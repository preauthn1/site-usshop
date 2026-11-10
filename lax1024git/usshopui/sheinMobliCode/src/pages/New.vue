<template>
    <div class="home">
        <Header :showMenu="false" :showBack="true"></Header>
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