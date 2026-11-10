<template>
    <div class="login">
        <Navbar :title="$t('钱包')"></Navbar>
        <div class="mt-[44px] bg-[#F6F6F6] ">
            <div class="p-[16px]">
                <div class="bg-[#fff] flex flex-col items-center">
                    <div class="pt-[24px]">
                        <span class="text-[24px] font-bold">{{ userStore.userInfo.balance }}</span>
                    </div>
                    <div class="pt-[10px]">
                        <span class="text-[14px]">{{ $t('钱包余额') }}</span>
                    </div>
                    <div class="pt-[20px] pb-[20px] flex justify-between">
                        <div class="w-[150px] pr-[8px]">
                            <van-button type="default" block size="small" class="rounded-[0px] font-bold text-[14px]"
                                @click="router.push({ path: '/recharge', query: { type: 1 } })">{{ $t('充值')
                                }}</van-button>
                        </div>
                        <div class="w-[150px] pl-[8px]">
                            <van-button type="primary" block size="small" class="rounded-[0px] font-bold text-[14px]"
                                @click="router.push({ path: '/recharge', query: { type: 2 } })">{{ $t('提现')
                                }}</van-button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="bg-[#fff] pb-[50px]">
                <van-list v-model:loading="loading" :finished="finished" loading-text="loading"
                    :finished-text="$t('没有更多了')" @load="onLoad">
                    <div class="pt-[10px] pl-[16px] pr-[16px] bg-[#fff]" v-for="item in money_list">
                        <div
                            class="flex justify-between items-center pl-[16px] pr-[16px] pt-[10px] pb-[10px] bg-[#F6F6F6] rounded-[4px]">
                            <div class="flex flex-col justify-between items-start">
                                <span class="text-[14px] font-bold">{{item.title}}</span>
                                <span class="text-[12px] text-[#666]">{{item.create_time}}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-[#fa6338] text-[20px] font-bold">{{item.amount}}</span>
                            </div>
                        </div>
                    </div>
                </van-list>
            </div>
            <!--   <van-tabs v-model="activeTab" background="#000" offset-top="44px" color="#fff" title-inactive-color="#fff"
                line-width="0px" sticky>
                <van-tab>
                    <template #title>
                        <div
                            class="w-full h-full flex flex-col items-center justify-center pt-[10px] pb-[10px] pl-[5px] pr-[5px]">
                            <span class="text-[12px]"
                                :style="{ color: active == index ? '#fff' : '#fff' }">{{ $t('充值记录') }}</span>
                        </div>
                    </template>
<div class="bg-[#fff]">
    <div class="pt-[10px] pl-[16px] pr-[16px] bg-[#fff]" v-for="item in 20">
        <div
            class="flex justify-between items-center pl-[16px] pr-[16px] pt-[10px] pb-[10px] bg-[#F6F6F6] rounded-[4px]">
            <div class="flex flex-col justify-between items-start">
                <span class="text-[14px] font-bold">银行卡充值</span>
                <span class="text-[12px] text-[#666]">2025.02.11 12:43</span>
            </div>
            <div class="flex items-center">
                <span class="text-[#fa6338] text-[20px] font-bold">+ 59.03</span>
            </div>
        </div>
    </div>
</div>

</van-tab>
<van-tab>
    <template #title>
                        <div
                            class="w-full h-full flex flex-col items-center justify-center pt-[10px] pb-[10px] pl-[5px] pr-[5px]">
                            <span class="text-[12px]"
                                :style="{ color: active == index ? '#fff' : '#fff' }">{{ $t('提现记录') }}</span>
                        </div>
                    </template>
    <div class="bg-[#fff]">
        <div class="pt-[10px] pl-[16px] pr-[16px] bg-[#fff]" v-for="item in 20">
            <div
                class="flex justify-between items-center pl-[16px] pr-[16px] pt-[10px] pb-[10px] bg-[#F6F6F6] rounded-[4px]">
                <div class="flex flex-col justify-between items-start">
                    <span class="text-[14px] font-bold">银行卡提现</span>
                    <span class="text-[12px] text-[#666]">2025.01.22 13:13</span>
                </div>
                <div class="flex items-center">
                    <span class="text-[#fa6338] text-[20px] font-bold">- 159.03</span>
                </div>
            </div>
        </div>
    </div>
</van-tab>
</van-tabs> -->
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onActivated, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/store/userStore';
import { useI18n } from 'vue-i18n';
import { UserService } from '@/api/user';
const { t } = useI18n();
const userStore = useUserStore();
const router = useRouter()
const loading = ref(false);
const finished = ref(false);
// const tablist = ref([t('充值记录'), t('提现记录')])
const money_list = ref([])
const pageInfo = reactive({
    page: 1,
    limit: 10,
})
const onLoad = async () => {
    const res = await UserService.money_log({ ...pageInfo })
    money_list.value = [...money_list.value, ...res.data]
    if (money_list.value.length < res.total) {
        pageInfo.page++
        loading.value = false;
    }else{
        finished.value = true
    }
}

onActivated(()=>{
    userStore.getUserInfo()
})

</script>

<style scoped>
:deep(.van-tab--grow) {
    flex: 1 0 auto;
    padding: 0 0;
}

:deep(.van-tabs__nav--line.van-tabs__nav--complete) {
    padding-right: 0px !important;
    padding-left: 0px !important;
}

:deep(.van-tab--active) {
    color: var(--van-tab-active-text-color);
    font-weight: var(--van-font-bold);
    background-color: #adaaaa;
}
</style>