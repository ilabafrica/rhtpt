import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    routes:[
        {
            path: "/login",
            component: require('./components/authentication/Login.vue'),
            meta: {
                forVisitors: true
            }
        },
        {
            path: "/register",
            component: require('./components/authentication/Register.vue'),
            meta: {
                forVisitors: true
            }
        },
        {
            path: "/feed",
            component: require('./components/Feed.vue'),
            meta: {
                forAuth: true
            }
        },
        {
            path: "/products/create",
            component: require('./components/product/Create.vue'),
            meta: {
                forAuth: true
            }
        },
        {
            path: "/products/:product/edit",
            component: require('./components/product/Edit.vue'),
            meta: {
                forAuth: true
            }
        },
        {
            path: "/home",
            component: require('./components/landing/Home.vue'),
            meta: {
                forAuth: true
            }
        },
        {
            path: "/programs",
            component: require('./components/program/Programs.vue'),
            meta: {
                forAuth: true
            }
        }
    ],

    linkActiveClass: 'active',

    mode: 'history'
})

export default router