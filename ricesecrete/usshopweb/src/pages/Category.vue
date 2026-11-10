<template>
    <div class="flex flex-col h-screen pt-[44px]">
        <Header :showMenu="false"></Header>
        <div class="pl-6" v-if="!tablist.length > 0">
            <div class="w-full flex justify-between items-center ">
                <div class="w-[40px] h-[20px] bg-base-selector-color" v-for="item in 5"></div>
            </div>
        </div>
        <div v-else>
            <van-sticky>
                <van-tabs v-model:active="active" shrink sticky @click-tab="changTab">
                    <van-tab v-for="(item, index) in tablist" :title="item.name">
                    </van-tab>
                </van-tabs>
            </van-sticky>
        </div>
        <div class="mt-[10px] flex flex-1 overflow-hidden" v-if="false">
            <div class="w-[30%] overflow-y-auto">
                <van-sidebar v-model="activeType" @change="changeTabs">
                    <van-sidebar-item v-for="(item, index) in 15" :key="index">
                    </van-sidebar-item>
                </van-sidebar>
                <div class="h-[50px]"></div>
            </div>
            <div class="flex flex-col flex-1">
                <div class="flex-1 overflow-y-auto">
                    <div class="list-wapper">
                        <div class="flex flex-col items-center" v-for="(item, index) in 10" :key="index">
                            <van-image class="image-content" fit="cover" round src="" />
                            <span class="w-full h-[15px]"></span>
                        </div>
                    </div>
                </div>
                <div class="h-[50px]"></div>
            </div>
        </div>

        <div v-if="loding" class="mt-[10px] flex flex-1 overflow-hidden">
            <div class="w-[28%] overflow-y-auto">
                <van-sidebar class="w-full">
                    <van-sidebar-item v-for="(item, index) in 10" :key="index">
                    </van-sidebar-item>
                </van-sidebar>
                <div class="h-[50px]"></div>
            </div>
            <div class="flex flex-col flex-1">
                <div class="flex-1 overflow-y-auto">
                    <div class="list-wapper">
                        <div class="flex flex-col items-center" v-for="(item, index) in 10" :key="index">
                            <van-image class="image-content" fit="cover" round>
                                <template slot="loading">
                                    <van-loading type="spinner" size="20" />
                                </template>
                            </van-image>
                            <span class="textline-2"></span>
                        </div>
                    </div>
                </div>
                <div class="h-[50px]"></div>
            </div>
        </div>
        <div v-else class="mt-[10px] flex flex-1 overflow-hidden">
            <div class="w-[28%] overflow-y-auto">
                <van-sidebar class="w-full" v-model="activeType" @change="changeTabs">
                    <van-sidebar-item v-for="(item, index) in Secondaryclassification" :key="index" :title="item.name">
                    </van-sidebar-item>
                </van-sidebar>
                <div class="h-[50px]"></div>
            </div>
            <div class="flex flex-col flex-1">
                <div class="flex-1 overflow-y-auto">
                    <div class="list-wapper">
                        <div class="flex flex-col items-center" v-for="(item, index) in thirdList" :key="index"
                            @click="router.push({ path: '/choosegoods', query: { typeId: item.id } })">
                            <van-image class="image-content" fit="cover" round :src="item.img" />
                            <span class="textline-2">{{ item.name }}</span>
                        </div>
                    </div>
                </div>
                <div class="h-[50px]"></div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import Header from '@/components/Header.vue';
import { AuthService } from '@/api/home';
import { Loading } from 'vant';
import { useRouter } from 'vue-router';
import { menuStore } from '@/store/menu';
const store = menuStore()
const router = useRouter()
const active = ref(0);
const activeType = ref(0);
const tablist = ref([])
const Secondaryclassification = ref([])
const loding = ref(false)
const getcatelist = async () => {
    try {
        loding.value = true;
        
        if(store.menuList.length <= 0){
            await store.getmenuList()
        }
        const res = store.menuList;
        if (res && res.length > 0) {
            tablist.value = res;
            Secondaryclassification.value = tablist.value[active.value]?.children || [];
            if (Secondaryclassification.value.length > 0) {
                await getthreecate(Secondaryclassification.value[0].id);
            }
        }
    } catch (error) {
        console.error('Error fetching category list:', error);
    } finally {
        loding.value = false;
    }
}
const thirdList = ref([])
const getthreecate = async (id) => {
    thirdList.value = []
    const res = Secondaryclassification.value.filter(item => item.id == id)
    if (res.length > 0) {
        thirdList.value = res[0].children
    }
}
const changTab = () => {
    activeType.value = 0
    Secondaryclassification.value = tablist.value[active.value].children
    getthreecate(Secondaryclassification.value[0].id)
}
//侧边栏变化
const changeTabs = () => {
    getthreecate(Secondaryclassification.value[activeType.value].id)
}

onMounted(() => {
    console.log(store.menuList,'这是当前状态')
    getcatelist()
})
</script>
<style scoped>
:deep(.van-sticky--fixed) {
    top: 40px !important;
}

.list-wapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 10px;
}

.span-text {
    font-size: min(3.2vw, 17.28px);
    color: rgba(0, 0, 0, .6);
    width: min(17.6vw, 95px);
    text-align: center;
    word-wrap: break-word;
    display: inline-block;
}

.image-content {
    width: min(17.6vw, 95px);
    height: min(17.6vw, 95px);
    position: relative;
}

:deep(.van-sidebar-item__text) {
    word-break: break-word;
}
</style>