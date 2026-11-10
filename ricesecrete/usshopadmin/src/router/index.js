import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import { isEmpty } from '@/libs/util'

import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import AppMain from '@/views/layout'

Vue.use(Router)

const logout = ()=>{
    return store.dispatch('handleLogOut').then(()=>{
        return Promise.reject()
    })
}

const dynamicRoutes = {
    path: '/',
    component: AppMain,
    redirect: '/home',
    children:[]
}

const staticRoutes = [
    { path: '/login', component: () => import('@/views/login'),name:'login'},
    { path: '/403', component: () => import('@/views/error/403'),name:'403'},
    { path: '/404', component: () => import('@/views/error/404'),name:'404'},
    { path: '/500', component: () => import('@/views/error/500'),name:'500'}
]

const createRouter = ()=>new Router({
    mode: 'history',
    scrollBehavior: () => ({ y: 0 }),
    routes: staticRoutes
})

const router = createRouter()

function resetRouter(){
    const newRouter = createRouter()
    router.matcher = newRouter.matcher
}

function checkRouteExist(name){
    let index = dynamicRoutes['children'].findIndex((item)=>{
        return item.name===name
    })
    if(index===-1){
        return false
    }else{
        return true
    }
}

function addDynamicRoutes (menuTree = []) {
    menuTree.forEach(function(menu) {
        if (menu.url) {
            if(!checkRouteExist(menu.name)){
                let url = menu.url
                if(menu.url_param){
                    url += menu.url_param
                }
                let route = {
                    path: url,
                    name: menu.name,
                    component: resolve => require([`@/views${menu.url}`], resolve),
                    meta: {
                        icon: menu.icon,
                        title:menu.title,
                        id: menu.id,
                        parent_id:menu.parent_id,
                        type:menu.type
                    }
                    // children: []
                }
                dynamicRoutes['children'].push(route)
            }
        }
        if (menu.children && menu.children.length >= 1) {
            addDynamicRoutes(menu.children)
        }
    })
}

function setDynamicRoutes(menuTree){
    resetRouter()
    addDynamicRoutes(menuTree)
    let routes = [dynamicRoutes]
    routes.push({ path: '*', redirect: '/404'})
    router.addRoutes(routes)
}

router.beforeEach((to, from, next) => {
    NProgress.start()
    const REDACTED
    if(!isEmpty(access_token)){
        if(to.name === 'login'){
            next({path: '/'})
        }else{
            if(store.getters.menuTree.length===0){
                store.dispatch('getProfile').then((res)=>{
                    if(res.code==1){
                        let menuTree = res.data.menuTree
                        if(menuTree){
                            setDynamicRoutes(menuTree)
                            next({ ...to, replace: true })
                        }
                    }else{
                        logout()
                    }
                }).catch(()=>{
                    logout()
                })
            }else{
                next()
            }
        }
    }else{
        if(to.name === 'login'){
            next()
        }else{
            next({
                name: 'login'
            })
        }
    }
})

router.afterEach(() => {
    NProgress.done()
})

export default router
