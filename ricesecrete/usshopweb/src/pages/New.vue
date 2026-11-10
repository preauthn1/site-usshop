<template>
    <div class="mt-[44px]">
        <Header :showMenu="false" :showBack="true"></Header>
        <van-swipe>
            <van-swipe-item>
                <img src="https://img.ltwebstatic.com/images3_ps1/2024/09/04/83/1725418696f43bc113b64a9677a196c4ce8eee73e5_thumbnail_720x.webp"
                    alt="">
            </van-swipe-item>
        </van-swipe>
        <div class="w-full h-[250px]">
            <van-tabs class="" v-model:active="active" background="#222" color="#fff" title-inactive-color="#fff"
                line-width="0px" @click-tab="onClickDayTab">
                <van-tab v-for="(item, index) in dayListData">
                    <template #title>
                        <div class="w-full pt-[0.2rem] bg-[#222]">
                            <div class="flex flex-col items-center justify-center pt-[1.2rem] px-[1.625rem] pb-4"
                                :class="{ 'tabs-selected-content': active == index }">
                                <span class=" text-[12px] leading-[14px] mb-2">{{ item }}</span>
                                <span class="text-[12px] font-normal leading-[14px]"
                                    :style="{ color: active == index ? '#767676' : '#fff' }">New Arrivals</span>
                            </div>
                        </div>
                    </template>
                    <div class="">
                        <div class="grid grid-cols-3 gap-[10px] ml-[10px] mr-[10px]">
                            <div class="flex flex-col pt-[10px] rounded-t-[4px] " v-for="(item, index) in dayNewData" :key="index">
                                <div class="relative">
                                    <div class="rounded-t-[4px] h-[145px]">
                                        <van-image height="100%" width="100%" fit="cover" position="center" :src="item.img">
                                            <template slot="loading">
                                                <van-loading type="spinner" size="20" />
                                            </template>
                                        </van-image>
                                    </div>
                                    <!-- <div class="absolute bottom-0 right-0 pb-[4px] pr-[4px]">
                                        <div
                                            class="bg-[#FFFFFFAA] w-[24px] h-[24px] flex justify-center items-center rounded-[50%]">
                                            <van-icon name="shopping-cart-o" size="20px" />
                                        </div>
                                    </div> -->
                                </div>
                                <div>
                                    <span class="text-[12px] font-bold">{{ $sysCurrency }}{{ item.min_price }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pt-[10px] pb-[10px] flex justify-center items-center"
                            @click="router.push({ path: '/choosegoods', query: { date: newPageInfo.day } })">
                            <span class="text-base-text-color-gray text-[14px] mr-[5px]">{{ $t('查看全部') }}</span>
                            <svg style="width:16px;height:16px;" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.76257 11.7123L6.82323 12.773L11.5962 8.00002L6.82323 3.22705L5.76257 4.28771L9.47501 8.00002L5.76257 11.7123Z"
                                    fill="#767676"></path>
                            </svg>
                        </div>
                    </div>
                </van-tab>
            </van-tabs>
        </div>


        <div class="mt-[30px] mb-[70px]">
            <van-tabs v-model="activeTab" class="bg-white" type="" background="#000" offset-top="4.4rem" color="#fff"
                title-inactive-color="#fff" line-width="40px" scrollspy sticky>
                <van-tab v-for="(item, index) in goodsData">
                    <template #title>
                        <div
                            class="w-full h-full flex flex-col items-center  justify-center pt-[10px] pb-[10px] pl-[5px] pr-[5px]">
                            <span class="text-[12px]" :style="{ color: active == index ? '#fff' : '#fff' }">{{ item.name
                                }}</span>
                        </div>
                    </template>
                    <template #default>
                        <div v-if="index < goodsData.length - 1" class="ml-[10px] mr-[10px]">
                            <div class="flex justify-between pt-[5px] pb-[5px]">
                                <span class="text-[14px] font-bold">{{ item.name }}</span>
                                <div class="flex items-center"
                                    @click="router.push({ path: '/choosegoods', query: { typeId: item.id } })">
                                    <span class="text-[12px] mr-[5px]">{{ $t('查看全部') }}</span>
                                    <svg style="width:12px;height:12px;" width="12" height="12" viewBox="0 0 12 12"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.96973 2.99474L5.03039 1.93408L9.09625 5.99995L5.03039 10.0658L3.96973 9.00515L6.97506 5.99995L3.96973 2.99474Z"
                                            fill="#000000"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <!-- <div class="w-[82px] h-[109px] overflow-hidden rounded-[3px]">
                                    <img :src="item.img" class="object-cover" alt="">
                                </div> -->
                                <div class="ml-[5px] flex-1 h-[110px] overflow-x-auto flex rounded-[2px] no-scrollbar">
                                    <!-- <img v-for="(item1, index) in item.goods" :src="item1.img" alt=""
                                        class="mr-[5px] h-[110px] w-[83px] rounded-[2px] object-cover"
                                        @click="router.push(`/goodsdetail?id=${item1.id}`)"> -->
                                    <div class="w=[83px] h-[110px] rounded-[2px]" v-for="(item1, index) in item.goods">
                                        <van-image class="mr-[5px] rounded-[2px]" :src="item1.img" width="83px" height="110px"
                                            fit="cover" position="center" @click="router.push(`/goodsdetail?id=${item1.id}`)">
                                            <template slot="loading">
                                                <van-loading type="spinner" size="20" />
                                            </template>
                                        </van-image>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div v-else class="pt-[16px]">
                            <div class="flex justify-center items-center">
                                <img src="https://img.shein.com/images3/2024/02/19/69/1708341848097e305a3fcf3e4fbffad8e249e658fc_thumbnail_720x.webp"
                                    alt="">
                            </div>
                            <van-list v-model:loading="loading" loading-text="loading" :finished="finished"
                                :finished-text="$t('没有更多了')" @load="onLoadMore">
                                <div class=" grid grid-cols-3 gap-[5px] p-[8px]">
                                    <div v-for="item in viewMoreData" class="p-[2px] rounded-[6px] bg-[#fff]"
                                        @click="router.push(`/goodsdetail?id=${item.id}`)">
                                        <div class="relative">
                                            <div class="rounded-t-[4px] overflow-hidden">
                                                <van-image height="150px" width="100%" fit="cover" position="center"
                                                    :src="item.img">
                                                    <template slot="loading">
                                                        <van-loading type="spinner" size="20" />
                                                    </template>
                                                </van-image>
                                            </div>
                                            <div class=" absolute top-0 left-0">
                                                <img src="https://img.ltwebstatic.com/images3_ps1/2025/02/20/35/174002252574166e9e2155e6b508b856a3867e223f.png"
                                                    alt="">
                                            </div>
                                        </div>
                                        <div class="p-[4px]">
                                            <div class="flex items-center">
                                                <div
                                                    class=" inline-block text-[12px] text-[#000] whitespace-nowrap overflow-hidden text-ellipsis w-full h-[14px] leading-[14px]">
                                                    {{ item.title }}
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-center w-full overflow-hidden">
                                                <div class="flex items-baseline gap-x-[4px] flex-wrap">
                                                    <div
                                                        class=" text-left flex align-baseline flex-wrap items-end leading-[16px]">
                                                        <span
                                                            class="text-[#222] font-bold text-[11px] leading-[11px]">{{
                                                                $sysCurrency }}</span>
                                                        <span
                                                            class="text-[#222] font-bold text-[14px] leading-[14px]">{{
                                                                item.min_price }}</span>
                                                        <span
                                                            class="text-[#222] font-bold text-[11px] leading-[11px]"></span>
                                                    </div>
                                                </div>
                                                <div class="flex self-stretch">
                                                    <div class="h-[16px] align-middle" v-if="item.buy > 0">
                                                        <span class="text-[10px] text-[#666]">{{ $t('出售') }} {{ item.buy
                                                            }}+</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </van-list>

                            <van-back-top offset="500" bottom="150" />
                        </div>
                    </template>
                </van-tab>
            </van-tabs>
        </div>
        <Cartdetail :show="opendetail" @closedetail="closedetail" :content="detaildata"></Cartdetail>
    </div>
</template>
<script setup>
import { onMounted, ref, reactive } from 'vue';
import Cartdetail from '@/components/cartdetail.vue';
import Header from '../components/Header.vue';
import { AuthService } from "@/api/home";
import { menuStore } from '@/store/menu';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter();
const store = menuStore();
const tablist = ref(['All', 'Women', 'Curve', ' kids ', ' men ', ' home ', ' beauty ', ' View More'])
const goodsData = ref([]);
const active = ref(0);
const activeTab = ref(0);

const dayPageInfo = ref([]);
function getPreviousWeekDates() {
    const dates = [];
    const today = new Date();
    for (let i = 0; i < 7; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() - i);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        dayPageInfo.value.push(`${year}-${month}-${day}`);
        if (i == 0) {
            dates.push('Today');
        } else if (i == 1) {
            dates.push('Yesterday')
        } else {
            dates.push(`${month}/${day}`);
        }
    }
    return dates;
}
const dayListData = ref(getPreviousWeekDates());
const newPageInfo = reactive({
    page: 1,
    limit: 3,
    type_id: "",
    classify: 2,
    order: 'new',
    day: 0,
})

const onClickDayTab = (name, title) => {
    console.log(name.name, dayPageInfo.value[name.name]);
    newPageInfo.day = name.name;
    onLoadNew();
}
const dayNewData = ref([]);
const onLoadNew = async () => {
    const res2 = await AuthService.prodect_list({ ...newPageInfo })
    dayNewData.value = res2.dataList;
}
const indexdata = async () => {
    const res1 = await store.getmenuList();
    pageInfo.type_id = res1[0].id
    tablist.value = res1
}

const viewMorePageInfo = reactive({
    page: 1,
    limit: 15,
    type_id: "",
    classify: 2,
    order: 'all',
})
const loading = ref(false);
const finished = ref(false);
const viewMoreData = ref([]);
const onLoadMore = async () => {
    const res2 = await AuthService.prodect_list({ ...viewMorePageInfo })
    if (viewMorePageInfo.page == 1) {
        viewMoreData.value = [];
    }
    viewMoreData.value = [...viewMoreData.value, ...res2.dataList];
    if (viewMoreData.value.length < res2.total) {
        loading.value = false;
        viewMorePageInfo.page++;
    } else {
        finished.value = true;
    }
}

const loadFenLei = async () => {

    const res2 = await AuthService.goodsTypeWithGoods();
    console.log(res2);
    goodsData.value = res2;
    const item = [{
        name: t('查看更多')
    }]
    goodsData.value.push(...item);
    console.log(goodsData.value)
}
loadFenLei();

onMounted(() => {
    onLoadNew();
    onLoadMore();
});

const opendetail = ref(false)
const closedetail = async (num) => {
    if (num) {

    }
    opendetail.value = false
}
//处理详情数据
const detaildata = ref({})
const handledata = (item) => {
    detaildata.value = item
    opendetail.value = true
}

</script>
<style scoped>
.tabs-selected-content {
    background-color: white;
}

:deep(.van-tabs--line .van-tabs__wrap) {
    height: fit-content;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

:deep(.van-tab--grow) {
    flex: 1 0 auto;
    padding: 0 0;
}

:deep(.van-tabs__nav--line.van-tabs__nav--complete) {
    padding-right: 0px !important;
    padding-left: 0px !important;
    padding-bottom: 0px;
}

:deep(.van-tab--active) {
    color: var(--van-tab-active-text-color);
    font-weight: var(--van-font-bold);
    background-color: #adaaaa;
}
</style>