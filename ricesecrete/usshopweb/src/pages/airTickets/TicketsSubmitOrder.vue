<template>
    <div class="">
        
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()

const tabData = ref([t('单程'), t('来回')]);//, t('多个目的地')
const tabSelectedIndex = ref(0);
const selectedTab = (index) => {
    tabSelectedIndex.value = index;
    console.log(tabSelectedIndex.value);
}
</script>
<style src="@/assets/css/ticket/ticket.css" scoped></style>