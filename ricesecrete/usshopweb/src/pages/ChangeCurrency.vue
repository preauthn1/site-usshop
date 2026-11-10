<template>
    <div class="changecurrency">
        <Navbar :title="$t('更改货币')"></Navbar>
        <div class="mt-[44px]">
            <div class="p-4 flex justify-between border-b border-borderColor" v-for="item in currency"
                @click="change_currency(item.id)">
                <div class="flex justify-between text-base-text-color-gray text-[12px]">
                    <div class="pr-8 font-bold">{{ item.name }}</div>
                    <div class="">{{ item.symbol }}</div>
                </div>
                <van-icon name="success" v-if="userStore.userInfo.currency == item.code" />
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onMounted, ref } from 'vue';
import { useUserStore } from '@/store/userStore';
import { LocalesService } from '@/api/locales'
import { UserService } from '@/api/user';
import { useRouter } from 'vue-router';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
import { sysCurrency } from '@/utils/global';
const { t } = useI18n()
const userStore = useUserStore();
const router = useRouter()
const currency = ref([])
const get_currency = async () => {
    const res = await LocalesService.currency()
    currency.value = res
}
const change_currency = async (id) =>{
    
    let cur = currency.value.filter(item => item.id == id)
    if(userStore.userInfo.currency == cur[0].code)return;
    await UserService.up_userInfo({"currency":cur[0].code})
     // 设置系统货币
     localStorage.setItem('sys_currency',cur[0].code)
    await userStore.getUserInfo()
    sysCurrency.value = cur[0].symbol
    showToast(t("修改成功"))
    router.back()
    setTimeout(()=>{
        window.location.reload();
    },1000)   
}
onMounted(()=>{
    get_currency();
})

</script>

<style lang="less" scoped></style>