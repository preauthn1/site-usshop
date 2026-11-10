<template>
    <div class="nickname bg-base-selector-color h-screen">
        <Navbar :title="$t('账户设置')" />
        <div class="mt-[44px]">
            <div class="w-full">
                <van-field v-model="nick_name" class="w-full" :placeholder="$t('请输入用户名')" clearable/>
            </div>
            <div class="my-10 mx-5 flex justify-center items-center py-4 bg-black text-white font-bold" @click="sub">{{$t('保存')}}</div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useUserStore } from '@/store/userStore';
import { UserService } from '@/api/user';
import { useRouter } from 'vue-router';
const store = useUserStore()
const nick_name = ref()
const router = useRouter()
nick_name.value = store.userInfo.nick_name
const sub = async () => {
    if(!nick_name.value)return;
    if(store.userInfo.nick_name == nick_name.value)return;
    await UserService.up_userInfo({"nick_name":nick_name.value})
    await store.getUserInfo() 
    router.go(-1)
}
</script>

<style lang="less" scoped>

</style>