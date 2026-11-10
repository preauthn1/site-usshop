<template>
    <div class="language">
        <Navbar :title="$t('更改语言')"></Navbar>
        <div class="mt-[44px]">
            <div class="p-4 flex justify-between border-b border-borderColor" 
                v-for="(item, index) in language" @click="fetchTranslations(item.code)">
                <div class="flex justify-between text-[#222] text-[14px] font-bold">
                    <div class="pr-8">{{ item.name }}</div>
                    <!-- <div class="">{{item.cur}}</div> -->
                </div>
                <van-icon name="success" v-if="item.code == seleceCur" />
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { LocalesService } from '@/api/locales'
import { useRouter } from 'vue-router';
const { locale } = useI18n();
const seleceCur = ref()
seleceCur.value = localStorage.getItem("locale") || 'en'
const router = useRouter()
const language = ref([])
const fetchTranslations = async (code) => {
    if(seleceCur.value == code)return;
    seleceCur.value = code
    localStorage.setItem("locale", seleceCur.value);
    window.location.reload();
};
const getlanguage = async () => {
    const response = await LocalesService.language();
    language.value = response
}
getlanguage()
</script>

<style scoped lang="less"></style>