<template>
    <div class="search">
        <div class="h-[44px] flex justify-between items-center py-[6px] px-[6px] relative">
            <van-icon name="arrow-left" @click="router.go(-1)" size='24' />

            <van-search v-model="keywords" left-icon="#" shape="round" class="search-input"
                :placeholder="$t('请输入搜索关键词')"></van-search>
            <div class=" absolute right-[21px] flex justify-center items-center h-[32px] w-[48px] bg-black rounded-full"
                @click="handlesearch">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                    style="width: 20px; height: 20px;">
                    <circle cx="9.5" cy="9" r="6.25" stroke="#FFF" stroke-width="1.5"></circle>
                    <path d="M13.6268 13.0898L17.463 17" stroke="#FFF" stroke-width="1.5"></path>
                </svg>
            </div>
        </div>
        <div class="p-[12px]">
            <div v-if="keywordsList?.keyword && keywordsList?.keyword.length > 0">
                <div class="flex justify-between pt-[10px] pb-[10px]">
                    <div class="text-[16px] font-bold overflow-hidden">
                        {{$t('最近搜索')}}
                    </div>
                    <div class="" @click="clear">
                        <svg t="1739191009770" class="icon" viewBox="0 0 1024 1024" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" p-id="2150" width="16" height="16">
                            <path
                                d="M574.577778 739.555556V381.155556c0-39.822222 62.577778-39.822222 62.577778 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577778 0z m-193.422222 0V381.155556c0-39.822222 62.577778-39.822222 62.577777 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577777 0z m551.822222-517.688889h-164.977778V159.288889c0-51.2-45.511111-96.711111-96.711111-96.711111H347.022222c-28.444444 0-51.2 11.377778-68.266666 28.444444-17.066667 17.066667-28.444444 45.511111-28.444445 68.266667v62.577778H85.333333C45.511111 221.866667 45.511111 284.444444 85.333333 284.444444h847.644445c45.511111 0 45.511111-62.577778 0-62.577777zM318.577778 153.6c0-17.066667 17.066667-34.133333 34.133333-34.133333h324.266667c17.066667 0 34.133333 11.377778 34.133333 34.133333v62.577778H318.577778V153.6zM739.555556 967.111111H284.444444c-28.444444 0-51.2-11.377778-68.266666-28.444444-17.066667-17.066667-28.444444-45.511111-28.444445-68.266667V381.155556c0-17.066667 17.066667-34.133333 34.133334-34.133334s34.133333 11.377778 34.133333 34.133334v489.244444c0 17.066667 17.066667 34.133333 34.133333 34.133333h455.111111c17.066667 0 34.133333-17.066667 34.133334-34.133333V386.844444c0-39.822222 62.577778-39.822222 62.577778 0v489.244445c-5.688889 45.511111-45.511111 91.022222-102.4 91.022222z"
                                fill="#999999" p-id="2151"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4 items-center  pb-[20px]">
                    <div class="h-[20px] max-w-full relative bg-[#f6f6f6]  px-4 leading-3 text-[#666] text-[13px] flex justify-center items-center p-7"
                        v-for="item in keywordsList?.keyword" @click="clickRecord(item)">
                        <span class="text-ellipsis overflow-hidden whitespace-nowrap">{{ item }}</span>
                        <span class=" absolute top-1 right-1 z-10" @click.stop="delateitem(item)">
                            <svg t="1740206678842" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" p-id="4008" width="12" height="12">
                                <path
                                    d="M853.8 215.4l-45.2-45.2L512 466.7 215.4 170.2l-45.2 45.2L466.7 512 170.2 808.6l45.2 45.2L512 557.3l296.6 296.5 45.2-45.2L557.3 512z"
                                    fill="#CCCCCC" p-id="4009"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex justify-between pt-[10px] pb-[10px]">
                    <div class="text-[16px] font-bold">
                        {{$t('推荐搜索')}}
                    </div>
                    <!-- <div class="">
                    <svg t="1739191009770" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2150" width="16" height="16">
                        <path
                            d="M574.577778 739.555556V381.155556c0-39.822222 62.577778-39.822222 62.577778 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577778 0z m-193.422222 0V381.155556c0-39.822222 62.577778-39.822222 62.577777 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577777 0z m551.822222-517.688889h-164.977778V159.288889c0-51.2-45.511111-96.711111-96.711111-96.711111H347.022222c-28.444444 0-51.2 11.377778-68.266666 28.444444-17.066667 17.066667-28.444444 45.511111-28.444445 68.266667v62.577778H85.333333C45.511111 221.866667 45.511111 284.444444 85.333333 284.444444h847.644445c45.511111 0 45.511111-62.577778 0-62.577777zM318.577778 153.6c0-17.066667 17.066667-34.133333 34.133333-34.133333h324.266667c17.066667 0 34.133333 11.377778 34.133333 34.133333v62.577778H318.577778V153.6zM739.555556 967.111111H284.444444c-28.444444 0-51.2-11.377778-68.266666-28.444444-17.066667-17.066667-28.444444-45.511111-28.444445-68.266667V381.155556c0-17.066667 17.066667-34.133333 34.133334-34.133334s34.133333 11.377778 34.133333 34.133334v489.244444c0 17.066667 17.066667 34.133333 34.133333 34.133333h455.111111c17.066667 0 34.133333-17.066667 34.133334-34.133333V386.844444c0-39.822222 62.577778-39.822222 62.577778 0v489.244445c-5.688889 45.511111-45.511111 91.022222-102.4 91.022222z"
                            fill="#999999" p-id="2151"></path>
                    </svg>
                </div> -->
                </div>
                <div class="flex flex-wrap gap-4 items-center ">
                    <div class="h-[20px] bg-[#f6f6f6] text-[10px] flex justify-center items-center px-2"
                        v-for="item in keywordsList.keyword_sys" @click="clickRecord(item)">
                        <div>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;">
                                <path
                                    d="M8.50755 2.81232L4.13128 6.24161C3.79943 6.50166 3.92994 7.03166 4.3446 7.10788L6.29101 7.46567L6.08998 8.22338C5.46177 10.5913 4.05995 12.5477 2.22815 13.8932C5.29962 13.6008 8.01539 11.4762 8.92668 8.35952L8.97116 8.19159L10.747 8.82315C11.1451 8.96471 11.5234 8.56912 11.3641 8.1778L9.26246 3.01302C9.13995 2.71197 8.76337 2.61185 8.50755 2.81232Z"
                                    fill="#9462FF"></path>
                            </svg>
                        </div>
                        <span class="text-[#666] text-[12px]">{{item}}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onActivated, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { SearchService } from '@/api/search';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter();
const keywords = ref('')
const keywordsList = ref({})
const getlist = async () => {
    const res = await SearchService.searchlist();
    keywordsList.value = res
}
const clickRecord = (str) => {
    router.push({ path: '/choosegoods', query: { keywords: str } });
}
const delateitem = async (event) =>{
    const res = await SearchService.del_keyword({type:2,"keyword":event});
    getlist()
    
}
const clear = async() => {
    if(!keywordsList.value.keyword)return;
    const res = await SearchService.clear_keyword({type:2})
    keywordsList.value.keyword = []
}
onActivated(()=>{
    getlist();
})

const handlesearch = () => {
    if (keywords.value.length <= 0) {
        showToast(t('请输入商品关键字'));
        return;
    }
    router.replace({ path: '/choosegoods', query: { keywords: keywords.value } });
}
</script>

<style scoped>
.search-input {
    width: 100%;
    position: relative;
}

:deep(.van-search) {
    display: flex;
    align-items: center;
    box-sizing: border-box;
    padding: var(--van-search-padding);
    background: #00000000;
}

:deep(.van-search__content) {
    display: flex;
    flex: 1;
    padding-left: var(--van-padding-sm);
    background: var(--van-search-content-background);
    /* border-radius: var(--van-radius-sm); */
    border: 1.5px solid #000;
}

span {
    line-height: 1.5;
}
</style>