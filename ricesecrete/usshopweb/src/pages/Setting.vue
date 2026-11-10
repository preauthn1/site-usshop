<template>
    <div class="setting bg-base-selector-color pb-[60px]">
        <Navbar :title="$t('设置')"></Navbar>
        <div class="mt-[44px] ">
            <div class="pt-4 w-full"></div>
            <div class="bg-white p-4 flex justify-between items-center" @click="router.push('/editprofile')">
                <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden font-semibold">{{userStore.userInfo.nick_name ? userStore.userInfo.nick_name:userStore.userInfo.email}}
                </div>
                <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 12px; height: 12px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                            fill="currentColor"></path>
                    </svg></div>
            </div>
            <!-- setting -->
            <div class="mt-4">
                <div class="p-4 bg-white">
                    <div class=" font-semibold text-[12px]">{{$t('设置')}}</div>
                    <div class="flex justify-between py-4 border-b border-borderColor items-center"
                        @click="router.push('/myaddress')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                           {{$t('地址簿')}}</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/changecurrency')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('更改货币')}}</div>
                        <div class="flex items-center">
                            <!-- <div class="text-[12px] pr-3">USD</div> -->
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/language')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('更改语言')}}</div>
                        <div class="flex items-center">
                           <!--  <div class="text-[12px] pr-3">English</div> -->
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                    <!-- <div class="flex justify-between py-4 border-b border-borderColor items-center">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            My Payment Options</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div> -->
                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/account')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('管理我的账户')}}</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                </div>
            </div>
            <!-- info -->
            <div class="mt-4">
                <div class="p-4 bg-white">
                    <div class=" font-semibold text-[12px]">{{$t('详情')}}</div>
                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/condition?type=1')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                           {{$t('运费')}}</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>

                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/condition?type=2')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('退货政策')}}</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-borderColor items-center" @click="router.push('/condition?type=3')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('隐私中心')}}
                        </div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                    <div class="flex justify-between py-4 items-center" @click="router.push('/condition?type=4')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('条款和条件')}}
                        </div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                    <div class="flex justify-between py-4 items-center" @click="router.push('/condition?type=5')">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            {{$t('关于我们')}}
                        </div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                </div>
            </div>
            <!-- <div class="mt-4">
                <div class="p-4 bg-white">
                    <div class="flex justify-between py-4 items-center">
                        <div class="text-[12px] w-3/4 text-ellipsis whitespace-nowrap overflow-hidden">
                            Switch accounts</div>
                        <div><svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                    fill="currentColor"></path>
                            </svg></div>
                    </div>
                </div>
            </div> -->
            <div class="mt-4">
                <div class="p-4 bg-white" @click="showSignout = true">
                    <div class="flex justify-center py-4 items-center">
                        <div class="text-[#bf4123] text-[16px] font-bold">{{$t('退出登录')}}</div>
                    </div>
                </div>
            </div>
        </div>
        <van-popup v-model:show="showSignout" position="bottom" :style="{ height: '23%' }">
            <div class="bg-base-selector-color">
                <div class="mb-4 bg-white">
                    <div class="border-b py-4 border-borderColor ">
                        <div class="flex justify-center text-[12px] text-[#999]">{{$t('您确定要退出吗？')}}</div>
                    </div>
                    <div class="p-4 flex justify-center text-[14px]" @click="returnUser">
                        {{$t('退出登录')}}
                    </div>
                </div>

                <div class="bg-white p-4 flex justify-center text-[14px]" @click="showSignout = false">{{$t('取消')}}</div>
            </div>

        </van-popup>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '@/components/Navbar.vue';
import { useRouter } from 'vue-router';
const router = useRouter()
const showSignout = ref(false)
import { useUserStore } from '@/store/userStore';
const userStore = useUserStore();
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const returnUser = () => {
    userStore.deleteToken();
    router.push("/login");
}
</script>

<style lang="less" scoped></style>