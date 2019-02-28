/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import Vue from 'vue'

import GateRestriction from './GateRestriction'

Vue.prototype.$gate = new GateRestriction(window.user)

window.Fire = new Vue();

/* Vue select **/
import vSelect from 'vue-select/src/index'

import Prism from 'Prismjs'
Vue.component('v-select', vSelect)

/* Prism */
Vue.use(Prism)
import 'prismjs/themes/prism-solarizedlight.css'

/* Pagination */
Vue.component('pagination', require('laravel-vue-pagination'));

/*  MomentJS */
import moment from 'moment';

/** Filtres */
import Filter from './Filter'


/* Vue Progress Bar */
import VueProgressBar from 'vue-progressbar'
const options = {
    color: '#2dce89',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
Vue.use(VueProgressBar, options)

/* Toasted */
import Toasted from 'vue-toasted';

Vue.use(Toasted)


// register the toast with the custom message
Vue.toasted.register('flash',
    (payload) => {

        // if there is no message passed show default message
        if (!payload.message) {
            return "Oops.. Something Went Wrong.."
        }

        // if there is a message show it with the message
        return payload.message;
    },
    {
        theme: "toasted-primary",
        position: "bottom-right",
        duration: 3000,
        className: 'bg-primary'
    }
)

/* SweetAlert */
import swal from 'sweetalert';

window.swal = swal

/* Vue Router*/
import VueRouter from 'vue-router'

const routes = [
    {path: '*', redirect: '/' },
    {path: '/', component: require('./components/DashboardComponent.vue').default},
    {path: '/profile', component: require('./components/Profile/ProfileComponent.vue').default},
    {path: '/users', component: require('./components/Users/UsersComponent.vue').default},
    {path: '/societies', component: require('./components/SocietiesComponent').default},
    {path: '/developper', component: require('./components/DevelopperComponent').default},
    {path: '/tickets', name: 'tickets', component: require('./components/Tickets/TicketsComponent').default},
    {path: '/tickets/:id', name: 'ticket', params: {id : ''}, component: require('./components/Tickets/TicketComponent').default},
    {path: '/knowledges', component: require('./components/underConstruction').default},
    {path: '/projects', component: require('./components/underConstruction').default},
    {path: '/nkeep', component: require('./components/underConstruction').default},

]
Vue.use(VueRouter)


const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);
Vue.component(
    'not-found',
    require('./components/404').default
);
Vue.component(
    'message-component',
    require('./components/Tickets/TicketMessagesComponent').default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data() {
        return {
            user: {}
        }
    },
    methods: {
        getProfilePhoto() {
            if (this.user.photo !== 'profile.png') {
                this.user.photo = '/img/profile/' + this.user.name + '/' + this.user.photo
            } else {
                this.user.photo = '/img/profile/profile.png'
            }
        },
    },
    created() {
        axios.get('/api/profile').then(result => {
            this.user = result.data
            this.getProfilePhoto()
        })
        Fire.$on('UpdatePhoto', () => {
            axios.get('/api/profile').then(result => {
                this.user = result.data
                this.getProfilePhoto()
            })
        })
    }
});
