<template>
  <div class="movie-detail-view">
    <div v-if="loading" class="loading">
      <p>Chargement...</p>
    </div>

    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
    </div>

    <div v-else-if="movie" class="movie-detail">
      <div class="movie-header">
        <div class="movie-poster">
          <img v-if="movie.poster" :src="movie.poster" :alt="movie.title" />
          <div v-else class="no-poster">Pas d'image</div>
        </div>

        <div class="movie-info">
          <h1>{{ movie.title }}</h1>

          <div class="movie-meta">
            <span v-if="movie.releaseYear" class="year">{{
              movie.releaseYear
            }}</span>
            <span v-if="movie.duration" class="duration"
              >{{ movie.duration }} min</span
            >
            <span v-if="movie.averageRating" class="rating">
              ⭐ {{ movie.averageRating.toFixed(1) }} / 10
            </span>
          </div>

          <div v-if="movie.genres && movie.genres.length" class="genres">
            <span
              v-for="genre in movie.genres"
              :key="genre.id"
              class="genre-badge"
            >
              {{ genre.name }}
            </span>
          </div>

          <div v-if="movie.synopsis" class="synopsis">
            <h2>Synopsis</h2>
            <p>{{ movie.synopsis }}</p>
          </div>

          <div class="movie-actions">
            <button class="btn-rating">Noter ce film</button>
            <button class="btn-list">Ajouter à ma liste</button>
            <button class="btn-review">Écrire une critique</button>
          </div>
        </div>
      </div>

      <div v-if="movie.cast && movie.cast.length" class="section">
        <h2>Casting</h2>
        <div class="cast-list">
          <div v-for="actor in movie.cast" :key="actor.id" class="cast-item">
            <img
              v-if="actor.profilePath"
              :src="`https://image.tmdb.org/t/p/w185${actor.profilePath}`"
              :alt="actor.name"
            />
            <div v-else class="no-photo">👤</div>
            <p class="actor-name">{{ actor.name }}</p>
          </div>
        </div>
      </div>

      <div v-if="movie.directors && movie.directors.length" class="section">
        <h2>Réalisateur{{ movie.directors.length > 1 ? "s" : "" }}</h2>
        <div class="directors-list">
          <span v-for="(director, index) in movie.directors" :key="director.id">
            {{ director.name
            }}{{ index < movie.directors.length - 1 ? ", " : "" }}
          </span>
        </div>
      </div>

      <div v-if="movie.reviews && movie.reviews.length" class="section">
        <h2>Critiques ({{ movie.reviews.length }})</h2>
        <div class="reviews-list">
          <div
            v-for="review in movie.reviews"
            :key="review.id"
            class="review-item"
          >
            <div class="review-header">
              <strong>{{ review.user?.username || "Utilisateur" }}</strong>
              <span v-if="review.rating" class="review-rating">
                ⭐ {{ review.rating }}/10
              </span>
            </div>
            <p class="review-content">{{ review.content }}</p>
            <span class="review-date">
              {{ new Date(review.createdAt).toLocaleDateString("fr-FR") }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { getMovie } from "@/services/movies";

const route = useRoute();

const movie = ref(null);
const loading = ref(false);
const error = ref(null);

const fetchMovie = async () => {
  loading.value = true;
  error.value = null;

  try {
    const id = route.params.id;
    console.log("Récupération du film ID:", id);

    const data = await getMovie(id);
    console.log("Données reçues:", data);

    movie.value = data;
  } catch (err) {
    console.error("Erreur:", err);
    error.value = "Impossible de charger le film";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchMovie();
});
</script>

<style scoped lang="scss">
.movie-header {
  display: flex;
  justify-content: center;
  gap: 10rem;
}

.movie-detail-view {
  background-color: black;
  min-height: 100vh;
  margin-top: 5rem;
}

.movie-poster img {
  width: 500px;
  border-radius: 20px;
}

.movie-info {
  @include liquid_glass_card;
  padding: 5rem;
  color: white;
}

h1 {
  text-align: center;
  
}

.movie-actions {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  height: 500px;
}

button {
  @include btn-generic;
}
</style>
