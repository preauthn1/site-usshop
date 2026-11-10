<template>
    <div class="changecurrency bg-base-selector-color h-screen">
        <Navbar :title="$t('管理我的账户')"></Navbar>
        <div class="mt-[44px]">
            <div class="pt-4"></div>
            <div class="p-4 bg-white">
                <div class="flex justify-between py-6  items-center"
                    :class="index + 1 == accountmenu.length ? '' : 'border-b border-borderColor'"
                    v-for="( item, index) in accountmenu" @click="router.push(item.url)">
                    <div class="text-[14px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                        {{ item.title }}</div>
                    <div class="flex items-center">
                        <div class="text-[12px] text-[#666]" v-if="item.value">{{ item.value }}</div>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 12px; height: 12px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const accountmenu = ref([
   /*  { title: "邮箱", value: "未验证",url:"/email" }, */
    { title: t('更改密码'), value: "",url:"/changepassword" },
    // { title: t('删除账户'), value: "" ,url:"/deleteaccount"},
    /* { title: "电话号码", value: "增加", url:'/phone'}, */
])
</script>

<style lang="less" scoped></style>