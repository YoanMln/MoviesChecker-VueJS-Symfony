<template>
  <div class="background-moviesView">
  <div class="movies-view">
    <div class="movies-header">
      <h1>Tous les films</h1>

      <div class="search-bar">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Rechercher un film....."
          @input="handleSearch"
        />
      </div>
    </div>

    <div v-if="loading" class="loading">
      <p>Chargement des films...</p>
    </div>

    <div v-else-if="error" class="error-message">
      {{ error }}
    </div>

    <div v-else class="movies-grid">
      <MovieCard v-for="movie in movies" :key="movie.id" :movie="movie" />
    </div>

    <div v-if="totalPages > 1" class="pagination">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="pagination-btn"
      >
        Précédent
      </button>
      <span class="page-info"> Page {{ currentPage }} / {{ totalPages }} </span>

      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="pagination-btn"
      >
        Suivant
      </button>
    </div>
  </div>
</div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { getMovies, searchMovies } from "@/services/movies";
import MovieCard from "@/components/MovieCard.vue";

const movies = ref([]);
const loading = ref(false);
const error = ref(null);
const searchQuery = ref("");
const currentPage = ref(1);
const itemsPerPage = ref(20);
const totalItems = ref(0);

const totalPages = computed(() => {
  return Math.ceil(totalItems.value / itemsPerPage.value);
});

const fetchMovies = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await getMovies(currentPage.value, itemsPerPage.value);

    console.log("Response complète:", response);
    console.log("member:", response.member);

    if (response.member) {
      movies.value = response.member;
      totalItems.value = response.totalItems || 0;

      if (movies.value.length > 0) {
        console.log("Premier film:", movies.value[0]);
      }
    } else if (response["hydra:member"]) {
      movies.value = response["hydra:member"];
      totalItems.value = response["hydra:totalItems"] || 0;
    } else {
      movies.value = response;
    }

    console.log("Movies:", movies.value);
  } catch (err) {
    error.value =
      err.response?.data?.message || "Erreur lors du chargement des films";
    console.error("Erreur:", err);
  } finally {
    loading.value = false;
  }
};

const handleSearch = async () => {
  if (searchQuery.value.trim() === "") {
    fetchMovies();
    return;
  }

  loading.value = true;
  error.value = null;

  try {
    const response = await searchMovies(searchQuery.value);

    if (response.member) {
      movies.value = response.member;
    } else if (response["hydra:member"]) {
      movies.value = response["hydra:member"];
    } else {
      movies.value = response;
    }
  } catch (err) {
    error.value = "Erreur lors de la recherche";
    console.error("Erreur:", err);
  } finally {
    loading.value = false;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchMovies();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    fetchMovies();
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

onMounted(() => {
  fetchMovies();
});
</script>

<style scoped lang="scss">

.background-moviesView {
  background-color: black;
}

.movies-view {
  min-height: 100vh;
  padding: 2rem;
  background: black;
}

.movies-header {
  text-align: center;
  color: white;
}

input {
  margin-top: 2rem;
  width: 400px;
  height: 40px;
}

.movies-grid {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 5rem;
  justify-content: center;
  margin-top: 5rem;
}
</style>
