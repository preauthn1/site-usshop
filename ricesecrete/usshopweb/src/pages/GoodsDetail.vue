<template>
    <div class="goodsdetail pt-[44px]">
        <Header :showMenu="true" :showBack="true" :showMessage="false" :showWishlist="false"></Header>
        <div class="goodsdetail-content  bg-base-selector-color pt-4 pb-[60px]">
            <div class="bg-white pb-4">
                <div class="pb-4">
                    <van-swipe :autoplay="3000" lazy-render class="w-full h-auto">
                        <van-swipe-item v-for="image in banner" :key="image.url" class="min-h-[200px]">
                            <img :src="image.url" class=" w-full object-contain" alt="">
                        </van-swipe-item>
                        <template #indicator="{ active, total }">
                            <div class="  absolute bottom-1 text-black left-[50%]">{{ active + 1 }}/{{ total }}</div>
                        </template>
                    </van-swipe>
                </div>
                <div class="px-4">

                    <div class="flex w-full items-center">
                        <div class="text-[11px]">{{ $sysCurrency }}</div>
                        <div>{{ content?.min_price }}</div>
                    </div>
                    <h1 class="text-[12px] text-detail-title py-4">
                        {{ content?.title }}
                    </h1>
                    <div class="pb-6" v-for="item in sizeList">
                        <div class="text-[14px] font-bold flex items-center pb-2">{{ item.name }}</div>
                        <div class="flex gap-4 flex-wrap">
                            <div class="py-2 px-4 border text-[12px] rounded-full  max-w-[300px] overflow-hidden text-ellipsis whitespace-nowrap"
                                :class="[selectList.includes(kids.name) ? 'border-black' : 'border-borderColor']"
                                v-for="kids in item.list" @click="handle(item.name, kids.name)">{{
                                    kids.name }}</div>
                        </div>
                    </div>

                    <div class="flex pt-4 items-center text-[12px]">
                        <div class="pr-2">{{ $t('数量') }}:</div>
                        <van-stepper button-size="25px" input-width="25px" v-model="qty" />
                    </div>
                </div>
            </div>
            <div class="mt-4 pb-[20px] bg-white px-4">
                <div class="py-4 text-[14px] font-bold">{{ $t('更多详情') }}</div>
                <div class=" relative  overflow-hidden" :class="!isshow ? 'h-[250px]' : 'h-full'">
                    <img :src="img" alt="" v-for="img in content?.details" :key="img">
                </div>
                <div class=" relative items-center z-[2]  p-4 text-[12px] w-full flex justify-center text-base-text-color-gray"
                    :class="isshow ? '' : 'cover'" @click="isshow = !isshow">
                    <span>{{ !isshow ? $t('浏览更多') : $t('收起') }}</span>
                    <van-icon color="#767676" :name="!isshow ? 'arrow-down' : 'arrow-up'" />
                </div>
            </div>
            <div class="mt-4 bg-white p-4">
                <div class="flex justify-between pt-2 pb-4 border-b border-borderColor">
                    <div class="pr-2">
                        <van-icon name="warning-o" />
                    </div>
                    <div class="max-w[90px] textline-2 text-[12px] pr-2">{{ $t('此类别的商品不能退货或换货。') }}
                    </div>
                    <div><van-icon color="#666" name="arrow" /></div>
                </div>
                <div class="pt-2 text-[12px]">
                    <div class="flex justify-between ">
                        <div class="pr-2 flex items-center">
                            <span>
                                <svg t="1739432192702" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="4232" width="18" height="18">
                                    <path
                                        d="M867.584 160.192c-149.632-16.928-262.208-57.408-334.592-120.352l-19.04-16.544-20.544 14.656C379.968 118.944 267.776 160 160 160H128v448c0 137.344 121.088 261.92 370.208 380.864l13.088 6.24 13.344-5.728C771.072 883.52 896 755.232 896 608V163.424l-28.416-3.232zM832 608c0 116.8-107.392 223.36-319.328 316.8C299.872 821.024 192 714.464 192 608V222.976c104.672-6.784 211.584-46.688 318.496-118.944C587.232 162.528 695.168 201.536 832 220.256V608z"
                                        p-id="4233" fill="#26b130"></path>
                                    <path
                                        d="M359.776 468.672a32 32 0 1 0-47.968 42.4l121.792 137.824c12.608 14.24 30.176 21.568 47.904 21.568a64.384 64.384 0 0 0 49.696-23.52l197.6-242.72a32 32 0 0 0-49.632-40.416l-197.6 242.688-121.792-137.824z"
                                        p-id="4234" fill="#26b130"></path>
                                </svg>
                            </span>
                            <div class="pl-2 text-[12px]">{{ $t('购物安全') }}</div>
                        </div>
                        <div><van-icon color="#666" name="arrow" /></div>
                    </div>
                    <div class="px-6 py-2 flex flex-wrap gap-3 text-[11px]">
                        <div class="flex items-center">
                            <span class="productShippingGuarantee__dot"></span>
                            <span class="pr-4">{{ $t('安全支付') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="productShippingGuarantee__dot"></span>
                            <span class="pr-2">{{ $t('安全物流') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="productShippingGuarantee__dot"></span>
                            <span class="pr-2">{{ $t('客户服务') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="productShippingGuarantee__dot"></span>
                            <span class="pr-2">{{ $t('隐私保护') }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div
            class="fixed flex z-10  items-center p-2 bottom-0 h-[50px] max-w-[425px] bg-white w-full border-t border-borderColor">
            <div class="pr-4">
                <span v-if="content?.is_collect == 1" @click="handlecollect">
                    <svg t="1740030974517" class="icon" viewBox="0 0 1220 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2638" width="27" height="27">
                        <path
                            d="M886.153846 0c-118.153846 0-224.492308 63.015385-275.692308 161.476923-51.2-98.461538-157.538462-161.476923-275.692307-161.476923-173.292308 0-315.076923 137.846154-315.076923 307.2 0 82.707692 11.815385 165.415385 102.4 275.692308C208.738462 689.230769 590.769231 1008.246154 610.461538 1024c19.692308-15.753846 401.723077-334.769231 488.369231-441.107692 90.584615-110.276923 102.4-192.984615 102.4-275.692308-3.938462-169.353846-141.784615-307.2-315.076923-307.2z"
                            fill="#000000" p-id="2639"></path>
                    </svg>
                </span>
                <span v-else @click="handlecollect">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.3938 4.98632C9.25839 2.96853 5.89126 3.00521 3.80009 5.09639C1.67157 7.22433 1.79597 10.2517 3.80009 12.8033C3.89976 12.9302 4.0098 13.0647 4.12892 13.2059L4.37892 13.4966L4.65088 13.803C4.69791 13.8553 4.74576 13.9081 4.79439 13.9615L5.09505 14.2877L5.41199 14.6249L5.74295 14.9709L6.08566 15.324L6.43784 15.6823L6.97891 16.2251L7.42184 16.6637L7.80117 17.0358L8.73799 17.9432L9.73299 18.8916L11.0299 20.108L12 21.0032L13.4782 19.634L15.0337 18.162L15.6098 17.6081L16.5782 16.6637L17.0211 16.2251L17.3832 15.8627L17.7393 15.5026L18.087 15.1467L18.4241 14.7969L18.7484 14.4551L19.0575 14.1231L19.3491 13.803L19.6211 13.4966L19.8711 13.2059C19.9902 13.0647 20.1002 12.9302 20.1999 12.8033C22.204 10.2517 22.3284 7.22433 20.1999 5.09639C18.0714 2.96787 14.6209 2.96787 12.493 5.09639L12 5.58882L11.507 5.09639L11.3938 4.98632ZM4.86075 6.15705C6.40346 4.61434 8.90405 4.61434 10.4462 6.1569L12 7.7089L13.5531 6.15766L13.6528 6.06125C15.2019 4.61504 17.6294 4.64717 19.1393 6.15705C20.6516 7.66897 20.6265 9.83175 19.0203 11.8768L18.8628 12.0727L18.6861 12.2843L18.4906 12.511L18.2766 12.7525L17.9223 13.1416L17.6645 13.4182L17.39 13.708C17.3429 13.7574 17.2951 13.8073 17.2466 13.8578L16.7926 14.3258L16.3041 14.8207L15.7825 15.3411L15.2662 15.8498L14.7911 16.3129C13.9912 17.0891 13.1541 17.884 12.3195 18.6639L12 18.9615L11.0797 18.1002C10.2795 17.3464 9.48556 16.5861 8.73228 15.8483L8.47296 15.5936L8.21581 15.3395L7.69483 14.8196L7.20668 14.325L6.90028 14.0102L6.47037 13.5612L6.20426 13.278L5.837 12.8786L5.50943 12.511L5.31389 12.2842L5.13717 12.0727L4.97973 11.8768C3.37349 9.83175 3.34842 7.66897 4.86075 6.15705Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>

                </span>

            </div>
            <div class="flex justify-center items-center bg-black text-white h-full w-full font-bold" @click="addcart">
                {{ $t('添加到购物车') }}
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, onActivated, onMounted, ref } from 'vue';
import { showLoading, hideLoading } from "@/utils/loadingManager";
import Header from '../components/Header.vue';
import { AuthService } from '@/api/home';
import { useRoute, useRouter } from 'vue-router';
import { showToast } from 'vant';
import { UserService } from '@/api/user'
import { useUserStore } from '@/store/userStore';
import { CartStore } from '@/store/cart';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const route = useRoute()
const router = useRouter()
const store = useUserStore()
const cartstore = CartStore()
const banner = ref([])
const qty = ref()
const content = ref()
const isshow = ref(false)
const sizeList = ref([])
const getdetail = async (id) => {
    const res = await AuthService.goods_detail({ "id": Number(id) })
    sizeList.value = res.spec
    banner.value = res.photo
    content.value = res

}
const combination = ref([])
const handle = (fethername, kidname) => {
    let has = combination.value.findIndex(item => item.fether == fethername)
    if (has >= 0) {
        combination.value[has].kid = kidname
    } else {
        combination.value.push({ fether: fethername, kid: kidname })
    }
    hasactive()
}
const selectList = ref([])
const hasactive = () => {
    selectList.value = []
    combination.value.forEach((item) => {
        selectList.value.push(item.kid)
    })
}

//收藏
const handlecollect = async () => {
    if (!store.token.token) {
        return router.push("/login")
    }
    await UserService.collect({ 'id': content.value.id, type: 2 })
    content.value.is_collect = !content.value.is_collect
}
//加入购物车
const addcart = async () => {

    const getAllCombinations = (arr) => {
        const result = [];
        const generateCombinations = (prefix, remaining) => {
            if (remaining.length === 0) {
                result.push(prefix.join(';'));
            } else {
                for (let i = 0; i < remaining.length; i++) {
                    generateCombinations([...prefix, remaining[i].kid], remaining.slice(0, i).concat(remaining.slice(i + 1)));
                }
            }
        };
        generateCombinations([], arr);
        return result;
    };

    const zuheData = getAllCombinations(combination.value);
    // 检查函数
    const containsAllElements = (name, elements) => {
        return elements.every(element => name.includes(element.kid));
    };
    // 检查函数
    const equleAllElements = (name, elements) => {

        for (let index = 0; index < elements.length; index++) {
            const element = elements[index];
            if (element == name) {
                return true;
            }
        }
    };

    let filteredArray = content.value.goods_spec.filter(item => equleAllElements(item.name, zuheData));
    if (filteredArray.length != 1) {
        showToast(t("请选择完整的规格"))
        return;
    }

    showLoading()
    try {
        await AuthService.usercart({ "product_id": filteredArray[0].product_id, "spec_id": filteredArray[0].id, "number": qty.value, change: 'add' })
        showToast(t("添加成功"))
        await cartstore.newcartlist()
        hideLoading()
    } catch (error) {
        hideLoading()
    }
}
onActivated(async () => {
    selectList.value = []
    sizeList.value = []
    banner.value = []
    content.value = {}
    if (route.query.id) {
        getdetail(route.query.id);
    }else{
        router.replace('/')
    }

})
</script>
<style lang="less" scoped>
.productShippingGuarantee__dot {
    display: inline-block;
    width: 5px;
    height: 5px;
    background: #198055;
    border-radius: 50%;
    margin-right: .11rem;
}

.cover::before {
    position: absolute;
    content: "";
    top: -99px;
    width: 100%;
    height: 100px;
    background: linear-gradient(180deg, hsla(0, 0%, 100%, 0), hsla(0, 0%, 100%, .73) 58%, #fff);

}
</style>