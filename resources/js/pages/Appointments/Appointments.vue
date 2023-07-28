<script setup>
import { onMounted, ref } from "vue";
import Swal from "sweetalert2";
import axios from "axios";
const AppointmentStatus = { Scheduled: 1, Confirmed: 2, Closed: 3 };

const appointments = ref({ data: [] });
const appointmentsCount = ref();
const scheduleCountCount = ref();
const confirmCountCount = ref();
const canceledCountCount = ref();

const getAppointments = (status) => {
  const params = {};
  if (status) {
    params.status = status;
  }

  axios
    .get("/api/appointments/", {
      params: params,
    })
    .then((response) => {
      appointments.value = response.data.data;
      appointmentsCount.value = response.data.totalCount;
      scheduleCountCount.value = response.data.scheduleCount;
      confirmCountCount.value = response.data.confirmCount;
      canceledCountCount.value = response.data.canceledCount;
      // console.log(appointmentsCount.value );
    });
};

const appointmentDelete = (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`/api/appointment/${id}`)
      .then((response) => {

      });
      appointments.value.data = appointments.value.data.filter(
        (appointment) => appointment.id !== id
      );
      Swal.fire("Deleted!", "Your file has been deleted.", "success");
    }
  });
};

onMounted(() => {
  getAppointments();
});
</script>
<style>
.btn-default.hover,
.btn-default:active,
.btn-default:hover {
  color: #fff !important;
  background-color: #545b62 !important;
  border-color: #4e555b !important;
}
</style>
<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Appointments</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Appointments</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-6">
          <router-link class="btn btn-primary" to="/admin/appointments/create">
            Add New Appointment
          </router-link>
        </div>
        <div class="col-6 d-flex justify-content-end">
          <button @click="getAppointments()" class="btn btn-sm btn-secondary ml-1">
            All
            <strong class="badge badge-pill bg-light"> {{ appointmentsCount }} </strong>
          </button>
          <button
            @click="getAppointments(AppointmentStatus.Scheduled)"
            class="btn btn-sm btn-default ml-1"
          >
            Scheduled
            <strong class="badge badge-pill bg-primary">
              {{ scheduleCountCount }}
            </strong>
          </button>
          <button
            @click="getAppointments(AppointmentStatus.Confirmed)"
            class="btn btn-sm btn-default ml-1"
          >
            Confirmed
            <strong class="badge badge-pill bg-success"> {{ confirmCountCount }} </strong>
          </button>
          <button
            @click="getAppointments(AppointmentStatus.Closed)"
            class="btn btn-sm btn-default ml-1"
          >
            Closed
            <strong class="badge badge-pill bg-danger"> {{ canceledCountCount }} </strong>
          </button>
        </div>

        <div class="col-12 mt-2">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Sl</th>
                      <th scope="col">Client name</th>
                      <th scope="col">Title</th>
                      <th scope="col">Start time</th>
                      <th scope="col">End time</th>
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class=""
                      v-for="(appointment, index) in appointments.data"
                      :key="appointment.id"
                    >
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ appointment.client.first_name }}
                        {{ appointment.client.last_name }}
                      </td>
                      <td>{{ appointment.title }}</td>
                      <td>{{ appointment.start_time }}</td>
                      <td>{{ appointment.end_time }}</td>
                      <td>
                        <strong
                          class="badge"
                          :class="`badge-${appointment.status.color}`"
                          >{{ appointment.status.name }}</strong
                        >
                      </td>
                      <td>
                        <router-link
                          :to="`/admin/appointments/${appointment.id}/edit`"
                          class="btn btn-sm btn-success mr-1"
                          >Edit</router-link
                        >
                        <button
                          @click="appointmentDelete(appointment.id)"
                          class="btn btn-sm btn-danger"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
