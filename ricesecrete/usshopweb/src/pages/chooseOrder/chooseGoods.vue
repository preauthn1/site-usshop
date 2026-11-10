<template>
    <div class="mt-[44px]" :class="{ 'no-scroll-content': showTop }">
        <Header :showMenu="true" :showBack="true" :showMessage="false" :showSmallSearch="false"
            :searchKeyword="keywordsString" @clearKeyword="clearKeywordAction"></Header>
        <div class="bg-[#f6f6f6]">
            <!-- classicAction(item.id)  -->
            <div class="bg-[#fff]">
                <div class="pt-[14px] pl-[12px] pr-[12px]">
                    <div class="flex w-full overflow-x-auto no-scrollbar">
                        <div class="flex flex-nowrap flex-shrink-0 box-border flex-row">
                            <div v-for="(item, index) in classicData" :key="index"
                                :style="{ marginLeft: index === 0 ? '0px' : '4px' }" @click="classicAction(item)">
                                <div class="content-item content-item__circle">
                                    <van-image width="60" height="60" :src="item.img" :class="{'content-item__active':classSelectedId == item.id}" fit="cover" round />
                                    <div class="content-label__circle content-label">
                                        <span class="text-[#666] break-words text-center textline-2">{{ item.name
                                            }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative mt-[5px]">
                <div class="bg-[#fff] flex h-[36px]">
                    <div class="sort-export__sort">
                        <div class="flex-nowrap w-full overflow-hidden flex relative items-center">
                            <div class="w-auto flex-shrink-0 flex items-center pl-[6px] pr-[6px]" @click="showRecomm">
                                <span class="text-[12] font-bold">{{ recommedStr }}</span>
                                <div v-if="!showTop">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.8891 5.75L8.00008 9.63901L4.11092 5.75L3.40381 6.45711L8 11.0533L12.5962 6.45711L11.8891 5.75Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>
                                <div v-else>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.11092 10.6641L7.99957 6.77478L11.8891 10.6641L12.5962 9.95703L8 5.36084L3.40381 9.95703L4.11092 10.6641Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </div>

                            </div>
                            <div class="justify-center flex w-auto flex-shrink-0 flex-grow" @click="changeMost">
                                <div class="nav-bar__button">
                                    <span class="nav-bar__label"
                                        :style="{ color: mostStr == 'buy' ? '#000' : '#666', fontWeight: mostStr == 'buy' ? '700' : '400' }">{{
                                        $t('最受欢迎') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sort-export__filter">
                        <div class="nav-bar__filter" @click="changePriceRank">
                            <span class="nav-bar__filter-label"
                                :style="{ color: rankStr != '' ? '#000' : '#666', fontWeight: rankStr != '' ? '700' : '400' }">{{
                                $t('价格') }}</span>
                            <div class="nav-bar__filter-icon">
                                <span class="inline-block align-middle leading-none">
                                    <van-icon name="sort" size="12" />
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-show="showTop" class="bg-[#00000066] h-full w-full absolute" @click="showTop = false">
                    <div class="bg-[#fff] w-full">
                        <div class=" block pl-[16px] pr-[16px]">
                            <div v-for="(item, index) in recommendData" :key="index" @click="selectedRecommend(item)"
                                class=" border-teal-200 border-t h-[44px] flex justify-between items-center">
                                <span class="text-[#666] text-[12px]"
                                    :class="{ 'recommend-selected': item === recommedStr }">{{ item }}</span>
                                <van-icon name="success" size="12px" v-if="item === recommedStr" />
                            </div>
                        </div>
                    </div>
                </div>

                <van-list v-model:loading="loading" loading-text="loading" :finished="finished"
                    :finished-text="$t('没有更多了')" @load="onLoadNew">

                    <div class="block pl-[10px] pr-[10px]">
                        <div class="flex justify-between w-full pt-[16px] pb-[70px]">
                            <div class="w-[50%] pr-[5px]">
                                <div v-for="(item, index) in listLeft" class="mb-[10px]"
                                    @click="router.push(`/goodsdetail?id=${item.id}`)">
                                    <img :src="item.img" alt="" class="rounded-[2px] object-cover">
                                    <div class="flex flex-col w-full">
                                        <div class="flex items-center">
                                            <span v-if="item.cashBack"
                                                class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                    item.cashBack }}</span>
                                            <span
                                                class="ml-[3px] text-[12px] whitespace-nowrap overflow-hidden text-ellipsis">{{
                                                    item.title
                                                }}</span>
                                        </div>
                                        <div v-if="item.score > 0">
                                            <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00"
                                                allow-half />
                                        </div>
                                        <div class="flex items-center mt-[5px]">
                                            <span class="text-[12px] font-bold"
                                                :style="{ color: item.cashBack > 0 ? '#fa6338' : '#222' }">{{ $sysCurrency }}{{
                                                    item.min_price }}</span>
                                            <span class="ml-[2px] text-[#767676] text-[8px]" v-if='item.buy > 0'>{{
                                                item.buy
                                                }}+
                                                {{ $t('出售') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-[50%] pl-[5px]">
                                <div v-for="(item, index) in listRight" class="mb-[10px]"
                                    @click="router.push(`/goodsdetail?id=${item.id}`)">
                                    <img :src="item.img" alt="" class="rounded-[2px] object-cover">
                                    <div class="flex flex-col w-full">
                                        <div class="flex items-center">
                                            <span v-if="item.cashBack"
                                                class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                    item.cashBack }}</span>
                                            <span
                                                class="ml-[3px] text-[12px] whitespace-nowrap overflow-hidden text-ellipsis">{{
                                                    item.title
                                                }}</span>
                                        </div>
                                        <div v-if="item.score > 0">
                                            <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00"
                                                allow-half />
                                        </div>
                                        <div class="flex items-center mt-[5px]">
                                            <span class="text-[12px] font-bold"
                                                :style="{ color: item.cashBack > 0 ? '#fa6338' : '#222' }">{{ $sysCurrency }}{{
                                                    item.min_price }}</span>
                                            <span class="ml-[2px] text-[#767676] text-[8px]" v-if='item.buy > 0'>{{
                                                item.buy
                                                }}+
                                                {{ $t('出售') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </van-list>


            </div>

            <!-- <div class="sticky h-[42px]">
                <div class="bg-[#fff] pl-[6px] pr-[6px] flex flex-nowrap w-full items-start overflow-hidden">
                    <div class="w-full h-full ml-auto mr-auto overflow-hidden list-none block">
                        <div class="bg-[#f6f6f6] flex items-center">
                            <span class="text-[12px] text-[#222]">Category</span>
                            <div class="ml-[4px]">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.8891 5.75L8.00008 9.63901L4.11092 5.75L3.40381 6.45711L8 11.0533L12.5962 6.45711L11.8891 5.75Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, reactive, watch, onActivated } from 'vue';
import Header from '@/components/Header.vue';
import { useRouter, useRoute } from "vue-router";
import { AuthService } from "@/api/home";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter();
const route = useRoute();
const listLeft = ref([]);
const listRight = ref([]);
const showTop = ref(false);
const showRecomm = () => {
    showTop.value = !showTop.value;
}
const keywordsString = ref( '');
const dayInfo = route.query.date || -1;
const classType = ref();

const classSelectedId = ref(-1);
const rankStr = ref('');
const mostStr = ref('all');
const recommendData = ref([t('推荐'), t('新品上市')])
const recommedStr = ref(t('推荐'));
const selectedRecommend = (data) => {
    recommedStr.value = data;
    showTop.value = false;
    if (data == t('推荐')) {
        pageInfo.order = "";
    } else {
        pageInfo.order = "new";
    }
    rankStr.value = '';
    mostStr.value = 'all';
    reloadDat();
}

const changePriceRank = () => {
    if (rankStr.value == '') {
        rankStr.value = 'asc';
    } else if (rankStr.value == 'asc') {
        rankStr.value = 'desc';
    } else {
        rankStr.value = 'asc';
    }
    mostStr.value = 'price';
    // recommedStr.value = t('推荐');
    reloadDat();
}
onActivated(()=>{
    if(classType.value == route.query.typeId)return;
    classType.value = route.query.typeId
    pageInfo.type_id = classType.value
    rankStr.value = ''
    mostStr.value = ''
    keywordsString.value = route.query.keywords || ''
    console.log("11111111111")
    uploadClassic()
    reloadDat()
    
})

const changeMost = () => {
    if (mostStr.value == 'all') {
        mostStr.value = 'buy';
    } else {
        mostStr.value = 'all';
    }
    recommedStr.value = t('推荐');
    rankStr.value = '';
    reloadDat();
}
const clearKeywordAction = () => {
    keywordsString.value = '';
    // reloadDat();
}
const classicAction = (item) => {
    if (item.is_children > 0) {
        // uploadClassic();
        router.push({path:'/choosesuborder',query: { typeId: item.id }})
    } else {
        pageInfo.type_id = id;
        classSelectedId.value = id;
        reloadDat();
    }

}
const loading = ref(false);
const finished = ref(false);
const pageInfo = reactive({
    page: 1,
    limit: 30,
    type_id: classType.value,
    classify: 2,
    order: dayInfo.length >= 0 ? 'new' : 'all',
    day: dayInfo,
    keyword: keywordsString,
    rank: rankStr.value,
    tab: mostStr.value,
})

const reloadDat = () => {
    pageInfo.page = 1; // 重置分页
    goodsData.value = []; // 清空当前数据
    pageInfo.rank = rankStr.value;
    pageInfo.tab = mostStr.value;
    finished.value = false
    onLoadNew(); // 重新加载数据
}

const goodsData = ref([]);
const onLoadNew = async () => {
    const res2 = await AuthService.prodect_list({ ...pageInfo })
    if (pageInfo.page == 1) {
        goodsData.value = [];
    }
    goodsData.value = [...goodsData.value, ...res2.dataList]
    const [array0, array1] = splitArrayIntoThree(goodsData.value);
    listLeft.value = array0;
    listRight.value = array1;
    if (goodsData.value.length < res2.total) {
        loading.value = false;
        pageInfo.page++;
    } else {
        finished.value = true;
    }
}

function splitArrayIntoThree(arr) {
    const result = [[], []];
    arr.forEach((item, index) => {
        result[index % 2].push(item);
    });
    return result;
}

const classicData = ref([]);
const uploadClassic = async () => {
    if (classType.value == '') {
        return;
    }
    const res = await AuthService.classification({ type_id: classType.value });
    classicData.value = res.filter(item => item.id != '');
}

// onMounted(() => {
//     uploadClassic();
// });
</script>

<style src="@/assets/css/chooseGoods.css" scoped></style>