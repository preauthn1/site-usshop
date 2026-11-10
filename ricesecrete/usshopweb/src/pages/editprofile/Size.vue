<template>
    <div class="size bg-base-selector-color h-screen">
        <Navbar :title="$t('我的测量')"></Navbar>
        <div class="mt-[44px]">
            <div class="pt-4"></div>
            <div class="bg-white p-4 text-[12px] text-detail-title">
                <p>{{ $t('我的测量有助于简化查找过程完美契合！!') }}</p>
                <p>{{ $t('填写我的尺寸可以让您:') }}</p>
                <p class="text-[#a86104]"> {{ $t('将测量结果应用于我们的尺寸图') }}</p>
                <p class="text-[#a86104]">{{ $t('为其他SHEINers新建有用的评论以供参考') }}</p>
            </div>
            <div class="mt-4" v-if="loading">
                <div class="p-4 bg-white text-detail-title">
                    <div class="flex w-full gap-4">
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('4')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[4]?.title }}</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(4) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(4) ? getTitlesFromValues(4) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('5')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[5]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(5) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(5) ? getTitlesFromValues(5) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                    </div>
                    <div class="flex w-full gap-4">
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('6')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[6]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(6) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(6) ? getTitlesFromValues(6) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('7')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[7]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(7) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(7) ? getTitlesFromValues(7) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                    </div>
                    <div class="flex w-full gap-4">
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('8')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[8]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(8) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(8) ? getTitlesFromValues(8) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor"
                            @click="handlepopdata('9')">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[9]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(9) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(9) ? getTitlesFromValues(9) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                    </div>
                    <div class="flex w-full gap-4" @click="handlepopdata('10')">
                        <div class="flex py-4 items-center justify-between w-1/2 border-b border-borderColor">
                            <div class="flex flex-col">
                                <div class="text-base-text-color-gray text-[12px]">{{ paransInfo[10]?.title }}:</div>
                                <div class="text-[14px] "
                                    :class="getTitlesFromValues(10) ? 'text-[#222]' : 'text-[#bbb]'">{{
                                        getTitlesFromValues(10) ? getTitlesFromValues(10) : $t('请选择') }}</div>
                            </div>
                            <van-icon size="10" name="arrow-down" />
                        </div>
                    </div>
                    <div class="mt-8">
                        <div class="text-[12px] text-[#999] underline" @click="ishelp = !ishelp">{{ $t('测量帮助') }}</div>
                        <div v-show="ishelp">
                            <div class="mt-2">
                                <img src="@/assets/image/measure-size.png" class="w-[80%] mx-auto my-0" alt="">
                            </div>
                            <div class="mt-2 text-[#222]">
                                <h4 class="font-bold flex items-center">
                                    <span
                                        class="mr-4 h-[30px] w-[30px] rounded-full bg-[#222] flex justify-center items-center text-white">1</span>
                                    {{ $t('胸围') }}
                                </h4>
                                <p class="text-[12px]">{{ $t('把你的乳房的最大部分包裹在一个卷尺上，确保把胶带放在地上.') }}</p>
                            </div>
                            <div class="mt-2 text-[#222]">
                                <h4 class="font-bold flex items-center">
                                    <span
                                        class="mr-4 h-[30px] w-[30px] rounded-full bg-[#222] flex justify-center items-center text-white">2</span>
                                    {{ $t('腰围') }}
                                </h4>
                                <p class="text-[12px]">{{ $t('将自己的腰围上的卷尺缠绕起来，确保把磁带保持在地面上.') }}</p>
                            </div>
                            <div class="mt-2 text-[#222]">
                                <h4 class="font-bold flex items-center">
                                    <span
                                        class="mr-4 h-[30px] w-[30px] rounded-full bg-[#222] flex justify-center items-center text-white">3</span>
                                    {{ $t('臀围') }}
                                </h4>
                                <p class="text-[12px]">{{ $t('把你的全部的卷尺放在一起臀部，确保将胶带保留在地上.') }}</p>
                            </div>
                        </div>

                    </div>

                    <div class="my-10 flex justify-center items-center py-4 bg-black text-white font-bold"
                        @click="subBtn">{{ $t('提交')
                        }}
                    </div>
                </div>
            </div>
        </div>
        <van-popup v-model:show="showSelect" position="bottom" closeable
            :style="{ 'max-height': '80%', 'min-height': '20%' }">
            <div class="p-4 pb-[12px]">
                <div class="title w-full text-center text-[14px] text-[#999] pb-4 border-b border-borderColor">
                    {{ curlist.title }}</div>
                <div class="px-4 py-2 flex justify-between text-[12px] text-[#222]" v-for="item in curlist.content"
                    @click="userselect(item)">
                    <div>{{ item.title }}</div>
                    <van-icon v-if="ids.includes(item.id)" name="success" />
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import Navbar from "@/components/Navbar.vue";
import { computed, onMounted, reactive, ref } from "vue";
import { UserService } from "@/api/user";
import { useI18n } from 'vue-i18n';
import { showToast } from "vant";
import { useRouter } from "vue-router";
const { t } = useI18n();
const router = useRouter()
const showSelect = ref(false)
const ishelp = ref(false)
const curlist = ref({})
const loading = ref(false)
const paransInfo = ref({})
const ids = ref([])
const userselect = (item) => {
    if (paransInfo.value[currselect.value].value.length > 0) {
        let index = ids.value.indexOf(paransInfo.value[currselect.value].value[0]);
        if (index !== -1) {
            ids.value.splice(index, 1);
        }
    }
    paransInfo.value[currselect.value].value = []
    paransInfo.value[currselect.value].value.push(item.id)
    getTitlesFromValues(currselect.value)
    showSelect.value = false
}
const typeList = ref([])

const get = async () => {
    const res = await UserService.hobbyList()
    paransInfo.value = res.paransInfo
    typeList.value = res.typeList
    for (const key in paransInfo.value) {
        if (paransInfo.value[key].value.length > 0) { // 检查 value 是否不为空
            ids.value = ids.value.concat(paransInfo.value[key].value);
        }
    }
    loading.value = true;
}


function getTitlesFromValues(paransInfoKey) {
    let values; // 获取 paransInfo 中的 value
    if (paransInfo.value[paransInfoKey].value.length <= 0) return false
    values = paransInfo.value[paransInfoKey].value
    let ishas = ids.value.includes(values[0])
    console.log(values,ishas)
    if (!ishas) {
        ids.value.push(values[0])
    }
    // 创建一个标题数组，存放找到的标题
    let titles = '';
    // 遍历 values 数组，查找对应的标题
    values.forEach(value => {
        const item = typeList.value[paransInfoKey].content.find(contentItem => contentItem.id === value);
        if (item) {
            titles = item.title // 添加找到的标题
        }
    });
    return titles; // 返回找到的标题数组
}

const currselect = ref('')
const handlepopdata = (index) => {
    if (typeList.value) {
        showSelect.value = true
        curlist.value = typeList.value[index]
        currselect.value = index
    }

}
const subBtn = async () => {
    if (ids.value.length <= 0) return;
    const res = await UserService.hobby({ 'ids': ids.value.map(item => item).join(',') })
    showToast(t("修改成功"))
    router.back()
}
onMounted(async () => {
    await get()
    // 遍历 paransInfo 对象
    
    console.log(ids.value,"这是所有的id");
})
</script>

<style lang="less" scoped>
:deep(.van-popup__close-icon--top-right) {
    top: 5px !important;
    right: 3px;
    font-size: 16px;
}

:deep(.van-cell) {
    padding: 5px;
}
</style>