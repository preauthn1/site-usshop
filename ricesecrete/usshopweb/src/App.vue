<script setup>
import { computed, onMounted, ref,watch } from 'vue';
import Tabbar from './components/Tabbar.vue'
import { useRoute } from 'vue-router';
import PageLoading from './components/PageLoading.vue'
import { useUserStore } from '@/store/userStore';

const store = useUserStore();

const route = useRoute();
const showTabbar = computed(() => route.meta.showTabbar == false);
// 登录状态变化，重新设置keepAliveKey
const keepAliveKey = ref(0);
watch(() => store.token.token, () => keepAliveKey.value++);

onMounted(() => {
  const iframe = document.querySelector('#s-chat-plugin');
  if (iframe) {
    setTimeout(() => {
      const btn = iframe.contentDocument.querySelector('.view_btn_chat_pglag');
      btn.style.display = "none"; // 隐藏按钮
      iframe.style.setProperty('display', 'block', 'important');
      console.log(iframe, '---v');

    }, 1000)
  }
})
</script>

<template>
  <div>

    <router-view v-slot="{ Component }">
      <keep-alive :max="10">
        <component :is="Component"
          :key="$route.path.split('/')[1] + JSON.stringify($route.params) + ($route.meta.longKeep || keepAliveKey)">
        </component>
      </keep-alive>
    </router-view>
    <van-back-top offset="500" bottom="150" />
    <Tabbar v-if="showTabbar" />
    <PageLoading />
    <!-- <van-overlay class="flex items-center justify-center">
        <div class="w-[100px] h-[100px] bg-[#00000000] flex items-center justify-center">
            <van-loading color="#000" />
        </div>
    </van-overlay> -->
  </div>
</template>

<style scoped></style>
