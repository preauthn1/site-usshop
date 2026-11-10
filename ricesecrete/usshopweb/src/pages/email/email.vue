<template>
    <div class="email">
        <Navbar :title="$t('邮箱')"/>
        <div class="mt-[44px]">
            <div class="px-[24px] py-[60px] pb-[100px] text-center">
                <p class="text-[16px] text-[#666]">{{$t('验证电子邮箱')}}</p>
                <h3 class="pt-[15px] font-bold text-[16px]">x*********0@gmail.com</h3>
            </div>
            <div class=" text-base-text-color-gray text-[12px] text-center">{{$t('100积分将在您的口袋里。')}}</div>
            <div class="py-3 px-8 mx-auto max-w-full">
                <div class="mx-auto flex justify-center items-center py-4 bg-black text-white font-bold">{{$t('验证')}}</div>
                <div class="mx-auto mt-4 flex justify-center items-center border border-borderColor py-4  text-black font-bold" @click="router.push('/emailverify')">{{$t('更改')}}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import { useRouter } from 'vue-router';
const router = useRouter();

</script>

<style lang="less" scoped>
    
</style>