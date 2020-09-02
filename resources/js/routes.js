import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./lawyers/Lawyers').default,
        name: 'home'
    },
    {
        path: '/details/:id',
        component: require('./lawyers/LawyerDetails').default,
        name: 'details'
    },
    {
        path: '/details/:id/create',
        component: require('./appointment/BookAppointment.vue').default,
        name: 'createAppointment',
        props: true
    },
    {
        path: '/auth/login',
        component: require('./auth/Login').default,
        name: 'login'
    },
    {
        path: '/auth/register',
        component: require('./auth/Register').default,
        name: 'register'
    }
    ,
    {
        path: '/dashboard/lawyer',
        component: require('./dashboard/LawyerDashboard').default,
        name: 'lawyerDashboard'
    }
    ,
    {
        path: '/dashboard/client',
        component: require('./dashboard/ClientDashboard').default,
        name: 'clientDashboard'
    },
    {
        path: '/dashboard/client/reschedule',
        component: require('./appointment/RescheduleAppointment').default,
        name: 'reschedule',
        props: true
    }
];

const router = new VueRouter({
    routes, // short for `routes: routes`
    mode: 'history'
});

export default router;
