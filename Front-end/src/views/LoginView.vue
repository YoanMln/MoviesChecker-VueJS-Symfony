<template>
  <div class="login-view">
    <div class="login-container">
      <h1 class="title-login">Connexion</h1>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="email"
            type="email"
            placeholder="votre@email.com"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input
            id="password"
            v-model="password"
            type="password"
            placeholder="••••••••"
            required
          />
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <button type="submit" :disabled="loading">
          {{ loading ? "Connexion..." : "Se connecter" }}
        </button>
      </form>

      <p class="register-link">
        Vous n'avez pas encore de compte ?
        <router-link to="/register">S'inscrire !</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { login } from "@/services/auth";

const router = useRouter();
const email = ref("");
const password = ref("");
const error = ref(null);
const loading = ref(false);

const handleLogin = async () => {
  error.value = null;
  loading.value = true;

  try {
    const response = await login(email.value, password.value);

    localStorage.setItem("token", response.token);

    if (response.user) {
      localStorage.setItem("user", JSON.stringify(response.user));
    }

    router.push({ name: "home" });
  } catch (err) {
    error.value = err.response?.data?.message || "Erreur de connexion";
    loading.value = false;
  }
};
</script>

<style scoped lang="scss">
.login-view {
  @include form-layout;
}

.login-container {
  @include container-regLog;
}

.title-login {
  @include title-regLog;
}

form {
  @include liquid_glass;
  @include flex-column;
  padding: 2.5rem;
  border-radius: 20px;
  gap: 1.5rem;
  backdrop-filter: blur(10px);
}

.form-group {
  @include flex-column;
  gap: 0.5rem;

  label {
    @include label-style;
  }
}

input {
  @include input;
}

.error-message {
  @include err-message;
}

button {
  @include btn-forms;
}

.register-link {
  @include switch-logs;
}
</style>
