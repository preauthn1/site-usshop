<template>
    <div class="pb-[50px] bg-base-selector-color">
        <!-- <Navbar :title="$t('等待付款')" /> -->
        <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 text-black" :title="$t('线下支付')"
            fixed></van-nav-bar>
        <div class="h-[44px]"></div>
        <div class="flex flex-col mt-[10px] bg-[#fff] pt-[20px] pl-[10px] pr-[10px]">
            <span class="text-[14px] font-bold">{{ $t('支付方式') }}</span>
            <div class="pt-[8px] pb-[8px]">
                <img class="w-[24px] h-[24px]" :src="pay_info.src" alt="">
                <span>{{ pay_info.name }}</span>
            </div>
        </div>
        <div class="bg-[#fff] mt-[10px] pt-[10px] pb-[10px] pl-[10px] pr-[10px]">
            <div class="flex justify-between pt-[10px] pb-[10px] items-end">
                <span class="text-[14px] font-semibold flex-shrink-0 max-w-[128px]">{{ $t('订单总额：') }}</span>
                <div class="flex flex-col items-end">
                    <!--  <span class="text-[14px] text-[#fa6338]"> {{$t('节省 {money} ',{money:'$12.00'})}} </span> -->
                    <span class="text-[24px] font-bold text-[#fa6338] mt-[8px]"> {{ $sysCurrency }}{{
                        pay_info?.pay_amount }} </span>
                </div>
            </div>
        </div>
        <div class="bg-[#fff] mt-[10px] p-[10px]">
            <span class="text-[14px] font-bold">{{ $t('支付渠道') }}</span>
            <div class="pt-[8px] my-4">
                <img :src="pay_info.ewm" alt="">
                <div>{{ pay_info.number }}</div>
            </div>
        </div>


        <div class="mt-[10px] bg-[#fff] p-[10px]">
            <!-- 上传图片 -->
            <van-uploader :after-read="afterRead" v-model="fileList" />
        </div>
        <div
            class="border-t border-borderColor max-w-[425px] my-0 mx-auto bg-[#fff] p-[10px] shadow-sm fixed bottom-0 left-0 right-0">
            <div class="flex justify-center items-center py-4 bg-black text-white font-bold" @click="saveAction">
                {{ $t('确认付款') }}
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

import { useRouter, useRoute } from 'vue-router';
const route = useRoute()
const router = useRouter()
import { ShopService } from '@/api/shop';
import { AuthService } from '@/api/home';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const fileList = ref([]);
const pay_info = ref({})

const afterRead = async (param) => {
    // 此时可以自行将文件上传至服务器
    console.log(param)
    let param_file = new FormData();
    param_file.append("file", param.file);
    param_file.append("folder", 'order');
    const res = await AuthService.uploadfile(param_file);
    // console.log(res)
    if(res.length > 0){
        fileList.value = res;
        pay_info.value.pay_img = res[0].url
    }
};
//下单
const saveAction = async () => {
    const res = await ShopService.update_pay_img({ "pay_no": pay_info.value.pay_no, 'pay_img': pay_info.value.pay_img })
    if(res){
        showToast(t("支付成功"))
    }else{
        showToast(t("支付失败"))
    }
    router.replace('/myorders')
}
onMounted(() => {
    if (route.query.pay_no) {
        let pay_temp = {};
        pay_temp.pay_amount = route.query.pay_amount
        pay_temp.name = route.query.name
        pay_temp.ewm = route.query.ewm
        pay_temp.src = route.query.src
        pay_temp.number = route.query.number
        pay_temp.pay_no = route.query.pay_no
        pay_info.value = pay_temp
        // console.log(pay_temp)
    } else {
        router.replace("/")
    }
})
</script>

<style lang="less" scoped>
:deep(.van-nav-bar__title) {
    color: black;
}
</style>