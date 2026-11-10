<template>
    <div class="deleteaccount">
        <Navbar :title="$t('删除账户')"/>
        <div class="mt-[44px]">
            <div class="p-4">
                <h2 class="text-center py-2 px-0 pb-[8px] font-bold text-[#222] text-[16px]">{{$t('申请删除账户')}}</h2>
                <p class="text-center mb-3 text-[#666] text-[12px]">{{$t('在處理帳戶刪除申請之前，SHEIN 將進行以下驗證：')}}</p>
                <p class="text-center mb-3 text-[#666] text-[12px]">{{$t('注意：帳戶一旦刪除就無法恢復。')}}</p>
                <ul class=" px-2 text-[12px]">
                    <li class=" py-4 px-3 pr-[56px] mb-3 relative identity">
                        <p class="text-[12px] mb-1 text-[#222]">{{$t('身份验证')}}</p>
                        <p class="text-[#999] text-[12px]">{{$t('此账户的所有权已经过验证')}}</p>
                    </li>
                    <li class=" py-4 px-3 pr-[56px] mb-3 relative order">
                        <p class="text-[12px] mb-1 text-[#222]">{{$t('账户订单已完成')}}</p>
                        <p class="text-[#999] text-[12px]">{{$t('此账户没有挂单或退货')}}</p>
                    </li>
                    <li class=" py-4 px-3 pr-[56px] mb-3 relative wallet">
                        <p class="text-[12px] mb-1 text-[#222]">{{$t('钱包资金结算')}}</p>
                        <p class="text-[#999] text-[12px]">{{$t('此账户没有钱包余额')}}</p>
                    </li>
                </ul>
            </div>
            <div class="my-10 w-[90%] mx-auto flex justify-center items-center py-4 bg-black text-white font-bold">申请删除</div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
</script>

<style lang="less" scoped>
.identity::after{
    width: 38px;
    height: 38px;
    display: block;
    content: "";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-size: contain;
    right: 10px;
    background-image: url(@/assets/image/deleteAccoung/icon-safe-8df52d46c3.png);
}
.order::after{
    width: 38px;
    height: 38px;
    display: block;
    content: "";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-size: contain;
    right: 10px;
    background-image: url('@/assets/image/deleteAccoung/icon-order-a393f18dfa.png');
}
.wallet::after{
    width: 38px;
    height: 38px;
    display: block;
    content: "";
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-size: contain;
    right: 10px;
    background-image: url('@/assets/image/deleteAccoung/icon-wallet-0eb0cd19ce.png');
}
</style>