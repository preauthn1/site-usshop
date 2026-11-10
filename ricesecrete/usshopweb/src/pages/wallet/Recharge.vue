<template>
    <div class="login bg-[#f6f6f6]">
        <Navbar :title="type == 1 ? $t('充值') : $t('提现')"></Navbar>
        <div class="mt-[44px] bg-[#f6f6f6] p-[16px]">
            <div class="flex flex-col bg-[#fff] pt-[10px] pl-[10px] pr-[10px]" v-if="type == 1">
                <span class="text-[14px] font-bold">{{ $t('充值方式') }}</span>
                <div class="pb-[8px]">
                    <van-radio-group v-model="checked">
                        <van-radio class="mt-[16px]" :name="index" checked-color="#222"
                            v-for="(item, index) in payoflist" :key="index">
                            <template #default>
                                <div class="flex items-center">
                                    <img class="h-[20px]" :src="item.src" alt="">
                                    <span class="ml-[10px] text-[14px]">{{ item.name }}</span>
                                </div>
                            </template>
                        </van-radio>
                    </van-radio-group>
                </div>
            </div>
            <div v-else>
                <div class="bg-[#fff]">
                    <van-field v-model="withdrawal_params.address" :placeholder="$t('请输入提现地址')" />
                </div>
            </div>
        </div>
        <div class="pt-[10px] pl-[16px] pr-[16px] pb-[20px]">
            <div class="bg-[#fff]">
                <van-field v-model="params.amount" :placeholder="$t('请输入充值金额')" v-if="type == 1" />
                <van-field v-else v-model="withdrawal_params.original_amount" type="number" :placeholder="t('请输入提现金额')" />
            </div>
            <div class="mt-[20px]">
                <van-button type="primary" block class="rounded-[0px] font-bold text-[14px]" @click="onSubmit">{{
                    type == 1 ? $t('充值') : $t('提现') }}</van-button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onActivated, onMounted, reactive, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useUserStore } from '@/store/userStore';
import { UserService } from '@/api/user';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const userStore = useUserStore();
const router = useRouter()
const route = useRoute()
const checked = ref(0)
const type = ref();
const payoflist = ref([]);
const get_payof = async () => {
    const res = await UserService.payment_channel({type:"recharge"})
    payoflist.value = res
}
const params = reactive({
    amount: '',
    channel_id: '',
})
const withdrawal_params = reactive({
    address: '',
    original_amount: '',
    channel_id: 4,
})
const onSubmit = async () => {
    if (type.value == 1) {
        params.channel_id = payoflist.value[checked.value].id
        if (!params.amount) {
            showToast(t("请输入充值金额"))
            return;
        }
        const res = await UserService.recharge({ ...params })
        window.location.href = res.pay_url
    } else {
        if(!withdrawal_params.address){
            return showToast(t("请输入提现地址"))
        }
        if(!withdrawal_params.original_amount){
            return showToast(t("请输入提现金额"))
        }
        const res = await UserService.withdrawal({...withdrawal_params})
        showToast(t("提现成功"))
    }
}
onActivated(() => {
    if (route.query.type) {
        type.value = route.query.type
        if (type.value == 1) {
            get_payof()
        }

    } else {
        router.replace('/')
    }
})
</script>

<style scoped></style>