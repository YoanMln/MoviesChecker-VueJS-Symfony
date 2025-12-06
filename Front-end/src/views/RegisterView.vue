<template>
  <div class="register-view">
    <div class="register-container">
      <h1 class="title-register">Inscription</h1>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="username">Nom d'utilisateur</label>
          <input id="username" v-model="username" type="text" required />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" v-model="email" type="email" required />
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input id="password" v-model="password" type="password" required />
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirmer le mot de passe</label>
          <input
            id="confirmPassword"
            v-model="confirmPassword"
            type="password"
            required
          />
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <button type="submit" :disabled="loading">
          {{ loading ? "Inscription..." : "S'inscrire" }}
        </button>
      </form>
      <p class="login-link">
        Déjà un compte ?
        <router-link to="/login">Se connecter</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { register } from "@/services/auth";

const router = useRouter();

const username = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const error = ref(null);
const loading = ref(false);

const handleRegister = async () => {
  error.value = null;

  if (password.value !== confirmPassword.value) {
    error.value = "Les mots de passe ne correspondent pas";
    return;
  }

  if (password.value.length < 6) {
    error.value = "Le mot de passe doit contenir au moins 6 caractères";
    return;
  }

  loading.value = true;

  try {
    const userData = {
      username: username.value,
      email: email.value,
      password: password.value,
    };

    await register(userData);

    router.push({ name: "login" });
  } catch (err) {
    error.value = err.response?.data?.message || "Erreur lors de l'inscription";
    loading.value = false;
  }
};
</script>

<style scoped lang="scss">
.register-view {
  @include form-layout;
}

.register-container {
  @include container-regLog;
}

.title-register {
  @include title-regLog;
}

form {
  @include liquid_glass;
  @include flex-column;
  padding: 5.5rem;
  gap: 1.5rem;
  backdrop-filter: blur(10px);
}

.form-group {
  @include flex-column;
  gap: 0.5rem;

  label {
    @include label-style;
  }
  input {
    @include input;
  }
  .error-message {
    @include err-message;
  }
}

button {
  @include btn-forms;
}
.login-link {
  @include switch-logs;
}
</style>
