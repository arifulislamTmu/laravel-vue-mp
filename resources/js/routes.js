import Dashboard from './components/Dashboard.vue';
import Appointments from './pages/Appointments/Appointments.vue';
import Users from './pages/Users/UserList.vue';
import Login from './pages/auth/Login.vue';
import AppointmentsForm from './pages/Appointments/AppointmentsForm.vue'
import { createWebHistory, createRouter } from "vue-router";
const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
    },
    {
        path: "/admin/dashboard",
        name: "dashboard",
        component: Dashboard,
    },
    {
        path: "/admin/appointments",
        name: "admin.appointments",
        component: Appointments,
    },
    {
        path: "/admin/appointments/create",
        name: "admin.appointments.create",
        component: AppointmentsForm,
    },

    {
        path: "/admin/appointments/:id/edit",
        name: "admin.appointments.edit",
        component: AppointmentsForm,
    },
    {
        path: "/admin/users",
        name: "Users",
        component: Users,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
