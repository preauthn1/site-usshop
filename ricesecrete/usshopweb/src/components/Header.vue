<template>
    <div class="header-wapper max-w-[425px] my-0 mx-auto">
        <div class="header-content max-w-[425px]">

            <div class="header-left-content">

                <div v-if="showBack" @click="router.back()" class="ml-[10px]">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: auto; height: 1em; vertical-align: middle; display: inline-block; line-height: 0; font-size: 24px; transform: none;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.4871 3.95288L16.513 5.04719L9.09603 12L16.513 18.9529L15.4871 20.0472L6.90344 12L15.4871 3.95288Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>
                </div>
                <div v-if="showMenu" @click="openside = true" class="ml-[10px] text-[18px] flex items-center">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: auto; height: 1em;">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21 4.5V6H3V4.5H21Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21 11.25V12.75H3V11.25H21Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21 19.5V18H3V19.5H21Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>
                </div>
                <div v-if="showMessage" @click="router.push('/message')" class="ml-[10px] relative">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: auto; height: 1em; vertical-align: middle; display: inline-block; line-height: 0; font-size: 24px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M22 4.5V19.5H2V4.5H22ZM20.5 6H3.5V6.43195L12 12.098L20.5 6.43196V6ZM20.5 8.23474V18H3.5V8.23473L12 13.9014L20.5 8.23474Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>
                    <div v-show="userstore.userInfo.notification_count > 0"
                        class=" absolute w-[8px] h-[8px] top-1 right-0 bg-[#FF0000] rounded-[8px] flex justify-center items-center">
                        <span class="text-[10px] text-[#fff]"></span>
                    </div>
                </div>
            </div>
            <div v-if="showSearch1" class="header-center-content">
                <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_26_5" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120"
                        height="24">
                        <rect width="120" height="24" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_26_5)">
                        <path
                            d="M26.3359 8.75879L23.3779 8.77148C23.3102 7.71354 22.9082 6.88411 22.1719 6.2832C21.444 5.68229 20.4665 5.38184 19.2393 5.38184C18.2236 5.38184 17.3942 5.61882 16.751 6.09277C16.1077 6.56673 15.7861 7.15495 15.7861 7.85742C15.7861 8.56836 16.0697 9.15234 16.6367 9.60938C17.2038 10.0579 18.3929 10.515 20.2041 10.9805C22.9548 11.6745 24.749 12.4277 25.5869 13.2402C26.4333 14.0527 26.8564 15.1868 26.8564 16.6426C26.8564 18.403 26.1963 19.8206 24.876 20.8955C23.5641 21.9619 21.8079 22.4951 19.6074 22.4951C17.2884 22.4951 15.4814 21.9365 14.1865 20.8193C12.9001 19.7021 12.2103 18.0941 12.1172 15.9951H15.1387C15.2233 17.2054 15.6761 18.1449 16.4971 18.8135C17.3265 19.4821 18.4479 19.8164 19.8613 19.8164C20.9277 19.8164 21.7995 19.5583 22.4766 19.042C23.1621 18.5257 23.5049 17.891 23.5049 17.1377C23.5049 16.8415 23.4076 16.5072 23.2129 16.1348C23.0267 15.7539 22.7855 15.4281 22.4893 15.1572C21.9984 14.7087 20.9066 14.277 19.2139 13.8623C18.8499 13.7692 18.5749 13.7015 18.3887 13.6592C16.2982 13.109 14.8213 12.4108 13.958 11.5645C13.0947 10.7096 12.6631 9.55013 12.6631 8.08594C12.6631 6.52865 13.2936 5.26335 14.5547 4.29004C15.8242 3.30827 17.5085 2.81738 19.6074 2.81738C21.6471 2.81738 23.2721 3.35482 24.4824 4.42969C25.7012 5.49609 26.319 6.93913 26.3359 8.75879ZM32.5739 3.2998H35.6843V10.6631H44.5329V3.2998H47.6432V22H44.5329V13.4434H35.6843V22H32.5739V3.2998ZM54.1732 3.2998H67.5795V6.04199H57.2328V10.9297H66.7796V13.5449H57.2328V19.1943H67.9222V22H54.1732V3.2998ZM73.5127 3.2998H86.919V6.04199H76.5723V10.9297H86.1192V13.5449H76.5723V19.1943H87.2618V22H73.5127V3.2998ZM92.8523 3.2998H96.153L104.862 17.29V3.2998H107.871V22H104.595L95.8357 8.00977V22H92.8523V3.2998Z"
                            fill="black" />
                    </g>
                </svg>

                <!-- <img src="../assets/image/logo2.png" class="h-[40px]" alt=""> -->
            </div>
            <div v-if="!showSearch1" class="header-center-content">
                <div class="bs-search-box relative" style="width: 200px;" @click="router.push('/search')">
                    <div v-if="searchKeyword.length <= 0" class="flex justify-between items-center w-full pr-4">
                        <van-swipe vertical class="notice-swipe" :autoplay="3000" :touchable="false"
                            :show-indicators="false">
                            <van-swipe-item v-for="item in keywordsList" class="flex items-center">
                                <span class="text-center text-[12px] whitespace-nowrap text-[#767676] overflow-hidden">{{ item }}</span>
                            </van-swipe-item>
                        </van-swipe>
                        <div class="bg-[#000] absolute right-2 w-[50px] h-[30px] flex justify-center items-center rounded-[15px]">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                style="width: var(--search-box-search-icon-size, 20px); height: var(--search-box-search-icon-size, 20px);">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.7474 7.74752C8.9197 6.57523 8.9197 4.67456 7.7474 3.50227C6.57511 2.32997 4.67444 2.32997 3.50215 3.50227C2.32985 4.67456 2.32985 6.57523 3.50215 7.74752C4.67444 8.91982 6.57511 8.91982 7.7474 7.74752ZM8.45451 2.79516C10.0173 4.35798 10.0173 6.89181 8.45451 8.45463C6.89169 10.0174 4.35786 10.0174 2.79504 8.45463C1.23222 6.89181 1.23222 4.35798 2.79504 2.79516C4.35786 1.23234 6.89169 1.23234 8.45451 2.79516Z"
                                    fill="var(--search-box-search-icon-color, white)"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.3 7.59294L10.5981 9.89104L9.89099 10.5981L7.5929 8.30005L8.3 7.59294Z"
                                    fill="var(--search-box-search-icon-color, white)"></path>
                            </svg>
                        </div>
                    </div>
                    <div v-else class="flex justify-between items-center h-full w-full pr-[10px]">
                        <span class="text-[16px] overflow-auto whitespace-nowrap w-full no-scrollbar">{{ searchKeyword }}</span>
                        <div class="flex-1" @click="clearKeyword">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9 16C12.866 16 16 12.866 16 9C16 5.13401 12.866 2 9 2C5.13401 2 2 5.13401 2 9C2 12.866 5.13401 16 9 16ZM11.0526 6L12 6.94737L9.94739 8.99998L12 11.0526L11.0527 12L9.00002 9.94735L6.94737 12L6 11.0526L8.05265 8.99998L6.00004 6.94737L6.94741 6L9.00002 8.05261L11.0526 6Z"
                                    fill="#959595"></path>
                            </svg>
                        </div>

                    </div>
                </div>
            </div>
            <div class="header-right-content">
                <div v-if="showSearch1" @click="router.push('/search')" class="mr-[10px]">
                    <svg aria-label="Search" width="24" height="24" viewBox="0 0 24 24" fill="none" handleExpose="true"
                        class="app-header__icon-right" style="">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.34315 5.34315C8.46734 2.21895 13.5327 2.21895 16.6569 5.34315C19.6022 8.2885 19.7708 12.9591 17.1627 16.102L21.4298 20.3692L20.3692 21.4298L16.102 17.1627C12.9591 19.7708 8.28851 19.6022 5.34315 16.6569C2.21895 13.5327 2.21895 8.46734 5.34315 5.34315ZM15.5962 6.40381C13.0578 3.8654 8.94221 3.8654 6.40381 6.40381C3.8654 8.94221 3.8654 13.0578 6.40381 15.5962C8.94221 18.1346 13.0578 18.1346 15.5962 15.5962C18.1346 13.0578 18.1346 8.94221 15.5962 6.40381Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>
                </div>
                <div v-if="showWishlist" class="mr-[10px]" @click="router.push('/wishlist')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.3938 4.98632C9.25839 2.96853 5.89126 3.00521 3.80009 5.09639C1.67157 7.22433 1.79597 10.2517 3.80009 12.8033C3.89976 12.9302 4.0098 13.0647 4.12892 13.2059L4.37892 13.4966L4.65088 13.803C4.69791 13.8553 4.74576 13.9081 4.79439 13.9615L5.09505 14.2877L5.41199 14.6249L5.74295 14.9709L6.08566 15.324L6.43784 15.6823L6.97891 16.2251L7.42184 16.6637L7.80117 17.0358L8.73799 17.9432L9.73299 18.8916L11.0299 20.108L12 21.0032L13.4782 19.634L15.0337 18.162L15.6098 17.6081L16.5782 16.6637L17.0211 16.2251L17.3832 15.8627L17.7393 15.5026L18.087 15.1467L18.4241 14.7969L18.7484 14.4551L19.0575 14.1231L19.3491 13.803L19.6211 13.4966L19.8711 13.2059C19.9902 13.0647 20.1002 12.9302 20.1999 12.8033C22.204 10.2517 22.3284 7.22433 20.1999 5.09639C18.0714 2.96787 14.6209 2.96787 12.493 5.09639L12 5.58882L11.507 5.09639L11.3938 4.98632ZM4.86075 6.15705C6.40346 4.61434 8.90405 4.61434 10.4462 6.1569L12 7.7089L13.5531 6.15766L13.6528 6.06125C15.2019 4.61504 17.6294 4.64717 19.1393 6.15705C20.6516 7.66897 20.6265 9.83175 19.0203 11.8768L18.8628 12.0727L18.6861 12.2843L18.4906 12.511L18.2766 12.7525L17.9223 13.1416L17.6645 13.4182L17.39 13.708C17.3429 13.7574 17.2951 13.8073 17.2466 13.8578L16.7926 14.3258L16.3041 14.8207L15.7825 15.3411L15.2662 15.8498L14.7911 16.3129C13.9912 17.0891 13.1541 17.884 12.3195 18.6639L12 18.9615L11.0797 18.1002C10.2795 17.3464 9.48556 16.5861 8.73228 15.8483L8.47296 15.5936L8.21581 15.3395L7.69483 14.8196L7.20668 14.325L6.90028 14.0102L6.47037 13.5612L6.20426 13.278L5.837 12.8786L5.50943 12.511L5.31389 12.2842L5.13717 12.0727L4.97973 11.8768C3.37349 9.83175 3.34842 7.66897 4.86075 6.15705Z"
                            fill="black"></path>
                    </svg>
                </div>
                <div class="mr-[10px] relative" @click="router.push('/cart')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="var(--shein-common-header-icon-color, #000)" class="bsc-header-cart__svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.796 6.04999H21.0108L19.6582 16.7695H6.63182L5.27917 6.04999H5.28453L5.15442 5H3V3.5H6.48003L6.796 6.04999ZM6.98187 7.54999L7.03628 7.98912L7.95444 15.2695H18.3356L19.3096 7.54999H6.98187Z">
                        </path>
                        <path
                            d="M9.14995 20.25C9.14995 20.9404 8.59032 21.5 7.89999 21.5C7.20965 21.5 6.65002 20.9404 6.65002 20.25C6.65002 19.5596 7.20965 19 7.89999 19C8.59032 19 9.14995 19.5596 9.14995 20.25Z">
                        </path>
                        <path
                            d="M19.6999 20.25C19.6999 20.9404 19.1402 21.5 18.4499 21.5C17.7596 21.5 17.2 20.9404 17.2 20.25C17.2 19.5596 17.7596 19 18.4499 19C19.1402 19 19.6999 19.5596 19.6999 20.25Z">
                        </path>
                    </svg>
                    <div v-if="cartstore.total > 0"
                        class=" absolute w-[14px] h-[14px] top-[-4px] right-[-5px] bg-[#FF0000] rounded-[8px] flex justify-center items-center">
                        <span class="text-[8px] text-[#fff]">{{ cartstore.total > 99 ? "99+" : cartstore.total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Sidebar :show="openside" @changeOpenside="openside = false" v-if="showMenu"/>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Sidebar from "./Sidebar.vue";
import { useRouter } from "vue-router";
import { useUserStore } from '@/store/userStore';
import { CartStore } from '@/store/cart';
const openside = ref(false)
const cartstore = CartStore()
const userstore = useUserStore()
const router = useRouter()
const props = defineProps({
    showMenu: {
        type: Boolean,
        default: true
    },
    showBack: {
        type: Boolean,
        default: false
    },
    showMessage: {
        type: Boolean,
        default: true
    },
    showWishlist: {
        type: Boolean,
        default: true
    },
    showSmallSearch: {
        type: Boolean,
        default: true
    },
    searchKeyword: {
        type: String,
        default: ''
    }
});
const emit = defineEmits()
const clearKeyword = () => {
    emit('clearKeyword');
    router.push('/search');
}
import { SearchService } from '@/api/search';
const keywordsList = ref([])
const getlist = async () => {
    const res = await SearchService.searchlist();
    keywordsList.value = [...res.keyword, ...res.keyword_sys];
}

const get_cartlist = async () => {

    await cartstore.getcartList()
    console.log(cartstore.total)
}
const showSearch1 = ref(props.showSmallSearch);
const windowScroll = () => {
    let scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    let opacity = Math.abs(Math.round(scrollTop)) / 250;
    if (opacity > 1) {
        showSearch1.value = false;
    } else {
        showSearch1.value = true;
    }
}

onMounted(() => {
    if (showSearch1.value == true) {
        window.addEventListener('scroll', windowScroll)
    }
    const store = useUserStore();
    if (store.token.token) {
        getlist();
        get_cartlist();
    }

})

onBeforeUnmount(() => {
    window.removeEventListener('scroll', windowScroll)
}) 
</script>

<style scoped src="../assets/css/header.css"></style>