<template>

    <van-popup v-model:show="props.show" position="left"
        :style="{ width: '80%', 'margin': '0 auto', height: '100vh', 'max-width': '425px' }" @click-overlay="handshow">
        <div class="flex flex-col justify-between slid-content" v-show="isshow">
            <van-sticky class=" shrink-0">
                <van-tabs v-model:active="active" color="#000" title-active-color="#000"
                    title-inactive-color="#0000009A" class="font-[14px]" @click-tab="changTab">
                    <van-tab v-for="tab in store.menuList" :title="tab.name" class="text-black"></van-tab>
                </van-tabs>
            </van-sticky>
            <div class=" border-t border-solid border-[#F6F6F6] pb-[20px]  overflow-y-auto">
                <div class="bg-white pl-5 pr-4 pt-4 flex justify-between items-center overflow-y-auto h-full"
                    v-for="(item, index) in secodList" @click="handlemenu(index)">
                    <div class="sild-item" :style="{ '--acImg': `url(${item?.img})` }">
                        <!--  <img src="../assets/image/sildbar.webp" alt=""> -->
                    </div>
                    <a class="sild-title">{{ item?.name }}</a>
                    <span v-if="item.is_children > 0"
                        style="vertical-align: middle; display: inline-block; line-height: 0; color: rgb(149, 149, 149);">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 16px; height: 16px;">
                            <path
                                d="M5.76257 11.7123L6.82323 12.773L11.5962 8.00002L6.82323 3.22705L5.76257 4.28771L9.47501 8.00002L5.76257 11.7123Z"
                                fill="#959595"></path>
                        </svg>
                    </span>
                </div>
            </div>
            <div v-if="false"
                class="shrink-0 h-[50px] fixed border-t border-solid border-[#E5E5E5] bottom-0 z-10 bg-white w-full flex items-center justify-around">

                <span @click="handshow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 24px; height: 24px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.2875 21.5V14.375H12.7125V21.5H21.5V10.4563L12 2.5L2.5 10.4563V21.5H11.2875ZM4 11.1561L12 4.45605L20 11.1566V20.0001H14.2125V12.8751H9.7875V20.0001H4V11.1561Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

                <span @click="router.push('/new')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 24px; height: 24px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.3938 4.98632C9.25839 2.96853 5.89126 3.00521 3.80009 5.09639C1.67157 7.22433 1.79597 10.2517 3.80009 12.8033C3.89976 12.9302 4.0098 13.0647 4.12892 13.2059L4.37892 13.4966L4.65088 13.803C4.69791 13.8553 4.74576 13.9081 4.79439 13.9615L5.09505 14.2877L5.41199 14.6249L5.74295 14.9709L6.08566 15.324L6.43784 15.6823L6.97891 16.2251L7.42184 16.6637L7.80117 17.0358L8.73799 17.9432L9.73299 18.8916L11.0299 20.108L12 21.0032L13.4782 19.634L15.0337 18.162L15.6098 17.6081L16.5782 16.6637L17.0211 16.2251L17.3832 15.8627L17.7393 15.5026L18.087 15.1467L18.4241 14.7969L18.7484 14.4551L19.0575 14.1231L19.3491 13.803L19.6211 13.4966L19.8711 13.2059C19.9902 13.0647 20.1002 12.9302 20.1999 12.8033C22.204 10.2517 22.3284 7.22433 20.1999 5.09639C18.0714 2.96787 14.6209 2.96787 12.493 5.09639L12 5.58882L11.507 5.09639L11.3938 4.98632ZM4.86075 6.15705C6.40346 4.61434 8.90405 4.61434 10.4462 6.1569L12 7.7089L13.5531 6.15766L13.6528 6.06125C15.2019 4.61504 17.6294 4.64717 19.1393 6.15705C20.6516 7.66897 20.6265 9.83175 19.0203 11.8768L18.8628 12.0727L18.6861 12.2843L18.4906 12.511L18.2766 12.7525L17.9223 13.1416L17.6645 13.4182L17.39 13.708C17.3429 13.7574 17.2951 13.8073 17.2466 13.8578L16.7926 14.3258L16.3041 14.8207L15.7825 15.3411L15.2662 15.8498L14.7911 16.3129C13.9912 17.0891 13.1541 17.884 12.3195 18.6639L12 18.9615L11.0797 18.1002C10.2795 17.3464 9.48556 16.5861 8.73228 15.8483L8.47296 15.5936L8.21581 15.3395L7.69483 14.8196L7.20668 14.325L6.90028 14.0102L6.47037 13.5612L6.20426 13.278L5.837 12.8786L5.50943 12.511L5.31389 12.2842L5.13717 12.0727L4.97973 11.8768C3.37349 9.83175 3.34842 7.66897 4.86075 6.15705Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

                <span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 24px; height: 24px;">
                        <rect width="24" height="24" fill="white" fill-opacity="0.01"></rect>
                        <path
                            d="M21.6 12C21.6 11.4477 21.1523 11 20.6 11H18.6V16H20.6C21.1523 16 21.6 15.5523 21.6 15V12Z"
                            fill="currentColor"></path>
                        <path
                            d="M1.40002 15C1.40002 15.5523 1.84774 16 2.40002 16L4.40002 16L4.40002 11L2.40002 11C1.84774 11 1.40002 11.4477 1.40002 12L1.40002 15Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7 10C7 9.44772 7.44772 9 8 9H15C15.5523 9 16 9.44772 16 10V14C16 14.5523 15.5523 15 15 15H8C7.44772 15 7 14.5523 7 14V10ZM6 10C6 8.89543 6.89543 8 8 8H15C16.1046 8 17 8.89543 17 10V14C17 15.1046 16.1046 16 15 16H8C6.89543 16 6 15.1046 6 14V10Z"
                            fill="currentColor"></path>
                        <path
                            d="M12.3916 11.883C12.3916 12.1172 12.4846 12.3418 12.6502 12.5074C12.8158 12.673 13.0404 12.766 13.2746 12.766C13.5088 12.766 13.7334 12.673 13.899 12.5074C14.0646 12.3418 14.1576 12.1172 14.1576 11.883C14.1576 11.7671 14.1348 11.6522 14.0904 11.5451C14.046 11.438 13.981 11.3406 13.899 11.2586C13.817 11.1766 13.7197 11.1116 13.6125 11.0672C13.5054 11.0228 13.3906 11 13.2746 11C13.1587 11 13.0438 11.0228 12.9367 11.0672C12.8296 11.1116 12.7322 11.1766 12.6502 11.2586C12.5682 11.3406 12.5032 11.438 12.4588 11.5451C12.4144 11.6522 12.3916 11.7671 12.3916 11.883Z"
                            fill="currentColor"></path>
                        <path
                            d="M8.5 11.883C8.5 12.1172 8.59303 12.3418 8.75863 12.5074C8.92423 12.673 9.14882 12.766 9.38301 12.766C9.6172 12.766 9.8418 12.673 10.0074 12.5074C10.173 12.3418 10.266 12.1172 10.266 11.883C10.266 11.6488 10.173 11.4242 10.0074 11.2586C9.8418 11.093 9.6172 11 9.38301 11C9.14882 11 8.92423 11.093 8.75863 11.2586C8.59303 11.4242 8.5 11.6488 8.5 11.883Z"
                            fill="currentColor"></path>
                        <path
                            d="M20 16C20 16 19.1 16 18.6 16C18.6 14.5 18.7188 13.0781 18.6008 11.0723C18.4828 9.26883 17.6833 7.57776 16.3646 6.34221C15.0459 5.10665 13.3066 4.41914 11.4998 4.41914C9.69288 4.41914 7.95366 5.10665 6.63493 6.34221C5.3162 7.57776 4.51672 9.26883 4.39869 11.0723C4.39869 14 4.39869 15.8235 4.39869 15.8235C4.39869 15.8235 2.79869 15.6483 2.48692 15.3364C2.17515 15.0246 2 14.6016 2 14.1606V12.7352C2.00048 12.4182 2.09173 12.1079 2.26294 11.8411C2.43414 11.5743 2.67816 11.3621 2.96613 11.2296C3.04689 9.01972 3.98132 6.92729 5.57286 5.39245C7.1644 3.85762 9.289 3 11.4998 3C13.7105 3 15.8351 3.85762 17.4267 5.39245C19.0182 6.92729 19.9526 9.01972 20.0334 11.2296C20.0334 13 20 14.0695 20 16Z"
                            fill="currentColor"></path>
                        <path d="M19.4004 14V15.5C19.4004 17.5 16.1 20.5 13.6 20.5" stroke="currentColor"
                            stroke-width="1.5"></path>
                        <path
                            d="M11 20.4998C11 19.8371 11.5373 19.2998 12.2 19.2998H13.8C14.4627 19.2998 15 19.8371 15 20.4998C15 21.1625 14.4627 21.6998 13.8 21.6998H12.2C11.5373 21.6998 11 21.1625 11 20.4998Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

                <span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 24px; height: 24px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12.0009 2.5C14.7478 2.5 16.9746 4.72681 16.9746 7.47371C16.9746 10.1741 14.8227 12.3718 12.1401 12.4455L12.0009 12.4474C9.254 12.4474 7.02719 10.2206 7.02719 7.47371C7.02719 4.77336 9.17915 2.57563 11.8617 2.50191L12.0009 2.5ZM21.0018 21.4472C20.2641 17.1436 16.515 13.8685 12.0009 13.8685L11.8231 13.8702C7.38868 13.9549 3.72797 17.2002 3 21.4472H21.0018ZM5.0713 19.7943C6.29049 17.1615 8.95841 15.3685 12.0009 15.3685L12.1727 15.3704C15.2007 15.4378 17.8339 17.2801 18.9995 19.947H5.00198L5.0713 19.7943ZM8.52719 7.47371C8.52719 5.55523 10.0824 4 12.0009 4C13.9194 4 15.4746 5.55523 15.4746 7.47371C15.4746 9.39219 13.9194 10.9474 12.0009 10.9474C10.0824 10.9474 8.52719 9.39219 8.52719 7.47371Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

            </div>
        </div>
        <div class="subcategory" v-show="!isshow">
            <div
                class="fixed top-0 h-[44px] w-full flex justify-center items-center text-center border-b border-solid border-[#E5E5E5]">
                <div class=" relative text-[15px] font-bold">{{ titleName }}</div>
                <span class=" absolute top-[10px] left-0" @click="isshow = true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 24px; height: 24px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.4871 3.95288L16.513 5.04719L9.09603 12L16.513 18.9529L15.4871 20.0472L6.90344 12L15.4871 3.95288Z"
                            fill="currentColor"></path>
                    </svg>
                </span>

            </div>
            <div class=" p-3 pt-0 mt-[44px]">
                <!-- <div class="subcategory-title">New in women's clothing</div> -->
                <ul class="flex justify-start items-stretch flex-wrap pt-4">
                    <li class=" w-1/3" v-for="item in threeList"
                        @click="router.push({ path: '/choosegoods', query: { typeId: item.id } })">
                        <div class="subcategory-content" :style="{ '--image': `url(${item.img})` }"></div>
                        <div class="content-title">{{ item.name }}</div>
                    </li>
                </ul>
            </div>
        </div>
        <!--  <div class="w-[40px] h-[40px] flex items-center justify-center bg-black absolute top-0 text-white right-[-40px]"
            @click="handshow">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                style="width: 24px; height: 24px;">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M19.6014 5.4591L13.0606 11.9991L19.6014 18.5406L18.5407 19.6012L12 13.0598L5.45922 19.6012L4.39856 18.5406L10.9393 11.9991L4.39856 5.4591L5.45922 4.39844L12 10.9385L18.5407 4.39844L19.6014 5.4591Z"
                    fill="white"></path>
            </svg>
        </div> -->
    </van-popup>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { AuthService } from '@/api/home';
import { menuStore } from '@/store/menu';
const store = menuStore()
const router = useRouter()
const active = ref(0)
const props = defineProps(["show"])
const emit = defineEmits()
const secodList = ref([])
const changTab = async () => {
    secodList.value = []
    console.log(store.menuList.length <= 0)
    if (store.menuList.length <= 0) {
        console.log(11111111111)
        await store.getmenuList();
        if (store.menuList[active.value].children) {
            secodList.value = store.menuList[active.value].children
        }
    }else{

        if (store.menuList[active.value].children) {
            secodList.value = store.menuList[active.value].children
        } 
    }

}
const threeList = ref([])
const titleName = ref()
const handlemenu = (index) => {
    if (secodList.value[index].is_children <= 0) {
        return
    }
    threeList.value = []
    isshow.value = false
    if (secodList.value[index].children) {
        titleName.value = secodList.value[index].name
        threeList.value = secodList.value[index].children
    }
}
const isshow = ref(true)
const handshow = () => {
    emit("changeOpenside", false)
}
onMounted(async () => {
    /* await store.getmenuList() */
    changTab()
})
</script>

<style scoped>
.sild-item {
    background-image: var(--acImg);
    border-radius: 50%;
    background-size: cover;
    background-repeat: no-repeat;
    background-color: #fafafa;
    background-position: center;
    border: 1px solid #e5e5e5;
    width: min(11.733vw, 64px);
    height: min(11.733vw, 64px);
    display: inline-block;
}

.sild-title {
    text-align: left;
    padding-left: 12px;
    font-size: min(3.733vw, 20px);
    flex: 1;
    color: #222;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: none;
}

.subcategory-title {
    font-weight: 500;
    font-size: 3.2vw;
    font-size: min(3.2vw, 17.28px);
    color: #222;
    line-height: 8.55vw;
    line-height: min(8.55vw, 46.17px);
    transition: .3s all cubic-bezier(.2, .63, .57, .94);
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

li {
    margin-bottom: min(4vw, 21.6px);
}

.subcategory-content {
    background-image: var(--image);
    margin: 0 auto;
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 50%;
    background-color: #f3f3f3;
    background-position: center;
    width: min(17.6vw, 95px);
    height: min(17.6vw, 95px);
    position: relative;
}

.content-title {
    margin-top: min(.53vw, 3px);
    text-align: center;
    position: relative;
    line-height: 3.567vw;
    line-height: min(3.567vw, 19.26px);
    font-weight: 400;
    text-overflow: -o-ellipsis-lastline;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    word-wrap: break-word;
    max-height: 11.6vw;
    max-height: min(11.6vw, 62.64px);
    width: 100%;
    font-size: 3.2vw;
    font-size: min(3.2vw, 17.28px);
    color: rgba(0, 0, 0, .6);
}

/* .subcategory{
    transform: translateX(0) !important;
} */
</style>