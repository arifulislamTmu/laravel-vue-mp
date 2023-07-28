<script setup>
import axios from "axios";
import { reactive, ref } from "vue";

const form = reactive({
  email: null,
  password: null,
});

const errorMessage = ref();
const loading = ref(false);
const handelForm = () => {
  errorMessage.value = "";
  loading.value = true;
  axios
    .post("/login", form)
    .then(() => {
      window.location.href = "admin/dashboard";
    })
    .catch((error) => {
      errorMessage.value = error.response.data.message;
    })
    .finally(() => {
      loading.value = false;
    });
};
</script>

<template>
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-body login-card-body">
        <div class="login-logo">
          <a href="#"><b>Admin</b>LTE</a>
        </div>
        <div v-if="errorMessage" class="alert alert-danger" role="alert">
          <small> {{ errorMessage }}</small>
        </div>
        <form @submit.prevent="handelForm">
          <div class="input-group mb-3">
            <input
              type="email"
              v-model="form.email"
              class="form-control"
              placeholder="Email"
            />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input
              type="password"
              v-model="form.password"
              class="form-control"
              placeholder="Password"
            />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-6"></div>
            <div class="col-4 text-end d-flex justify-content-end">
              <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                <div
                  v-if="loading"
                  class="spinner-border spinner-border-sm text-white mr-1"
                  role="status"
                ></div>
                <span v-else> Sign In</span>
              </button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="#">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="#" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
</template>
