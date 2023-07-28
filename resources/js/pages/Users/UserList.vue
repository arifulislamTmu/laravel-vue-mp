<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";

import { debounce } from "lodash";
//yup validation er jonno command
/*
 * npm i vee-validate --save
 * npm i -s yup
 *
 */
import { Form, Field } from "vee-validate";
import * as yup from "yup";
import { useToastr } from "../../toastr.js";
import { dateFormate } from "../../helper.js";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

const toastr = useToastr();
const users = ref({ data: [] });
const editing = ref(false);
const formValues = ref();
const form = ref(null);

const roles = ref([
  {
    name: "USER",
    value: 2,
  },
  {
    name: "ADMIN",
    value: 1,
  },
]);

//yup validation er jonno create new user
const userFormschema = yup.object({
  name: yup.string().required(),
  email: yup.string().required(),
  password: yup.string().required().min(3),
});

const addUser = () => {
  editing.value = false;
  $("#modal-userFrom").modal("show");
};
const createUser = (values, { resetForm, setErrors }) => {
  axios
    .post("/api/users", values)
    .then((response) => {
      users.value.data.unshift(response.data);
      $("#modal-userFrom").modal("hide");
      toastr.success("User Successfully Created ");
      resetForm();
    })
    .catch((error) => {
      // console.log(error);
      setErrors(error.response.data.errors);
      toastr.error("Email Already exists...");
    });
};

// edit user model
const edituserFormschema = yup.object({
  name: yup.string().required(),
  email: yup.string().required(),
});

const editUser = (user) => {
  form.value.resetForm();
  $("#modal-userFromEdit").modal("show");
  formValues.value = {
    id: user.id,
    name: user.name,
    email: user.email,
  };
};

const updateUser = (values, { resetForm, setErrors }) => {
  axios
    .post("/api/users/update", values)
    .then((response) => {
      // users.value.unshift(response.data);
      $("#modal-userFromEdit").modal("hide");
      toastr.success("User Successfully Updated ");
      resetForm();
      getUsers();
    })
    .catch((error) => {
      // console.log(error);
      toastr.error("Email Already exists...");
      setErrors(error.response.data.errors);
    });
};

//user role update
const userRole = (user, role) => {
  axios
    .patch(`/api/users/${user.id}/role-change`, {
      role: role,
    })
    .then(() => {
      toastr.success("Role successfully changed");
    });
};

//search korar jonno

const searchQuery = ref(null);

watch(
  searchQuery,
  debounce(() => {
    getUsers();
  }, 500)
);

// datadase theke data niye asar jonno
const getUsers = (page = 1) => {
  axios
    .get(`/api/users?page=${page}`, {
      params: {
        query: searchQuery.value,
      },
    })
    .then((response) => {
      users.value = response.data;
    });
};

onMounted(() => {
  getUsers();
});
</script>

<template>
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4 class="m-0">Users list</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Users list</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between">
              <div>
                <button type="button" class="btn btn-primary btn-sm" @click="addUser">
                  Add New User
                </button>
              </div>
              <div class="d-flex justify-content-end">
                <!-- button submit search  -->
                <!-- <input type="text" class="form-control" v-model="searchQuery" placeholder="Search........">
                 <button @click.prevent="search">search</button> -->
                <input
                  type="text"
                  class="form-control"
                  v-model="searchQuery"
                  placeholder="Search........"
                />
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Id</th>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Date</th>
                      <th scope="col">Role</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="users.data.length > 0">
                    <tr class="" v-for="(user, index) in users.data" :key="user.id">
                      <td scope="row">{{ index + 1 }}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ dateFormate(user.created_at) }}</td>
                      <td>
                        <select
                          class="form-control"
                          @change="userRole(user, $event.target.value)"
                        >
                          <option
                            v-for="role in roles"
                            :key="role"
                            :value="role.value"
                            :selected="role.name === user.role"
                          >
                            {{ role.name }}
                          </option>
                        </select>
                      </td>
                      <td>
                        <a
                          href=""
                          @click.prevent="editUser(user)"
                          class="btn btn-success btn-sm"
                        >
                          Edit</a
                        >
                        <button class="btn btn-danger btn-sm mx-1">Delete</button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5" class="text-center">No data found</td>
                    </tr>
                  </tbody>
                </table>
                <Bootstrap5Pagination :data="users" @pagination-change-page="getUsers" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- user modal create  -->
  <div class="modal fade" id="modal-userFrom">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title"> Edit user</h5> -->
          <h5 class="modal-title">Add new user</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <Form
          ref="form"
          @submit="createUser"
          v-slot="{ errors }"
          :validation-schema="userFormschema"
        >
          <div class="modal-body">
            <div class="form-group mb-2">
              <label class="form-label" for="">User name </label>
              <Field
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                type="text"
                name="name"
              />
              <span class="invalid-feedback">{{ errors.name }}</span>
            </div>
            <div class="form-group mb-2">
              <label class="form-label" for="">User email </label>
              <Field
                class="form-control"
                :class="{ 'is-invalid': errors.email }"
                type="text"
                name="email"
              />
              <span class="invalid-feedback">{{ errors.email }}</span>
            </div>
            <div class="form-group mb-2">
              <label class="form-label" for="">User password </label>
              <Field
                class="form-control"
                :class="{ 'is-invalid': errors.password }"
                type="password"
                name="password"
              />
              <span class="invalid-feedback">{{ errors.password }}</span>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <!-- <button type="submit" class="btn btn-primary" v-if="editing">  Update </button> -->
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </Form>
      </div>
    </div>
  </div>
  <!-- user modal Edit  -->
  <div class="modal fade" id="modal-userFromEdit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit user</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <Form
          ref="form"
          @submit="updateUser"
          v-slot="{ errors }"
          :validation-schema="edituserFormschema"
          :initial-values="formValues"
        >
          <div class="modal-body">
            <div class="form-group mb-2">
              <label class="form-label" for="">User name </label>
              <Field
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                type="text"
                name="name"
              />
              <span class="invalid-feedback">{{ errors.name }}</span>
            </div>
            <div class="form-group mb-2">
              <label class="form-label" for="">User email </label>
              <Field
                class="form-control"
                :class="{ 'is-invalid': errors.email }"
                type="text"
                name="email"
              />
              <span class="invalid-feedback">{{ errors.email }}</span>
            </div>
            <Field type="hidden" name="id" />
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </Form>
      </div>
    </div>
  </div>
</template>
