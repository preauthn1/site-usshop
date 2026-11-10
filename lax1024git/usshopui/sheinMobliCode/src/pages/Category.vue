<template>
    <div class="home">
        <Header :showMenu="false"></Header>
        <span class="text-[#000000]">123123123131</span>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import Header from '../components/Header.vue';
</script>
<style scoped>

.home {
    height: 100%;
    padding-bottom: 80px;
}
</style>