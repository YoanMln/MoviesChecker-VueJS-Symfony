<template>
  <nav class="navbar" :class="`navbar-${currentRoute}`">
    <div class="navbar-container">
      <router-link to="/" class="navbar-brand">
        🎬 MoviesCheck
      </router-link>
      
      <div class="navbar-menu">
        <router-link to="/movies" class="navbar-link">
          Films
        </router-link>
        <router-link to="/profile" class="navbar-link">
          Profil
        </router-link>
        <button @click="handleLogout" class="navbar-link logout-btn">
          Déconnexion
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();


const currentRoute = computed(() => {
  const path = route.path;
  if (path.includes('/movies')) return 'movies';
  if (path.includes('/login')) return 'login';
  if (path.includes('/register')) return 'register';
  if (path.includes('/profile')) return 'profile';
  return 'default';
});

const handleLogout = () => {
  localStorage.removeItem('token');
  router.push('/login');
};
</script>

<style scoped lang="scss">
.navbar {
  padding: 1rem 2rem;
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.navbar-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar-brand {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
  transition: transform 0.3s ease;
  
  &:hover {
    transform: scale(1.05);
  }
}

.navbar-menu {
  display: flex;
  gap: 2rem;
  align-items: center;
}

.navbar-link {
  color: white;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  
  &:hover {
    background: rgba(255, 255, 255, 0.1);
  }
  
  &.router-link-active {
    background: rgba(255, 255, 255, 0.2);
  }
}

.logout-btn {
  &:hover {
    background: rgba(244, 67, 54, 0.3);
  }
}

.navbar-movies {
  background: rgb(0, 0, 0);
}

.navbar-login {
  background: linear-gradient(to right, #434343, #000000);
}

.navbar-register {
  background: linear-gradient(to right, #434343, #000000);
}

.navbar-profile {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.navbar-default {
  background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
}
</style>