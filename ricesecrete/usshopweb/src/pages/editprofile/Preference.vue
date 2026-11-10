<template>
    <div class="nickname bg-base-selector-color h-screen">
        <Navbar :title="$t('偏好')" />
        <div class="mt-[44px]">
            <div class="p-4 bg-white">
                <div class="">
                    <div class="text-[12px] font-bold">{{ paransInfo[1]?.title }}</div>
                    <div class="flex pt-2 flex-wrap">
                        <div class="pr-4 pb-4" v-for="content in typeList[1]?.content">
                            <div @click="handlehobby(content.id)"
                                :class="select_hobby_ids.includes(content.id) ? 'activePreference' : ''"
                                class="bg-base-selector-color  py-2 px-4 flex justify-center items-center text-[12px]">
                                {{ content?.title }}</div>
                        </div>

                    </div>
                </div>
                <div class="">
                    <div class="text-[12px] font-bold">{{ paransInfo[2]?.title }}</div>
                    <div class="flex pt-2 flex-wrap">
                        <div class="pr-4 pb-4" v-for="content in typeList[2]?.content">
                            <div @click="handlehobby(content.id)"
                                :class="select_hobby_ids.includes(content.id) ? 'activePreference' : ''"
                                class="bg-base-selector-color  py-2 px-4 flex justify-center items-center text-[12px]">
                                {{ content?.title }}</div>
                        </div>

                    </div>
                </div>
                <div class="">
                    <div class="text-[12px] font-bold">{{ paransInfo[3]?.title }}</div>
                    <div class="flex pt-2 flex-wrap">
                        <div class="pr-4 pb-4" v-for="content in typeList[3]?.content">
                            <div @click="handlehobby(content.id)"
                                :class="select_hobby_ids.includes(content.id) ? 'activePreference' : ''"
                                class="bg-base-selector-color  py-2 px-4 flex justify-center items-center text-[12px]">
                                {{ content.title }}</div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="my-10 mx-5 flex justify-center items-center py-4  font-bold"
                :class="!isvalue ? 'bg-[#999] text-[#e5e5e5]' : 'bg-black text-white'" @click="suBtn">{{ $t('保存') }}</div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { computed, onActivated, onMounted, ref } from 'vue';
import { UserService } from "@/api/user";
import { useRouter } from 'vue-router';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const typeList = ref([])
const paransInfo = ref({})
const select_hobby_ids = ref([])
//是否爱好
const handlehobby = (e) => {
    if (select_hobby_ids.value.includes(e)) {
        let index = select_hobby_ids.value.indexOf(e)
        select_hobby_ids.value.splice(index, 1);
    } else {
        select_hobby_ids.value.push(e)
    }
}
const handleselect = (num) => {
    select_hobby_ids.value.push(...paransInfo.value[num].value)
}
const get = async () => {
    const res = await UserService.hobbyList()
    paransInfo.value = res.paransInfo
    typeList.value = res.typeList
    for (const key in paransInfo.value) {
        if (paransInfo.value[key].value.length > 0) { // 检查 value 是否不为空
            select_hobby_ids.value = select_hobby_ids.value.concat(paransInfo.value[key].value);
        }
    }
    console.log(select_hobby_ids.value)
}
onActivated(() => {
    get()
})
const isvalue = computed(() => {
    let has;
    if (select_hobby_ids.value.length > 0) {
        has = true
    } else {
        has = false
    }
    return has
})
const suBtn =async() => {
    if (isvalue) {
        const res = await UserService.hobby({ 'ids': select_hobby_ids.value.map(item => item).join(',') })
        showToast(t("修改成功"))
        router.back()
    }

}
</script>

<style lang="less" scoped>
.activePreference {
    border: 1px solid #222;
    position: relative;
}

.activePreference::before {
    position: absolute;
    content: "";
    width: 15px;
    height: 15px;
    background-size: 100% 100%;
    top: 0;
    right: 0;
    background-image: url('@/assets/image/cancel.png');
}
</style>