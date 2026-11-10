<template>
    <div class="return">
        <Navbar :title="content?.title" :is_title="true"/>
        <div class="mt-[44px]">
            <div class="p-4">
                <div class="" v-html="content?.content"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue' 
import { LocalesService } from '@/api/locales'
import { useRoute,useRouter } from 'vue-router';
import { onActivated, onDeactivated, onMounted,onUnmounted,ref } from 'vue';
const route = useRoute()
const router = useRouter()
const content = ref({})
onActivated( async()=> {
    if(route.query.type){
        const res = await LocalesService.content({"type":route.query.type})
        content.value = res
    }else{
        router.go(-1)
    }
});
onDeactivated(()=>{
    content.value = {};
})

</script>