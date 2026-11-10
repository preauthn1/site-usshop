<template>
    <van-popup v-model:show="props.show" closeable position="bottom" :style="{ height: '80%' }"
        @click-overlay="handshow" @click-close-icon="handshow">
        <div class="pb-[50px]">
            <div class="pt-8 pl-4 ">
                <div class="flex justify-between pt-4 ">
                    <div class="flex-1 flex overflow-x-auto no-scrollbar">
                        <img :src="item" v-for="item in props.content?.photo" class="mr-[10px] w-[164px] h-[190px] "
                            alt="">
                    </div>

                </div>

            </div>
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <div class="w-2/3  overflow-hidden text-ellipsis whitespace-nowrap">
                        {{ props?.content?.title }}
                    </div>
                    <div class="1/3 flex-0 flex items-center text-[#2d68a8]"
                        @click="router.push(`/goodsdetail?id=${props?.content?.product_id}`)">
                        Details
                        <div class="w-3 h-3 rounded-full relative ml-1">
                            <span class=" top-[-1.5px] absolute rounded-full" style="background: rgba(45,104,168,.1);">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 10px; height: 10px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.96973 2.99474L5.03039 1.93408L9.09625 5.99995L5.03039 10.0658L3.96973 9.00515L6.97506 5.99995L3.96973 2.99474Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="py-6" v-for="item in props.content?.spec">
                    <div class="text-[14px] font-bold flex items-center pb-2">{{ item.name }}</div>
                    <div class="flex gap-4 flex-wrap">
                        <div class="py-2 px-4 border text-[12px] rounded-full  max-w-[300px] overflow-hidden text-ellipsis whitespace-nowrap"
                            :class="[selectList.includes(kids.name) ? 'border-black' : 'border-borderColor']"
                            v-for="kids in item.list" @click="handle(item.name, kids.name)">{{
                                kids.name }}</div>
                    </div>
                </div>
                <div class="flex pt-4 items-center text-[12px]">
                    <div class="pr-2">{{ $t("数量") }}:</div>
                    <van-stepper button-size="25px" input-width="25px" v-model="qty" />
                </div>
            </div>

            <div class="fixed bottom-0 w-full h-[50px] z-[10] flex items-center border-t border-borderColor">
                <div class="flex w-full justify-center text-[16px] items-center py-3 mx-4 bg-black text-white font-bold"
                    @click="updatecart">UPDATE</div>
            </div>
        </div>
    </van-popup>

</template>


<script setup>
import { showToast } from 'vant';
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { AuthService } from '@/api/home'
import { useI18n } from 'vue-i18n';
import { showLoading, hideLoading } from "@/utils/loadingManager";
import { CartStore } from '@/store/cart';
const { t } = useI18n();
const cartStore = CartStore()
const router = useRouter()
const props = defineProps(["show", "content"])
const emit = defineEmits()
const isnew = ref(false)
const handshow = (num) => {
    combination.value = []
    if (num != 1) {
        isnew.value = false
    } else {
        isnew.value = true
    }
    emit("closedetail", isnew.value)
}
const imageList = computed(() => {
    if (!props.content.goods_spec) {
        return []
    }
    return props.content.goods_spec.map(item => item.img);
})
const qty = ref()
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
const updatecart = async () => {
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
    console.log(zuheData, combination.value, '111');

    // 检查函数
    const equleAllElements = (name, elements) => {

        for (let index = 0; index < elements.length; index++) {
            const element = elements[index];
            if (element == name) {
                return true;
            }
        }
    };

    let filteredArray = props.content.goods_spec.filter(item => equleAllElements(item.name, zuheData));
    if (filteredArray.length != 1) {
        showToast(t("请选择完整的规格"))
        return;
    }
    showLoading()
    try {
        await AuthService.usercart({ "product_id": props.content.product_id, "spec_id": props.content.spec_id, "number": props.content.number, change: 'sub' })

        await AuthService.usercart({ "product_id": filteredArray[0].product_id, "spec_id": filteredArray[0].id, "number": qty.value, change: 'add' })
        await cartStore.newcartlist()
        selectList.value = []
        handshow(1)
        hideLoading()
    } catch (error) {
        showLoading()
    }

}
</script>

<style lang="less" scoped>
:deep(.van-popup__close-icon--top-right) {
    top: 5px !important;
    right: 3px;
    font-size: 16px;
    color: #000;
}
</style>