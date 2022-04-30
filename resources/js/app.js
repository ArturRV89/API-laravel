require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Vuelidate from "vuelidate";

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuelidate);



import App from "./components/App";
import Desks from "./components/Desks/Desks";
import Example from "./components/Example";
import Home from "./components/Home";
import About from "./components/About";
import ShowDesks from "./components/Desks/ShowDesks";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            name: 'home',
            path: '/web/home',
            component: Home
        },
        {
            name: 'about',
            path: '/web/about',
            component: About
        },
        {
            name: 'example',
            path: '/web/example',
            component: Example
        },
        {
            name: 'desks',
            path: '/web/desks',
            component: Desks
        },
        {
            name: 'showDesks',
            path: '/web/desks/:deskId',
            component: ShowDesks,
            props: true
        }
    ]
})

const app = new Vue({
    el: '#app',
    components: {App},
    router
})

