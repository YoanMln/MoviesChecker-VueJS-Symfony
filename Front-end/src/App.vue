<template>
  <div id="app">
    <header>
      <nav>
        <router-link to="/">Accueil</router-link>
        <router-link to="/movies">Films</router-link>

        <div class="nav-right">
          <template v-if="!isLoggedIn">
            <router-link to="/login">Connexion</router-link>
            <router-link to="/register">Inscription</router-link>
          </template>
          <template v-else>
            <router-link to="/profile">Profil</router-link>
            <button @click="logout" class="logout-btn">Déconnexion</button>
          </template>
        </div>
      </nav>
    </header>

    <main>
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const token = ref(localStorage.getItem("token"));

const isLoggedIn = computed(() => !!token.value);

const logout = () => {
  localStorage.removeItem("token");
  token.value = null;
  router.push({ name: "login" });
};

onMounted(() => {
  window.addEventListener("storage", () => {
    token.value = localStorage.getItem("token");
  });
});
</script>

<style scoped lang="scss">
header {
  background-color: grey;
  padding: 1rem;
  border-radius: 10px;

  nav {
    display: flex;
    justify-content: space-between;

    a {
      color: white;
      text-decoration: none;
      margin-right: 1rem;
    }

    .nav-right {
      display: flex;
      gap: 1rem;
    }
  }
}
</style>
