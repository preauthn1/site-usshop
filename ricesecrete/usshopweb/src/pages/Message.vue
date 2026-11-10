<template>
    <div class="message">
        <div
            class="h-[44px] bg-white z-10 flex fixed top-0 items-center justify-between border-b border-solid border-[#e5e5e5] max-w-[425px] w-full py-[6px] px-[6px]">
            <van-icon name="arrow-left" class="w-1/4" @click="router.go(-1)" size="20" />
            <div class="relative">
                <h2
                    class="max-w-[207px] text-ellipsis overflow-hidden whitespace-nowrap text-right text-[17px] font-bold">
                    {{ $t('消息') }}</h2>
            </div>
            <div class="w-1/4" @click="readall">
                <div class="px-2 flex justify-end">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.2499 8H16.7499V12.5H15.2499V8ZM13.7499 12.5V6.5H18.2499V12.5H24.9999V17H23.4999V20.5C23.4999 22.4587 22.6955 24.2295 21.3989 25.5H6.39893C6.85149 25.0565 7.2441 24.5521 7.56347 24C8.15906 22.9704 8.49994 21.775 8.49994 20.5V17H6.99994V12.5H13.7499ZM9.99994 17H21.9999V20.5C21.9999 21.8301 21.5293 23.0483 20.7429 24H14.9999V21H13.4999V24H9.24778C9.73073 22.9325 9.99994 21.7473 9.99994 20.5V17ZM23.4999 14V15.5H8.49994V14H13.7499H18.2499H23.4999Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-[#f8f8f8] mt-[44px]">
            <div class="w-full min-h-[200px] bg-[white] px-4">
                <van-list v-model:loading="loading" :finished="finished" loading-text="loading"
                    :finished-text="$t('没有更多了')" @load="onLoad">
                    <div class="flex justify-between items-center py-4 border-b border-[rgb(238,238,238)]"
                        v-for="item in meaasgeList" @click="router.push(`/messagedetail?id=${item.id}`)">

                        <div class="w-1/3 pr-2" v-if="item?.img">
                            <img src="../assets/image/message.png" alt="">
                        </div>
                        <div :class="item?.img ? 'w-2/3' : 'w-full'">
                            <div class="flex items-center">
                                <div class="w-[10px] mr-2 h-[10px] rounded-full bg-red-600" v-if="item.read == 2"></div>
                                <div
                                    class="text-[14px] font-bold overflow-hidden text-ellipsis w-full whitespace-nowrap">
                                    {{ item?.title }}</div>
                            </div>

                            <div class="textline-2 w-full text-[12px]" v-html="item.content">

                            </div>
                        </div>
                    </div>
                </van-list>
            </div>
        </div>
    </div>

</template>

<script setup>
import { useRouter } from 'vue-router';
import { MessageService } from '@/api/message';
import { onActivated, onMounted, reactive, ref } from 'vue';
import { useUserStore } from '@/store/userStore';
const meaasgeList = ref([])
const store = useUserStore()
const loading = ref(false)
const finished = ref(false)
const pageInfo = reactive({
    page:1,
    limit:30
})
const is_init = ref(true)
const onLoad = async (num) => {
    const res = await MessageService.notification({...pageInfo})
    if(num == 1){
        meaasgeList.value = []
    }
    
    meaasgeList.value = [...meaasgeList.value,...res.dataList]
    if(meaasgeList.value.length < res.total){
        pageInfo.page++;
        loading.value = false
    }else{
        finished.value = true;
    }
}
const readall = async () => {
    let isclear = meaasgeList.value.some(item => item.read == 2)
    if (isclear) {
        const res = await MessageService.readAll();
        await store.getUserInfo()
        pageInfo.page = 1
        onLoad(1);
    }

}
onActivated(()=>{
    pageInfo.page = 1;
    onLoad(1);
})
const router = useRouter()

</script>

<style lang="less" scoped></style>