<template>
    <div class="myaddress pb-[100px]">
        <Navbar :title="$t('订单详情')" />
        <div class="mt-[44px] bg-[#F6F6F6] pb-[50px]">
            <div class="p-[16px] bg-[#fff8eb]">
                <div class="flex justify-between items-center">
                    <span class="text-[21px] font-bold"> {{ getOrderStatusTitle(orderDetail.status) }} </span>
                    <div class="" v-if="time > 0">
                        <van-count-down :time="time" format="HH : mm : ss" />
                    </div>
                </div>
                <!-- <div class="flex w-fit justify-between break-words overflow-hidden">
                    <span class=" text-base-text-color-gray text-[11px]">Arrives between 02 Mar 2025 ~ 03 Mar
                        2025.</span>
                </div> -->
            </div>
            <div class="flex flex-col mt-[10px] bg-[#fff] pt-[20px] pl-[10px] pr-[10px]" v-if="orderDetail.status == 1">
                <span class="text-[14px] font-bold">{{ $t('支付方式') }}</span>
                <div class="pt-[8px] pb-[8px]">
                    <van-radio-group v-model="checked">
                        <van-radio class="mt-[16px]" :name="index" checked-color="#222"
                            v-for="(item, index) in orderDetail.pay_list" :key="index">
                            <template #default>
                                <div class="flex items-center">
                                    <img class="h-[20px]" :src="item.icon" alt="">
                                    <span class="ml-[10px] text-[14px]">{{ item.name }}</span>
                                </div>
                            </template>
                        </van-radio>
                    </van-radio-group>
                </div>
            </div>
            <div class="mt-[10px] bg-[#fff]">
                <div class="p-[16px]">
                    <div>
                        <div class=" inline-flex items-center">
                            <div class="bg-[#2d68a8] w-[6px] h-[6px] rounded-[3px] mr-[5px]"></div>
                            <span class="text-[12px] text-[#333] font-bold">{{ getOrderStatusTitle(orderDetail.status)
                                }}</span>
                        </div>
                    </div>
                    <div class="flex pt-[16px]">
                        <div class="w-[75px]">
                            <img :src="orderDetail.product_img" />
                        </div>
                        <div class="block pl-[8px] " style="max-width: 80%; overflow: hidden;">
                            <div class="flex items-start justify-between">
                                <span
                                    class=" text-[12px] text-[#666] flex-1 overflow-hidden text-ellipsis line-clamp-3">{{
                                        orderDetail.product_name }}</span>
                                <span class=" text-right text-[12px] font-bold">
                                    {{ $sysCurrency }}{{ orderDetail.spec_json?.price ??
                                        '' }} </span>
                            </div>
                            <div class="mt-[10px] flex justify-between">
                                <span
                                    class="text-[#666] w-[80%] overflow-hidden text-ellipsis line-clamp-3 text-[12px]">{{
                                        orderDetail.spec_json?.spec ?? '' }}</span>
                                <span class="text-[#666] text-[12px]">x{{ orderDetail.spec_json?.number ?? '' }}</span>
                            </div>
                            <!-- <div class="p-[5px] mt-[10px] flex justify-between items-center">
                                <div class="flex-1 overflow-hidden"></div>
                                <div class="pl-[8px] pr-[8px] h-[22px] rounded-[11px] border border-black">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;"
                                        data-v-f6ae4e88="">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M2 3.25V4.75H4.14056L5.34056 14.05H17.0427L18.0042 6.7396H16.4913L15.7271 12.55H6.65944L5.45944 3.25H2Z"
                                            fill="currentColor" data-v-f6ae4e88=""></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.9 4.74719H10.4V6.73669H8.40002V8.23669H10.4V10.2264H11.9V8.23669H13.9V6.73669H11.9V4.74719Z"
                                            fill="currentColor" data-v-f6ae4e88=""></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.32996 16.3C7.32996 15.7478 6.88224 15.3 6.32996 15.3C5.77767 15.3 5.32996 15.7478 5.32996 16.3C5.32996 16.8523 5.77767 17.3 6.32996 17.3C6.88224 17.3 7.32996 16.8523 7.32996 16.3Z"
                                            fill="currentColor" data-v-f6ae4e88=""></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M17.05 16.3C17.05 15.7478 16.6023 15.3 16.05 15.3C15.4978 15.3 15.05 15.7478 15.05 16.3C15.05 16.8523 15.4978 17.3 16.05 17.3C16.6023 17.3 17.05 16.8523 17.05 16.3Z"
                                            fill="currentColor" data-v-f6ae4e88=""></path>
                                    </svg>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-[10px] bg-[#fff]">
                <div class="p-[16px]">
                    <div class="flex justify-between pt-[5px] pb-[10px] border-b border-[#e5e5e5]">
                        <span class="text-[14px] text-[#222]">{{ $t('小计') }}:</span>
                        <span class="text-[14px]">{{ $sysCurrency }}{{ orderDetail.spec_json?.price ?? '' }}</span>
                    </div>
                    <div class="flex justify-between pt-[5px] pb-[5px]">
                        <span class="text-[16px] font-bold text-[#222]">{{ $t('合计') }}:</span>
                        <span class="text-[16px] font-bold">{{ $sysCurrency }}{{ orderDetail.amount }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-[10px] bg-[#fff]">
                <div class="p-[16px]">
                    <div class="flex justify-between items-center">
                        <span class="text-[14px] font-bold">{{ $t('订单信息') }}</span>
                    </div>
                    <div class="mt-[5px]">
                        <span class=" text-base-text-color-gray text-[12px]">{{ $t('订单号2') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[14px]">{{ orderDetail.order_no }}</span>
                        <van-button type="default" size="mini" class="rounded-[0px] font-bold text-[14px]"
                            @click="copyText(orderDetail.order_no)">{{ $t('复制') }}</van-button>
                    </div>

                    <div class="mt-[5px]">
                        <span class=" text-base-text-color-gray text-[12px]"> {{ $t('下单时间') }}:</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[14px]">{{ orderDetail.create_time }}</span>
                    </div>

                    <div class="mt-[5px]">
                        <span class=" text-base-text-color-gray text-[12px]">{{ $t('运送方式') }}:</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[14px]">{{ orderDetail.logistics?.name ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="orderDetail.status == 1"
            class="h-[44px] fixed bottom-0 left-0 right-0 bg-[#fff] border-t border-[#e5e5e5] flex justify-end items-center">
            <van-button type="default" size="small" class="rounded-[0px] font-bold text-[14px]" @click="cancelBtn">{{
                $t('取消')
                }}</van-button>

            <van-button type="primary" size="small" class="rounded-[0px] font-bold text-[14px] ml-[16px] mr-[16px]"
                @click="payofBtn">{{ $t('立即付款') }}</van-button>

        </div>
        <van-popup v-model:show="showShare">
            <div class="bg-[#fff] w-[300px] rounded-[5px] p-[16px]">
                <div class="pb-[16px] flex justify-center items-center">
                    <span class="text-[16px] font-bold">{{ $t('分享链接') }}</span>
                </div>
                <div class="bg-[#F6F6F6] p-[16px] rounded-[5px] flex justify-center">
                    <span class="text-[12px] text-center text-[#666]">{{ shareLink }}</span>
                </div>
                <div class="mt-[16px] p-16px flex justify-between">
                    <van-button type="default" size="small" block class="rounded-[0px] font-bold text-[14px]"
                        @click="showShare = false">{{ $t('取消') }}</van-button>
                    <van-button type="primary" block size="small" class="rounded-[0px] font-bold text-[14px]"
                        @click="copyText(shareLink)">{{ $t('复制') }}</van-button>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Navbar from '@/components/Navbar.vue';
import { useRouter, useRoute } from 'vue-router';
import { UserService } from '@/api/user'
import { useI18n } from 'vue-i18n';
import { showToast } from 'vant';
import { ShopService } from '@/api/shop';
const { t } = useI18n();
const router = useRouter()
const route = useRoute()
const checked = ref(0)
const time = ref(0);
const showShare = ref(false);
const orderId = ref();
const orderDetail = ref({});
const loadDetails = async () => {
    const res = await UserService.goodsOrderDetails({ order_id: orderId.value });
    orderDetail.value = res;
    if (orderDetail.value == 1) {
        time.value = orderDetail.value.pay_cancel_time;
    }
}
const shareLink = ref("");
const copyText = async (str) => {
    const input = document.createElement("input"); //创建input 
    input.setAttribute("value", str);            //把input设置value
    document.body.appendChild(input);            //添加这个dom对象
    input.select();                              //选中该输入框
    document.execCommand("copy");                //复制该文本 
    document.body.removeChild(input);            //移除输入框
    showToast(t("复制成功"))
    showShare.value = false
};

const getOrderStatusTitle = (status) => {
    if (status == 1) {
        return t('等待付款');
    } else if (status == 2) {
        return t('待发货');
    } else if (status == 3) {
        return t('已发货');
    } else if (status == 5 || status == 6) {
        return t('退货');
    } else if (status == 4 || status == 7) {
        return t('完成');
    } else if (status == 0) {
        return t('取消');
    }
}
const cancelBtn = async () => {
    const res = await UserService.orderCancel({ order_id: orderDetail.value.id });
    loadDetails();
}
const payofBtn = async () => {
    if (orderDetail.value.pay_list[checked.value].type == "balance") {
        const res = await ShopService.balance_pay({ "order_id": orderDetail.value.id })
        showToast(t("支付成功"))
        router.replace('/myorders')
        return;
    }
    if (orderDetail.value.pay_list[checked.value].type == "proxy") {
        shareLink.value = window.location.origin + '/#/' + 'shareorder' + `?id=${orderDetail.value.id}&lang=${localStorage.getItem("locale")}`
        showShare.value = true;
        return;
    }
    if (orderDetail.value.pay_list[checked.value].pay_type == 1) {
        const res1 = await ShopService.pay_info({ "order_id": orderDetail.value.id, 'pay_type': 'NOWPayments' });
        if (res1.pay_info?.pay_url) {
            window.open(res1.pay_info?.pay_url);
        }
        return;
    }
    if (orderDetail.value.pay_list[checked.value].pay_type == 2) {
        const res1 = await ShopService.pay_info({ "order_id": orderDetail.value.id, 'pay_type': orderDetail.value.pay_list[checked.value].type });
        let json = {
            pay_amount: res1.pay_amount,
            name: res1.pay_info.name,
            ewm: res1.pay_info.ewm,
            src: res1.pay_info.src,
            number: res1.pay_info.number,
            pay_no: res1.pay_no
        }
        router.push({ path: '/offlinepayment', query: json })
        // showToast(t("支付成功"))
        // router.replace('/myorders')
    }
}
onMounted(() => {
    if (route.query.orderId) {
        orderId.value = route.query.orderId
        loadDetails();
    }
});
</script>

<style lang="less" scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>