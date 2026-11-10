<template>
    <div class="email">
        <Navbar :title="$t('验证电子邮箱')" />
        <div class="mt-[44px]">
            <div class="py-[44px] px-[24px]">
                <h2 class="text-[#222] font-bold text-[17px] pb-3">{{$t('验证码')}}</h2>
                <div class="text-[#959595] text-[13px]">
                    <span> {{$t('验证码已发送至邮箱*0@Gmail.com',{mail:'123QQ.com'})}}</span>
                    <span class="pl-[10px] text-[#222] font-bold" :class="count > 0 ? '' : 'text-[#2d68a8]'"
                        @click="startCountdown">{{ count > 0 ? count + 's' : $t('重新发送')}}</span>
                </div>

                <div class="my-[24px]">
                    <div class="verification-container">
                        <input v-for="(code, index) in verificationCodes" :key="index"
                            v-model="verificationCodes[index]" @input="handleInput(index, $event)"
                            @keydown="handleKeyDown(index, $event)" maxlength="1" class="verification-input" />
                    </div>
                    <p class="text-center text-[#2d68a8] text-[12px] mt-10" @click="verify = true">{{$t('收不到验证码?')}}</p>
                </div>
            </div>
        </div>
        <van-popup v-model:show="verify" position="bottom" closeable :style="{ 'height': '40%' }">
            <div class="title text-center px-4 text-base-text-color-gray text-[14px] py-4 border-b border-borderColor">{{$t('收不到验证码?')}}</div>
            <div class="p-4">
                <div class="text-[14px] text-[#222]">{{$t('1. 请检查您的邮箱，确认是否收到了包含6位数字的验证码。 如果没有，请检查您的垃圾邮件文件夹。')}}</div>
                <div class="mt-2 text-[14px] text-[#222]">{{$t('2. 点击“重新发送”按钮，将重新发送验证码到 x*0@gmail.com',{mail:'123qq.com'})}}</div>
            </div>
            <div class="my-10 w-[90%] mx-auto flex justify-center items-center py-4 bg-black text-white font-bold" @click="verify = false">{{$t('好')}}</div>
        </van-popup>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import { ref, defineProps, defineEmits, nextTick } from 'vue';
const emits = defineEmits(['emailCode']);
const verificationCodes = ref(['', '', '', '', '', '']);
let timer;
let count = ref(60);
const verify = ref(false);
const updateCountdown = () => {
    if (count.value > 0) {
        count.value--;
    } else {
        clearInterval(timer);
    }
}
const startCountdown = () => {
    
    count.value = 60; // 重置倒计时
    updateCountdown(); // 显示初始值
    if (timer) {
        clearInterval(timer); // 清除之前的计时器
    }
    timer = setInterval(updateCountdown, 1000); // 每秒更新
}
startCountdown()
const handleInput = (index, event) => {
    const value = event.target.value;
    verificationCodes.value[index] = value;

    // 判断是否输入完成
    if (verificationCodes.value.join('').length === 6) {
        emits('emailCode', verificationCodes.value.join(''));
    }

    // 自动跳到下一个输入框
    if (value && index < verificationCodes.value.length - 1) {
        const nextInput = event.target.nextElementSibling;
        if (nextInput) {
            nextTick(() => {
                nextInput.focus();
            });
        }
    }
};
const handleKeyDown = (index, event) => {
    // 处理删除操作
    if (event.key === 'Backspace' && !event.target.value && index > 0) {
        const prevInput = event.target.previousElementSibling;
        if (prevInput) {
            nextTick(() => {
                prevInput.focus();
            });
        }
    }
};
</script>
<style lang="less" scoped>
:deep(.van-popup__close-icon--top-right) {
    top: 5px !important;
    right: 3px;
    font-size: 16px;
}

.verification-container {
    display: flex;
    justify-content: space-between;
}

.verification-input {
    width: 45px;
    height: 45px;
    margin-right: 10px;
    text-align: center;
    font-size: 20px;
    border: 1px solid #ebebeb;
    border-radius: 5px;

    &:last-child {
        margin-right: 0;
    }
}

.verification-input:focus {
    outline: none;
    // border-color: #007bff;
    // box-shadow: 0 0 5px #007bff;
}
</style>