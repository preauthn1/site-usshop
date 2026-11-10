<template>
    <div class="login">
        <Navbar :title="$t('登录')" :is_title="true"></Navbar>
        <div class="mt-[44px]">
            <div class="flex flex-col w-full justify-center items-center">
                <div class="p-4">
                    <!-- <img src="@/assets/image/logo2.png" class="  object-contain" alt=""> -->
                    <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_26_5" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120"
                        height="24">
                        <rect width="120" height="24" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_26_5)">
                        <path
                            d="M26.3359 8.75879L23.3779 8.77148C23.3102 7.71354 22.9082 6.88411 22.1719 6.2832C21.444 5.68229 20.4665 5.38184 19.2393 5.38184C18.2236 5.38184 17.3942 5.61882 16.751 6.09277C16.1077 6.56673 15.7861 7.15495 15.7861 7.85742C15.7861 8.56836 16.0697 9.15234 16.6367 9.60938C17.2038 10.0579 18.3929 10.515 20.2041 10.9805C22.9548 11.6745 24.749 12.4277 25.5869 13.2402C26.4333 14.0527 26.8564 15.1868 26.8564 16.6426C26.8564 18.403 26.1963 19.8206 24.876 20.8955C23.5641 21.9619 21.8079 22.4951 19.6074 22.4951C17.2884 22.4951 15.4814 21.9365 14.1865 20.8193C12.9001 19.7021 12.2103 18.0941 12.1172 15.9951H15.1387C15.2233 17.2054 15.6761 18.1449 16.4971 18.8135C17.3265 19.4821 18.4479 19.8164 19.8613 19.8164C20.9277 19.8164 21.7995 19.5583 22.4766 19.042C23.1621 18.5257 23.5049 17.891 23.5049 17.1377C23.5049 16.8415 23.4076 16.5072 23.2129 16.1348C23.0267 15.7539 22.7855 15.4281 22.4893 15.1572C21.9984 14.7087 20.9066 14.277 19.2139 13.8623C18.8499 13.7692 18.5749 13.7015 18.3887 13.6592C16.2982 13.109 14.8213 12.4108 13.958 11.5645C13.0947 10.7096 12.6631 9.55013 12.6631 8.08594C12.6631 6.52865 13.2936 5.26335 14.5547 4.29004C15.8242 3.30827 17.5085 2.81738 19.6074 2.81738C21.6471 2.81738 23.2721 3.35482 24.4824 4.42969C25.7012 5.49609 26.319 6.93913 26.3359 8.75879ZM32.5739 3.2998H35.6843V10.6631H44.5329V3.2998H47.6432V22H44.5329V13.4434H35.6843V22H32.5739V3.2998ZM54.1732 3.2998H67.5795V6.04199H57.2328V10.9297H66.7796V13.5449H57.2328V19.1943H67.9222V22H54.1732V3.2998ZM73.5127 3.2998H86.919V6.04199H76.5723V10.9297H86.1192V13.5449H76.5723V19.1943H87.2618V22H73.5127V3.2998ZM92.8523 3.2998H96.153L104.862 17.29V3.2998H107.871V22H104.595L95.8357 8.00977V22H92.8523V3.2998Z"
                            fill="black" />
                    </g>
                </svg>
                </div>
                <div class="flex items-center">
                    <svg data-v-d6b814f2="" xmlns="http://www.w3.org/2000/svg" width="11" height="13"
                        viewBox="0 0 11 13" fill="none" class="page__login-lock">
                        <rect x="0.81875" y="4.83267" width="9.3625" height="7.02917" stroke="#198055"
                            stroke-width="1.1375"></rect>
                        <mask id="path-2-inside-1_1_8695" fill="white">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.66795 5.04181V3.68056C7.66795 2.48317 6.69728 1.5125 5.49989 1.5125C4.30251 1.5125 3.33184 2.48317 3.33184 3.68056V5.04181H2.19434V3.68056C2.19434 1.85495 3.67428 0.375 5.49989 0.375C7.3255 0.375 8.80545 1.85495 8.80545 3.68056V5.04181H7.66795Z">
                            </path>
                        </mask>
                        <path
                            d="M7.66795 5.04181H6.79295V5.91681H7.66795V5.04181ZM3.33184 5.04181V5.91681H4.20684V5.04181H3.33184ZM2.19434 5.04181H1.31934V5.91681H2.19434V5.04181ZM8.80545 5.04181V5.91681H9.68045V5.04181H8.80545ZM8.54295 5.04181V3.68056H6.79295V5.04181H8.54295ZM8.54295 3.68056C8.54295 1.99992 7.18053 0.6375 5.49989 0.6375V2.3875C6.21403 2.3875 6.79295 2.96642 6.79295 3.68056H8.54295ZM5.49989 0.6375C3.81926 0.6375 2.45684 1.99992 2.45684 3.68056H4.20684C4.20684 2.96642 4.78576 2.3875 5.49989 2.3875V0.6375ZM2.45684 3.68056V5.04181H4.20684V3.68056H2.45684ZM2.19434 5.91681H3.33184V4.16681H2.19434V5.91681ZM1.31934 3.68056V5.04181H3.06934V3.68056H1.31934ZM5.49989 -0.5C3.19103 -0.5 1.31934 1.3717 1.31934 3.68056H3.06934C3.06934 2.3382 4.15753 1.25 5.49989 1.25V-0.5ZM9.68045 3.68056C9.68045 1.3717 7.80875 -0.5 5.49989 -0.5V1.25C6.84225 1.25 7.93045 2.3382 7.93045 3.68056H9.68045ZM9.68045 5.04181V3.68056H7.93045V5.04181H9.68045ZM7.66795 5.91681H8.80545V4.16681H7.66795V5.91681Z"
                            fill="#198055" mask="url(#path-2-inside-1_1_8695)"></path>
                        <rect x="5.30566" y="6.59741" width="0.875" height="3.5" fill="#198055"></rect>
                    </svg>
                    <span class="pl-1 text-[12px] text-[#198055]">{{$t('你的资料已受到保护。')}}</span>
                </div>
            </div>
            <div class="p-4 text-[#767676] text-[14px]">
                <van-form @submit="onSubmit">
                    <div class="p-2">
                        <div class=" mb-2">{{$t('电子邮箱')}}</div>
                        <van-field :clearable="true" :rules="[{ required: true, message: $t('请填写电子邮箱') }]"
                            v-model="pageInfo.username" class="border py-2"
                            :class="border1 ? 'border-black' : 'border-borderColor'" :placeholder="$t('请输入邮箱')"
                            maxlength="30"
                            @blur="border1 = false" @focus="border1 = true" />
                    </div>
                    <div class="p-2">
                        <div class=" mb-2">{{$t('密码')}}</div>
                        <van-field :type="eyeClose?'password':'text'" :rules="[{ required: true, message: $t('请填写密码') }]"
                            v-model="pageInfo.password" class="border py-2"
                            :right-icon="eyeClose?'closed-eye':'eye-o'" @click-right-icon="eyeClose = !eyeClose"
                            :class="border2 ? 'border-black' : 'border-borderColor'" :placeholder="$t('请输入密码')"
                            maxlength="30"
                            @blur="border2 = false" @focus="border2 = true" />
                    </div>
                    <div class="p-2">
                        <van-button block type="primary" native-type="submit">
                            {{$t('登录')}}
                        </van-button>
                    </div>
                    <div class="p-2 flex justify-center w-full">
                        <div>{{$t('没有账号？')}}</div>
                        <div class="text-[#198055]" @click="router.replace('/register')">{{$t('去注册')}}</div>
                    </div>
                </van-form>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { UserService } from '@/api/user'
import { showToast } from 'vant';
import { useUserStore } from '@/store/userStore';
import { CartStore } from '@/store/cart';
const userStore = useUserStore();
const cartstore = CartStore()
const router = useRouter()
const border1 = ref(false)
const border2 = ref(false)
const eyeClose = ref(true);
const pageInfo = reactive({
    username: '',
    password: '',
})
const onSubmit = async () => {
    const res = await UserService.login({ "email": pageInfo.username, "password": pageInfo.password })
    userStore.token = res
    await userStore.getUserInfo()
    cartstore.clearCartStore()
    router.push('/');
}
</script>

<style lang="less" scoped></style>