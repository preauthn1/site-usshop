<template>
    <div class="editprofile bg-base-selector-color h-screen">
        <Navbar :title="$t('编辑个人资料')"></Navbar>
        <div class="mt-[44px]">
            <div class="p-4 flex items-center justify-between bg-white" @click="router.push('/nickname')">
                <div class="text-[14px]">{{$t('昵称')}}
                    <em class="text-[red]">*</em>
                </div>
                <div class="flex items-center text-[10px]">
                    <div class="max-w-[150px] text-ellipsis overflow-hidden whitespace-nowrap">{{store.userInfo.nick_name}}</div>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 12px; height: 12px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
            </div>
            <div class="p-4 text-base-text-color-gray text-[10px]">{{$t('以下内容将予以保密。')}}
            </div>
            <div class="bg-white">
                <div class="p-4 flex items-center justify-between border-b border-borderColor" @click="router.push('/preference')">
                    <div class="text-[14px]">{{$t('偏好')}}</div>
                    <div class="flex items-center text-[10px]">
                        <!-- <div class="max-w-[150px] text-ellipsis overflow-hidden whitespace-nowrap">{{$t('品类、风格等')}}</div> -->
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 12px; height: 12px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </div>

                <div class="p-4 flex items-center justify-between" @click="router.push('/size')">
                    <div class="text-[14px]">{{$t('尺寸')}}</div>
                    <div class="flex items-center text-[10px]">
                        <!-- <div class="max-w-[150px] text-ellipsis overflow-hidden whitespace-nowrap">{{$t('品类、风格等')}}</div> -->
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 12px; height: 12px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
            </div>
           <!--  <div class="bg-white mt-4">
                <div class="p-4 flex items-center justify-between">
                    <div class="text-[14px]">Student Verification</div>
                    <div class="flex items-center text-[10px]">
                        <div class="max-w-[150px] text-ellipsis overflow-hidden whitespace-nowrap">Special Discount
                        </div>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 12px; height: 12px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/store/userStore';
const store = useUserStore()

const router = useRouter();

</script>