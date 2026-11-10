<template>
    <div class="addaddress h-screen" :class="true ? 'bg-base-selector-color' : ''">
        <Navbar :title="route.query.cate == 2 ? $t('添加新地址') : $t('配送地址')" />
        <div class="h-[44px]"></div>
        <div v-if="route.query.cate == 2">
            <van-steps :active="active">
                <van-step>{{ $t('注册') }}</van-step>
                <van-step>{{ $t('配送地址') }}</van-step>
                <van-step>{{ $t('下单') }}</van-step>
            </van-steps>
        </div>
        <div>
            <div class="pt-4"></div>
            <div v-if="false">
                <van-skeleton>
                    <template #template>
                        <div class="w-full" v-for="item in 8">
                            <van-skeleton-paragraph class="py-10 mt-5 w-full" />
                        </div>
                    </template>
                </van-skeleton>
            </div>
            <div class="bg-white p-4 ">
                <van-form @failed="onFailed">
                    <van-cell-group inset>
                        <div class="border p-2 border-borderColor">
                            <div class="text-[12px] text-[#666666]">{{ $t('姓名') }}*</div>
                            <van-field v-model="form.name" :placeholder="$t('请填写真实姓名')" />
                        </div>
                    </van-cell-group>
                    <van-cell-group inset>
                        <div class="border mt-4 p-2 border-borderColor">
                            <div class="text-[12px] text-[#666666]">{{ $t('电话号码') }}*</div>
                            <van-field v-model="form.mobile" type="number" class="phone" :placeholder="$t('请填写电话号码')">
                                <!-- <template #button>
                                    <div class="border-r border-borderColor text-[12px] pr-2">TW +886</div>
                                </template> -->
                            </van-field>
                        </div>
                    </van-cell-group>
                </van-form>

            </div>
            <div class="bg-white p-4 mt-4">
                <van-form @failed="onFailed">
                    <van-cell-group inset>
                        <div class="border p-2 border-borderColor">
                            <div class="text-[12px] text-[#666666]">{{ $t('地址') }}*</div>
                            <van-field v-model="form.province" :placeholder="$t('请填写地址')" />
                        </div>
                    </van-cell-group>
                    <van-cell-group inset class="">
                        <div class="border mt-4 p-2 border-borderColor">
                            <div class="text-[12px] text-[#666666]">{{ $t('详细地址') }}*</div>
                            <van-field v-model="form.address" :placeholder="$t('请填写详细地址')" />
                        </div>
                    </van-cell-group>

                </van-form>
            </div>
            <div class="bg-white p-4 mt-4">
                <div class="flex justify-between items-center ">
                    <div class="text-[14px]">{{ $t('设为默认') }}</div>
                    <van-switch v-model="form.is_default" size="20px" active-color="#000000" />
                </div>
            </div>

            <div class="bg-white p-4 mt-4">
                <div class="flex text-[12px] text-[#198055] items-center ">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 16px; height: 16px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.44443 8.44415V12.4442H7.55554V8.44415H8.44443Z" fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.77778 1.33301C9.86419 1.33301 11.5556 3.02438 11.5556 5.11079V5.77747H13.7777V15.1108H1.77771V5.77747H4V5.11078C4 3.02437 5.69137 1.33301 7.77778 1.33301ZM10.5556 5.77747V5.11079C10.5556 3.57666 9.3119 2.33301 7.77778 2.33301C6.24365 2.33301 5 3.57666 5 5.11078V5.77747H10.5556ZM12.7777 6.77747H2.77771V14.1108H12.7777V6.77747Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <span>{{ $t('安全与隐私') }}</span>
                </div>
                <div class="text-base-text-color-gray text-[12px] mt-2">{{ $t('我们采用行业标准的物理、技术和管理措施来保护您的个人信息。') }}</div>
            </div>

            <div class="my-10 p-4">
                <div class="flex justify-center items-center py-4 bg-black text-white font-bold" @click="saveAction">{{
                    $t('保存') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onActivated, ref } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { UserService } from '@/api/user'
import { useUserStore } from '@/store/userStore';
const store = useUserStore();
const route = useRoute()
const router = useRouter()
const checked = ref(false)
const active = ref(1);
const saveAction = () => {
    addMyAddress();
}
const addressData = ref({});

const form = ref({});
const addMyAddress = async () => {
    const res = await UserService.addressSave(form.value);
    await store.getUserInfo()
    if (route.query.product_ids) {
        router.replace(`/confirmorder?product_ids=${route.query.product_ids}`);
        return;
    }
    router.back()

}
onActivated(() => {
    console.log("走了这里")
    if (route.query.data) {
        addressData.value = JSON.parse(route.query.data);
        form.value = {
            id: addressData.value.id ?? '',
            name: addressData.value.name ?? '',
            mobile: addressData.value.mobile ?? '',
            province: addressData.value.province ?? '',
            city: "city",
            area: "area",
            address: addressData.value.address ?? '',
            is_default: addressData.value.is_default ?? false,
        }
    } else {
        addressData.value = {}
        form.value = {
            id: '',
            name: '',
            mobile: '',
            province: '',
            city: "city",
            area: "area",
            address: '',
            is_default: false,
        }
    }
})
</script>

<style lang="less" scoped>
:deep(.van-skeleton) {
    display: block;
}

:deep(.van-cell) {
    padding: 0px 0px;
}

:deep(.van-cell-group--inset) {
    margin: 0;
    border-radius: 0;
}

.phone {
    :deep(.van-field__body) {
        display: flex;
        align-items: center;
        align-items: center;
        justify-content: start;
        flex-direction: row-reverse;
        gap: 5px;
    }
}

:deep(.van-field__button) {
    padding-left: 0;
}
</style>