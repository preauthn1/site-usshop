// src/router/index.ts

import { createRouter, createWebHashHistory } from 'vue-router'
const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/category',
        name: 'Category',
        component: () => import('../pages/Category.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/new',
        name: 'New',
        component: () => import('../pages/New.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/cart',
        name: 'Cart',
        component: () => import('../pages/Cart.vue'),
        meta: {
            showTabbar: true //控制是否显示底部导航
        }
    },
    {
        path: '/me',
        name: 'Me',
        component: () => import('../pages/Me.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

//全局前置守卫
router.beforeEach((to,from,next)=>{
    if (to.meta && to.meta.showTabbar !== undefined) {
        // Your logic here
    } else {
        console.warn('Route does not have meta property:', to);
    }
    next();
})
export default router;