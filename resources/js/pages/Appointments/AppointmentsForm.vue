<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { Form, Field } from "vee-validate";
import { useRouter, useRoute } from "vue-router";
import { useToastr } from "@/toastr.js";
import flatpickr from "flatpickr";
import "flatpickr/dist/themes/dark.css";
const router = useRouter();
const route = useRoute();
const tostr = useToastr();
const form = reactive({
  title: "",
  client_id: "",
  start_time: "",
  end_time: "",
  description: "",
});

const clients = ref();
const editStatus = ref(false);
const handleForm = (values, actions) => {
  if (editStatus) {
    editAppointmentForm(values, actions);
  } else {
    createAppointmentForm(values, actions);
  }
};

const createAppointmentForm = (values, actions) => {
  axios
    .post("/api/appointment/create", form)
    .then((response) => {
      router.push("/admin/appointments");
      tostr.success("Appointment successfully save");
    })
    .catch((error) => {
      actions.setErrors(error.response.data.errors);
    });
};

const editAppointmentForm = (values, actions) => {
  axios
    .put(`/api/appointment/${route.params.id}/edit`, form)
    .then((response) => {
      router.push("/admin/appointments");
      tostr.success("Appointment successfully updated");
    })
    .catch((error) => {
      actions.setErrors(error.response.data.errors);
    });
};
const getClient = () => {
  axios.get("/api/clients").then((response) => {
    clients.value = response.data;
    // console.log(clients.value);
  });
};

// edit data read koranor jonno
const getAppointment = () => {
  axios.get(`/api/appointment/${route.params.id}/edit`).then((response) => {
    form.title = response.data.title;
    form.client_id = response.data.client_id;
    form.start_time = response.data.start_time;
    form.end_time = response.data.end_time;
    form.description = response.data.description;
  });
};
onMounted(() => {
  if (route.name === "admin.appointments.edit") {
    editStatus.value = true;
    getAppointment();
  }

  flatpickr(".flatpickr", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
  });

  getClient();
});
</script>
<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">
            <span v-if="editStatus"> Edit</span>
            <span v-else>Create</span>
            appointment
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <router-link to="/admin/dashboard">Home</router-link>
            </li>
            <li class="breadcrumb-item">
              <router-link to="/admin/appointments"> Appointments </router-link>
            </li>
            <li class="breadcrumb-item active">
              <span v-if="editStatus"> Edit</span> <span v-else>Create</span>
            </li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <Form @submit="handleForm" v-slot="{ errors }">
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label">Title</label>
                  <input
                    v-model="form.title"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.title }"
                    placeholder="title"
                  />
                  <span class="invalid-feedback">{{ errors.title }}</span>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label">Client Name</label>
                  <select v-model="form.client_id" id="client" class="form-control">
                    <option v-for="client in clients" :key="client.id" :value="client.id">
                      {{ client.first_name }} {{ client.last_name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label">Start Date</label>
                  <input
                    v-model="form.start_time"
                    type="date"
                    class="form-control flatpickr"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label">End Time</label>
                  <input
                    v-model="form.end_time"
                    type="time"
                    class="form-control flatpickr"
                  />
                </div>
              </div>
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea
                    v-model="form.description"
                    class="form-control"
                    rows="3"
                    :class="{ 'is-invalid': errors.description }"
                  ></textarea>
                  <span class="invalid-feedback">{{ errors.description }}</span>
                </div>
              </div>
              <button class="btn btn-success">save</button>
            </div>
          </Form>
        </div>
      </div>
    </div>
  </div>
</template>
