<template>
    <div class="phone bg-base-selector-color">
        <Navbar :title="$t('输入手机号')"  />
        <div class="mt-[44px]">
            <div class="pt-4"></div>
            <div class=" p-6 bg-white">
                <div class=" mb-5 text-[#222] text-[14px]">
                    {{$t('输入并验证手机号码后，您可以直接使用您的手机号码登录')}}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
</script>

<style lang="less" scoped></style>