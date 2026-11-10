<template>
    <div class="detail">
        <div
            class="h-[44px] bg-white z-10 flex fixed top-0 items-center justify-between border-b border-solid border-[#e5e5e5] max-w-[425px] w-full py-[6px] px-[6px]">
            <van-icon name="arrow-left" size="25" class="w-1/4" @click="router.go(-1)" />
            <div class="relative">
                <h2
                    class="max-w-[207px] text-ellipsis overflow-hidden whitespace-nowrap text-right text-[17px] font-bold">
                    {{detailContent?.title}}</h2>
            </div>
            <div class="w-1/4">
                <!-- <div class="px-2 flex justify-end">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"
                        >
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.2499 8H16.7499V12.5H15.2499V8ZM13.7499 12.5V6.5H18.2499V12.5H24.9999V17H23.4999V20.5C23.4999 22.4587 22.6955 24.2295 21.3989 25.5H6.39893C6.85149 25.0565 7.2441 24.5521 7.56347 24C8.15906 22.9704 8.49994 21.775 8.49994 20.5V17H6.99994V12.5H13.7499ZM9.99994 17H21.9999V20.5C21.9999 21.8301 21.5293 23.0483 20.7429 24H14.9999V21H13.4999V24H9.24778C9.73073 22.9325 9.99994 21.7473 9.99994 20.5V17ZM23.4999 14V15.5H8.49994V14H13.7499H18.2499H23.4999Z"
                            fill="currentColor"></path>
                    </svg>
                </div> -->
            </div>
        </div>
        <div class="p-4 mt-[44px]">
            <div class="" v-html="detailContent?.content">
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter,useRoute } from 'vue-router';
import { MessageService } from '@/api/message';
import { onActivated, onMounted,ref} from 'vue';
import { useUserStore } from '@/store/userStore';
const store = useUserStore()
const router = useRouter()
const route = useRoute()
const detailContent = ref({})
const get_detail =async (id) => {
    const res = await MessageService.detail({'id':id})
    detailContent.value = res
    if(detailContent.value.read == 2){
        await MessageService.read({'id':id})
        await store.getUserInfo()
    }
    
    
}
onActivated(()=>{
    if(route.query.id){
        get_detail(route.query.id)
    }else{
        router.back()
    }
})
</script>

<style lang="less" scoped></style>