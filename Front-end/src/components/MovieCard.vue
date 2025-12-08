<template>
  <div class="movie-card" @click="goToDetail">
    <div class="movie-poster">
      <img
        :src="movie.poster || '/placeholder-movie.jpg'"
        :alt="movie.title"
        @error="handleImageError"
      />
      <div class="container-movies-info">
        <div class="movie-rating" v-if="movie.imdb?.rating">
          <span class="star">⭐</span>
          <span class="rating-value">{{ movie.imdb.rating }}</span>
        </div>

        <div class="movie-info">
          <h3 class="movie-title">{{ movie.title }}</h3>
          <p class="movie-year" v-if="movie.year">
            {{ movie.year }}
          </p>
          <p class="movie-genre" v-if="movie.genres && movie.genres.length > 0">
            {{ movie.genres.map((g) => g.label).join(", ") }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  movie: {
    type: Object,
    required: true,
  },
});

const router = useRouter();

const goToDetail = () => {
  router.push({ name: "movie-detail", params: { id: props.movie.id } });
};

const handleImageError = (event) => {
  event.target.src = "/placeholder-movie.jpg";
};
</script>

<style scoped lang="scss">
.container-movies-info {
  text-align: center;
}

.movie-info {
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.movie-card {
  @include liquid_glass;
  width: 400px;
  -webkit-box-shadow: 0px 0px 11px 6px rgba(247, 247, 247, 0.84);
  -moz-box-shadow: 0px 0px 11px 6px rgba(247, 247, 247, 0.84);
  box-shadow: 0px 0px 11px 6px rgba(247, 247, 247, 0.84);
  cursor: pointer;

  &:hover {
    box-shadow: 0px 10px 25px 8px rgba(247, 247, 247, 0.6),
      0px 25px 50px 15px rgba(247, 247, 247, 0.25);
    
  }
}

.movie-poster {
  width: 100%;
  object-fit: cover;

  img {
    border-radius: 16px;
    width: 100%;
  }
}
</style>
