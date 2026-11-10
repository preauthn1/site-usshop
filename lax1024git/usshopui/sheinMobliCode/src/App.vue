<script setup>
import { computed, ref } from 'vue';
import Tabbar from './components/Tabbar.vue'
import { useRoute } from 'vue-router';
const route = useRoute();
const showTabbar = computed(() => route.meta.showTabbar == false);
</script>

<template>
  <div>
    <router-view/>
    <Tabbar v-if="showTabbar"/>
  </div>
</template>

<style scoped>

</style>
