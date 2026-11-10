<template>
     <div class="pb-[50px] bg-base-selector-color">
        <!-- <Navbar :title="$t('等待付款')" /> -->
        <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 text-black" :title="$t('等待付款')" fixed></van-nav-bar>
        <div class="h-[44px]"></div>
        <div class="flex flex-col mt-[10px] bg-[#fff] pt-[20px] pl-[10px] pr-[10px]">
            <span class="text-[14px] font-bold">{{ $t('支付方式') }}</span>
            <div class="pt-[8px] pb-[8px]">
                <van-radio-group v-model="checked">
                    <van-radio class="mt-[16px]" :name="index" checked-color="#222" v-for="(item, index) in product_content.pay_list"
                        :key="index">
                        <template #default>
                            <div class="flex items-center">
                                <img class="h-[20px]"
                                    :src="item.icon"
                                    alt="">
                                <span class="ml-[10px] text-[14px]">{{item.name}}</span>
                            </div>
                        </template>
                    </van-radio>
                </van-radio-group>
            </div>
        </div>

        <div class="bg-[#fff] mt-[10px] p-[10px]">
            <span class="text-[14px] font-bold">{{ $t('订单详情') }}</span>
            <div class="pt-[8px] my-4" v-for="item in product_content.spec_list">
                <div class="flex justify-between ">
                    <div class="w-[72px] overflow-hidden flex-shrink-0 mr-[10px]">
                        <img :src="item.img" alt="">
                    </div>
                    <div class="flex flex-col justify-between flex-1 min-w-0 ">
                        <div class="flex items-baseline flex-col">
                            <div class="flex items-center">
                                <span
                                    class=" text-[14px] font-bold textline-2">{{
                                    item.name }}</span>
                            </div>
                            <div class="pt-[6px] whitespace-nowrap text-ellipsis overflow-hidden">
                                <span
                                    class="text-[#666]  text-[12px]">{{item.spec}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#fff] mt-[10px] pt-[10px] pb-[10px] pl-[10px] pr-[10px]">
            <div class="flex justify-between pt-[10px] pb-[10px]">
                <span class="text-[14px]">{{ $t('小计（{num} 项）：', { num: total[1] }) }}</span>
                <span class="text-[14px]"> {{$sysCurrency}}{{ total[0].toFixed(2) }} </span>
            </div>
            <!-- <div class="flex justify-between pt-[10px] pb-[10px]">
                <span class="text-[14px]"> {{$t('运费：')}} </span>
                <div class="flex items-center">
                    <span class="text-[14px] text-[#959595] line-through"> S$21.98 </span>
                    <span class="text-[14px] text-[#198055] ml-[5px]"> FREE </span>
                </div>
            </div> -->
            <!-- <div class="flex flex-col ">
                <div class="flex items-center pt-[10px] pb-[10px]">
                    <span> {{$t('税费包括：')}} </span>
                    <div>
                        <svg style="width:14px;height:14px;" width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.5 6C11.5 2.96243 9.03757 0.5 6 0.5C2.96243 0.5 0.5 2.96243 0.5 6C0.5 9.03757 2.96243 11.5 6 11.5C9.03757 11.5 11.5 9.03757 11.5 6ZM1.5 6C1.5 3.51472 3.51472 1.5 6 1.5C8.48528 1.5 10.5 3.51472 10.5 6C10.5 8.48528 8.48528 10.5 6 10.5C3.51472 10.5 1.5 8.48528 1.5 6ZM6.31996 9.13495L6.3195 8.13455H5.32L5.31996 9.13495H6.31996ZM7.08866 6.48411C7.97998 5.90939 8.27223 4.72992 7.73614 3.80139C7.1833 2.84384 5.95889 2.51576 5.00134 3.0686C4.35718 3.44051 3.99789 4.11633 4.00001 4.81038L5.00019 4.81044C4.99771 4.46165 5.17768 4.12149 5.50134 3.93463C5.9806 3.65793 6.59342 3.82213 6.87012 4.30139C7.14682 4.78065 6.98261 5.39347 6.50336 5.67017L6.39931 5.73382C5.81851 6.10986 5.43906 6.71402 5.34229 7.38475L6.35759 7.3848C6.44462 7.03978 6.673 6.72692 7.00336 6.53619L7.08866 6.48411Z"
                                fill="#959595"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-between pt-[5px] pb-[10px]">
                    <span class="text-[12px] text-[#767677]"> GST </span>
                    <span class="text-[12px] text-[#767677]"> S$1.98 </span>
                </div>
            </div> -->
            <div class="flex justify-between pt-[10px] pb-[10px] items-end">
                <span class="text-[14px] font-semibold flex-shrink-0 max-w-[128px]">{{ $t('订单总额：') }}</span>
                <div class="flex flex-col items-end">
                    <!--  <span class="text-[14px] text-[#fa6338]"> {{$t('节省 {money} ',{money:'$12.00'})}} </span> -->
                    <span class="text-[24px] font-bold text-[#fa6338] mt-[8px]"> {{$sysCurrency}}{{ total[0].toFixed(2) }} </span>
                </div>
            </div>
        </div>
        <div class="mt-[10px] bg-[#fff] p-[10px]">
            <span class="text-[14px] text-[#000] font-medium">{{ $t('安全、可持续地购物') }}</span>
            <div class="flex justify-between items-center pt-[10px] pb-[10px]">
                <div class="flex flex-col items-center justify-between">
                    <img class="w-[24px] h-[24px]"
                        src="https://img.ltwebstatic.com/images3_acp/2023/03/01/1677662377aicndh476pay.png" alt="">
                    <span class="text-[11px] font-normal text-[#959595] text-center"> {{ $t('确保您的付款安全') }}</span>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <img class="w-[24px] h-[24px]"
                        src="https://img.ltwebstatic.com/images3_acp/2023/03/01/1677662377aicndh476nmidpri.png" alt="">
                    <span class="text-[11px] font-normal text-[#959595] text-center">{{ $t('安全与隐私') }}</span>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <img class="w-[24px] h-[24px]"
                        src="https://img.ltwebstatic.com/images3_ccc/2024/08/12/a1/1723432466cda8b1e5cb1f1322ca72094afa74d023.png"
                        alt="">
                    <span class="text-[11px] font-normal text-[#959595] text-center">{{ $t('安全运输保证') }}</span>
                </div>
                <div class="flex flex-col items-center justify-center">
                    <img class="w-[24px] h-[24px]"
                        src="https://img.ltwebstatic.com/images3_ccc/2024/12/13/26/17340725817ca3930912aa2e9ae2bc31e8e738fdcc.png"
                        alt="">
                    <span class="text-[11px] font-normal text-[#959595] text-center">{{ $t('客户支持') }}</span>
                </div>
            </div>
        </div>
        <div
            class="border-t border-borderColor max-w-[425px] my-0 mx-auto bg-[#fff] p-[10px] shadow-sm fixed bottom-0 left-0 right-0">
            <div class="flex justify-center items-center py-4 bg-black text-white font-bold" @click="saveAction">
                {{ $t('立即付款') }}
            </div>

        </div>

        <van-popup v-model:show="showShare">
            <div class="bg-[#fff] w-[300px] rounded-[5px] p-[16px]">
                <div class="pb-[16px] flex justify-center items-center">
                    <span class="text-[16px] font-bold">{{ $t('分享链接') }}</span>
                </div>
                <div class="bg-[#F6F6F6] p-[16px] rounded-[5px] flex justify-center">
                    <span class="text-[12px] text-center text-[#666]">{{shareLink}}</span>
                </div>
                <div class="mt-[16px] p-16px flex justify-between">
                    <van-button type="default" size="small" block class="rounded-[0px] font-bold text-[14px]"
                        @click="showShare = false">{{ $t('取消') }}</van-button>
                    <van-button type="primary" block size="small" class="rounded-[0px] font-bold text-[14px]"
                        @click="copyText">{{ $t('复制') }}</van-button>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onMounted, ref, computed } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { ShopService } from '@/api/shop';
const route = useRoute()
const router = useRouter()
const checked = ref(0);
const product_content = ref({})
const get_orders = async () => {
    const res = await ShopService.order_share_details({ "order_id": route.query.id })
    product_content.value = res
}
const total = computed(() => {
    if (!product_content.value || !Array.isArray(product_content.value.spec_list)) return [0, 0];
    // 如果 spec_list 存在且是数组，则计算
    return [
        product_content.value.spec_list.reduce((sum, item) => sum + (item.price || 0) * (item.number || 0), 0),
        product_content.value.spec_list.reduce((sum, item) => sum + (item.number || 0), 0)
    ];

})
//下单
const saveAction = async () => {
    if(product_content.value.pay_list[checked.value].type == "NOWPayments"){
        const res1 = await ShopService.pay_info({"order_id":route.query.id,'pay_type':'NOWPayments'});
        if (res1.pay_info?.pay_url) {
            window.location.href = res1.pay_info?.pay_url
        }
    }
}
onMounted(() => {
    if(route.query.id){
        
        get_orders()
    }
    else{
        router.replace("/")
    }
})
</script>

<style lang="less" scoped>
:deep(.van-nav-bar__title){
    color: black;
}

</style>