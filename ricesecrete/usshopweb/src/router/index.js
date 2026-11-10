// src/router/index.ts

import { createRouter, createWebHashHistory } from 'vue-router'
import { useUserStore } from '@/store/userStore';
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
        path: '/mine',
        name: 'Mine',
        component: () => import('../pages/Mine.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path:"/search",
        name:"Search",
        component:() => import('../pages/Search.vue')
    },
    {
        path:"/wishlist",
        name:"Wishlist",
        component:() => import('@/pages/Wishlist.vue')
    },
    {
        path:"/message",
        name:"Message",
        component:() => import('@/pages/Message.vue')
    },
    {
        path:"/messagedetail",
        name:"MessageDetail",
        component:() => import('@/pages/MessageDetail.vue')
    },
    {
        path:"/goodsdetail",
        name:"GoodsDetail",
        component:() => import('@/pages/GoodsDetail.vue')
    },
    {
        path:"/setting",
        name:"Setting",
        component:() => import('@/pages/Setting.vue')
    },
    {
        path:"/myorders",
        name:"Myorders",
        component:() => import('@/pages/Myorders.vue')
    },
    {
        path:"/editprofile",
        name:"Editprofile",
        component:() => import('@/pages/editprofile/EditProfile.vue')
    },
    {
        path:"/nickname",
        name:"Nickname",
        component:() => import('@/pages/editprofile/Nickname.vue')
    },
    {
        path:"/preference",
        name:"Preference",
        component:() => import('@/pages/editprofile/Preference.vue')
    },
    {
        path:"/size",
        name:"Size",
        component:() => import('@/pages/editprofile/Size.vue')
    },
    {
        path:"/myaddress",
        name:"Myaddress",
        component:() => import('@/pages/address/Myaddress.vue')
    },
    {
        path:"/addaddress",
        name:"Addaddress",
        component:() => import('@/pages/address/Addaddress.vue')
    },
    {
        path:"/changecurrency",
        name:"Changecurrency",
        component:() => import('@/pages/ChangeCurrency.vue')
    },
    {
        path:"/language",
        name:"Language",
        component:() => import('@/pages/Language.vue')
    },
    {
        path:"/account",
        name:"Account",
        component:() => import('@/pages/Account.vue')
    },
    {
        path:"/changepassword",
        name:"ChangePassword",
        component:() => import('@/pages/Changepsd.vue')
    },
    {
        path:"/deleteaccount",
        name:"Deleteaccount",
        component:() => import('@/pages/Deleteaccount.vue')
    },
    {
        path:"/email",
        name:"Email",
        component:() => import('@/pages/email/email.vue')
    },
    {
        path:"/emailverify",
        name:"Emailverify",
        component:() => import('@/pages/email/emailverify.vue')
    },
    {
        path:"/phone",
        name:"Phone",
        component:() => import('@/pages/Phone.vue')
    },
    {
        path:"/returnpolicy",
        name:"ReturnPolicy",
        component:() => import('@/pages/ReturnPolicy.vue')
    },
    {
        path:"/privacy",
        name:"Privacy",
        component:() => import('@/pages/Privacy.vue')
    },
    {
        path:"/condition",
        name:"Condition",
        component:() => import('@/pages/Condition.vue')
    },
    {
        path:"/confirmorder",
        name:"ConfirmOrder",
        component:() => import('@/pages/order/ConfirmOrder.vue')
    },
    {
        path:"/choosegoods",
        name:"chooseGoods",
        component:() => import('@/pages/chooseOrder/chooseGoods.vue')
    },{
        path:"/login",
        name:"Login",
        component:() => import('@/pages/Login.vue')
    },
    {
        path:"/register",
        name:"Register",
        component:() => import('@/pages/Register.vue')
    },
    {
        path:"/wallet",
        name:"wallet",
        component:() => import('@/pages/wallet/Wallet.vue')
    },
    {
        path:"/recharge",
        name:"Recharge",
        component:() => import('@/pages/wallet/Recharge.vue')
    },
    {
        path:"/offlinepayment",
        name:"OfflinePayment",
        component:() => import('@/pages/order/OfflinePayment.vue')
    },
    {
        path:"/orderdetails",
        name:"OrderDetails",
        component:() => import('@/pages/order/OrderDetails.vue')
    },
    {
        path:"/shareorder",
        name:"ShareOrder",
        component:() => import('@/pages/order/ShareOrder.vue')
    },
    {
        path:"/choosesuborder",
        name:"chooseSubOrder",
        component:() => import('@/pages/chooseOrder/chooseSubOrder.vue')
    },
    {
        path:"/hotelhome",
        name:"HotelHome",
        component:() => import('@/pages/hotel/HotelHome.vue')
    },
    {
        path:"/hotelsearch",
        name:"HotelSearch",
        component:() => import('@/pages/hotel/HotelSearch.vue')
    },
    {
        path:"/ticketshome",
        name:"TicketsHome",
        component:() => import('@/pages/airTickets/TicketsHome.vue')
    },
    {
        path:"/hoteldetails",
        name:"HotelDetails",
        component:() => import('@/pages/hotel/HotelDetails.vue')
    },
    {
        path:"/hotelsubmitorder",
        name:"HotelSubmitOrder",
        component:() => import('@/pages/hotel/HotelSubmitOrder.vue')
    },
    {
        path:"/ticketssearch",
        name:"TicketsSearch",
        component:() => import('@/pages/airTickets/TicketsSearch.vue')
    },
    {
        path:"/ticketsdetail",
        name:"TicketsDetail",
        component:() => import('@/pages/airTickets/TicketsDetail.vue')
    },
    {
        path:"/ticketssubmitorder",
        name:"TicketsSubmitOrder",
        component:() => import('@/pages/airTickets/TicketsSubmitOrder.vue')
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});
const whiteList = ["/","/login","/register","/new","/category","/search","/choosegoods","/goodsdetail","/shareorder","/choosesuborder","/hotelhome"]
//全局前置守卫
router.beforeEach((to,from,next)=>{
    
    if(!whiteList.includes(to.path)){
        const store = useUserStore();
        if(store.token.token){
            next()
        }else{
            store.backRoute = to.path
            router.push("/login")
        }
    }else{
        next();
    }
})
export default router;