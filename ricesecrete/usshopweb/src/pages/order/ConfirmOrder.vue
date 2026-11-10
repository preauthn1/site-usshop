<template>
    <div class="pb-[50px] bg-base-selector-color">
        <Navbar :title="$t('确认订单')" />
        <div class="h-[44px]"></div>
        <div class="checkout-address-home__layout">
            <div class="flex" @click="router.push('/myaddress?type=1')">
                <div class="mr-[8px]">
                    <svg style="width:16px;height:16px;" width="15" height="15" viewBox="0 0 15 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.50001 14.0057C7.58767 13.9413 7.67708 13.8742 7.76782 13.8046C8.47256 13.2641 9.13982 12.667 9.74208 12.0303C11.5544 10.1144 12.5 8.15489 12.5 6.22498C12.5 3.47737 10.2614 1.25 7.50001 1.25C4.73857 1.25 2.5 3.47737 2.5 6.22498C2.5 8.15489 3.44564 10.1144 5.25793 12.0303C5.86019 12.667 6.52745 13.2641 7.23219 13.8046C7.32294 13.8742 7.41235 13.9413 7.50001 14.0057ZM5.50513 6.24998C5.50513 7.35171 6.39825 8.24484 7.49998 8.24484C8.60171 8.24484 9.49484 7.35171 9.49484 6.24998C9.49484 5.14825 8.60171 4.25513 7.49998 4.25513C6.39825 4.25513 5.50513 5.14825 5.50513 6.24998Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col" v-if="cartstore.address">
                            <div class="flex">
                                <span class="text-[14px] font-semibold mr-[8px]">{{ cartstore.address?.name }}</span>
                                <span class="text-[14px] text-base-text-color-gray">{{ cartstore.address?.mobile
                                    }}</span>
                            </div>
                            <div>
                                <span class="text-[14px]">{{ cartstore.address?.province }}</span>
                            </div>
                            <div>
                                <span class="text-[14px]">{{ cartstore.address?.address }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col" v-else>
                            <div class="flex">
                                <span class="text-[14px] font-semibold mr-[8px]">{{ product_content?.address?.name
                                    }}</span>
                                <span class="text-[14px] text-base-text-color-gray">{{ product_content?.address?.mobile
                                    }}</span>
                            </div>
                            <div>
                                <span class="text-[14px]">{{ product_content?.address?.province }}</span>
                            </div>
                            <div>
                                <span class="text-[14px]">{{ product_content?.address?.address }}</span>
                            </div>
                        </div>
                        <div>
                            <svg style="width:16px;height:16px;" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.76257 11.7123L6.82323 12.773L11.5962 8.00002L6.82323 3.22705L5.76257 4.28771L9.47501 8.00002L5.76257 11.7123Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col mt-[10px] bg-[#fff] pt-[20px] pl-[10px] pr-[10px]">
            <span class="text-[14px] font-bold">{{ $t('支付方式') }}</span>
            <div class="pt-[8px] pb-[8px]">
                <van-radio-group v-model="checked">
                    <van-radio class="mt-[16px]" :name="index" checked-color="#222"
                        v-for="(item, index) in product_content.pay_list" :key="index">
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

        <div class="mt-[10px] pt-[10px] bg-[#fff] pb-[10px]">
            <van-cell class="text-[14px]" :title="$t('钱包')"
                :value="$t('总共：{point}', { point: userStore.userInfo.balance })" title-style="color:#222" />
            <!--  <van-cell  :title="$t('积分')" is-link :value="$t('总共：{point}', { point: 1 })" title-style="color:#222"
                label-class="color:#767676; font-size:12px" /> -->
        </div>

        <div class="bg-[#fff] mt-[10px] p-[10px]">
            <span class="text-[14px] font-bold">{{ $t('订单详情') }}</span>
            <div class="pt-[8px] my-4" v-for="item in product_content.spec_list">
                <div class="flex justify-between items-center">
                    <div class="w-[72px] overflow-hidden flex-shrink-0 mr-[10px]">
                        <img :src="item.img" alt="">
                    </div>
                    <div class="flex flex-col justify-between flex-1 min-w-0 ">
                        <div class="flex flex-col">
                            <div class="flex items-center">
                                <span
                                    class="text-[#666] text-[12px] overflow-ellipsis whitespace-nowrap overflow-hidden">{{
                                        item.product_name }}</span>
                            </div>
                            <div class=" whitespace-nowrap text-ellipsis overflow-hidden">
                                <span class="text-[#666]  text-[12px]">{{ item.spec }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[15px] font-bold">{{ $sysCurrency }}{{ item.price }}</span>
                            <van-stepper v-model="item.number" integer :max="item.store" input-width="20px"
                                button-size="20px" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#fff] mt-[10px] pt-[10px] pb-[10px] pl-[10px] pr-[10px]">
            <div class="flex justify-between pt-[10px] pb-[10px]">
                <span class="text-[14px]">{{ $t('小计（{num} 项）：', { num: total[1] }) }}</span>
                <span class="text-[14px]"> {{ $sysCurrency }}{{ total[0].toFixed(2) }} </span>
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
                    <span class="text-[24px] font-bold text-[#fa6338] mt-[8px]"> {{ $sysCurrency }}{{ total[0].toFixed(2)
                        }}
                    </span>
                </div>
            </div>
        </div>

        <!--      <div class="mt-[10px] bg-[#fff] p-[10px]">
            <div class="flex items-center">
                <img class="w-[20px] h-[20px]"
                    src="https://img.ltwebstatic.com/images3_ccc/2024/07/15/0a/17210247754a30e7ed289354af602feeebcb0e1cad.png"
                    alt="">
                <div class="ml-[8px] text-[#000] text-[14px] font-bold">
                    {{ $t('报酬') }}
                    <span class="text-[#fa6338]">16</span>
                    {{ $t('积分') }}
                </div>
                <svg style="width:16px;height:16px;" width="16" height="16" viewBox="0 0 16 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.23995 4.55029C9.09047 4.55029 9.77995 5.23977 9.77995 6.09029C9.77995 6.48931 9.62885 6.85188 9.37991 7.12574C9.29503 7.21911 9.19641 7.32217 9.09143 7.43188C8.85209 7.682 8.57971 7.96665 8.36187 8.24965C8.03585 8.67321 7.73995 9.19942 7.73995 9.83029V10.0003H8.73995V9.83029C8.73995 9.52229 8.88325 9.21175 9.15431 8.8596C9.3399 8.61849 9.54474 8.40495 9.76309 8.17734C9.87853 8.05701 9.99773 7.93274 10.1199 7.79839C10.5296 7.34766 10.78 6.74763 10.78 6.09029C10.78 4.68749 9.64275 3.55029 8.23995 3.55029C6.83715 3.55029 5.69995 4.68749 5.69995 6.09029H6.69995C6.69995 5.23977 7.38943 4.55029 8.23995 4.55029Z"
                        fill="#959595"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.75 11.0002H7.75V12.0002H8.75V11.0002Z"
                        fill="#959595">
                    </path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M1.24683 8.05208C1.24683 4.27814 4.30621 1.21875 8.08016 1.21875C11.8541 1.21875 14.9135 4.27814 14.9135 8.05208C14.9135 11.826 11.8541 14.8854 8.08016 14.8854C4.30621 14.8854 1.24683 11.826 1.24683 8.05208ZM13.9135 8.05208C13.9135 4.83042 11.3018 2.21875 8.08016 2.21875C4.8585 2.21875 2.24683 4.83042 2.24683 8.05208C2.24683 11.2737 4.8585 13.8854 8.08016 13.8854C11.3018 13.8854 13.9135 11.2737 13.9135 8.05208Z"
                        fill="#959595"></path>
                </svg>
            </div>
        </div> -->

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
                {{ $t('继续') }}
            </div>

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
                        @click="copyText">{{ $t('复制') }}</van-button>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onMounted, ref, computed, onActivated } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { ShopService } from '@/api/shop';
import { useUserStore } from '@/store/userStore';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
import { CartStore } from '@/store/cart';
const { t } = useI18n();
const route = useRoute()
const userStore = useUserStore();
const cartstore = CartStore()
const router = useRouter()
const checked = ref(0);
const product_ids = ref("")
const product_content = ref({})
const get_orders = async () => {
    const res = await ShopService.order_info({ "product_ids": route.query.product_ids })
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

const showShare = ref(false);
const shareLink = ref("");

const copyText = async () => {
    const input = document.createElement("input"); //创建input 
    input.setAttribute("value", shareLink.value);            //把input设置value
    document.body.appendChild(input);            //添加这个dom对象
    input.select();                              //选中该输入框
    document.execCommand("copy");                //复制该文本 
    document.body.removeChild(input);            //移除输入框
    showToast(t("复制成功"))
    router.replace('/myorders')
};
//下单
const saveAction = async () => {
    console.log(product_content.value.pay_list[checked.value].type)
    let contacts;
    if (cartstore.address) {
        contacts = JSON.stringify([{ "name": cartstore.address.name, "mobile": cartstore.address.mobile, "address": cartstore.address.address }])
    } else {
        contacts = JSON.stringify([{ "name": product_content.value.address.name, "mobile": product_content.value.address.mobile, "address": product_content.value.address.address }])
    }

    let product_list = product_content.value.spec_list.map(item => {
        return { "product_id": item.product_id, "spec_id": item.id, "number": item.number, "take_day": "" }
    })
    let prodect_ids = JSON.stringify(product_list)
    const res = await ShopService.create_order({ "type": 2, "contacts": contacts, "product_ids": prodect_ids })
    cartstore.newcartlist()
    let order_ids = res.map(item => item.order_id).join(',');

    if (product_content.value.pay_list[checked.value].type == "balance") {
        const res1 = await ShopService.balance_pay({ "order_id": order_ids })
        showToast(t("支付成功"))
        router.replace('/myorders')
        return;
    }
    if (product_content.value.pay_list[checked.value].type == "proxy") {
        shareLink.value = window.location.origin + '/#/' + 'shareorder' + `?id=${order_ids}&lang=${localStorage.getItem("locale")}`
        showShare.value = true;
        return;
    }
    if (product_content.value.pay_list[checked.value].pay_type == 1) {
        const res1 = await ShopService.pay_info({ "order_id": order_ids, 'pay_type': product_content.value.pay_list[checked.value].type });
        if (res1.pay_info?.pay_url) {
            window.open(res1.pay_info?.pay_url);
        }
        return;
        // showToast(t("支付成功"))
        // router.replace('/myorders')
    }
    if (product_content.value.pay_list[checked.value].pay_type == 2) {
        const res1 = await ShopService.pay_info({ "order_id": order_ids, 'pay_type': product_content.value.pay_list[checked.value].type });
        // console.log(res1.pay_info)
        let json = {
            pay_amount: res1.pay_amount,
            name: res1.pay_info.name,
            ewm: res1.pay_info.ewm,
            src: res1.pay_info.src,
            number: res1.pay_info.number,
            pay_no:res1.pay_no
        }
        router.push({ path: '/offlinepayment', query: json })
        // showToast(t("支付成功"))
        // router.replace('/myorders')
    }


}
onActivated(async () => {
    if (route.query.product_ids) {
        if (userStore.userInfo.address_count == 0) {
            router.back()
        }
        product_ids.value = route.query.product_ids
        get_orders()
        await userStore.getUserInfo()
    } else {
        router.replace("/")
    }
})
</script>

<style lang="less" scoped>
.checkout-address-home__layout {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 10px;
    background: #fff;
}

.checkout-address-home__layout:before {
    position: absolute;
    bottom: 0;
    content: "";
    height: 3px;
    width: 100%;
}

.checkout-address-home__layout:before {
    background-size: 62px 8px
}

.checkout-address-home__layout:before {
    left: -10px;
    background-image: linear-gradient(90deg, #8dadd0 0, #8dadd0 39%, transparent 0, transparent 50%, #fdb9a5 0, #fdb9a5 89%, transparent 0, transparent);
    transform: skewX(-45deg)
}

.checkout-address-home__layout:before {
    right: -10px;
    background-image: linear-gradient(270deg, #8dadd0 0, #8dadd0 39%, transparent 0, transparent 50%, #fdb9a5 0, #fdb9a5 89%, transparent 0, transparent);
    transform: skewX(45deg)
}

.checkout-address-home__layout:active {
    background: #f6f6f6
}

/* span {
    line-height: 10px;
} */
</style>