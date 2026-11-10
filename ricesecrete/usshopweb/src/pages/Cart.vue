<template>
    <div class="pb-[80px]">
        <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0" title="标题" left-text="返回" z-index="2" fixed
            left-arrow @click-left="router.back()" @click-right="router.replace('/wishlist')">
            <template #right>
                <span class="pr-6" v-show="total[0]>0" @click.stop="handleclear">
                    <svg t="1739191009770" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2150" width="22" height="22">
                        <path
                            d="M574.577778 739.555556V381.155556c0-39.822222 62.577778-39.822222 62.577778 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577778 0z m-193.422222 0V381.155556c0-39.822222 62.577778-39.822222 62.577777 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577777 0z m551.822222-517.688889h-164.977778V159.288889c0-51.2-45.511111-96.711111-96.711111-96.711111H347.022222c-28.444444 0-51.2 11.377778-68.266666 28.444444-17.066667 17.066667-28.444444 45.511111-28.444445 68.266667v62.577778H85.333333C45.511111 221.866667 45.511111 284.444444 85.333333 284.444444h847.644445c45.511111 0 45.511111-62.577778 0-62.577777zM318.577778 153.6c0-17.066667 17.066667-34.133333 34.133333-34.133333h324.266667c17.066667 0 34.133333 11.377778 34.133333 34.133333v62.577778H318.577778V153.6zM739.555556 967.111111H284.444444c-28.444444 0-51.2-11.377778-68.266666-28.444444-17.066667-17.066667-28.444444-45.511111-28.444445-68.266667V381.155556c0-17.066667 17.066667-34.133333 34.133334-34.133334s34.133333 11.377778 34.133333 34.133334v489.244444c0 17.066667 17.066667 34.133333 34.133333 34.133333h455.111111c17.066667 0 34.133333-17.066667 34.133334-34.133333V386.844444c0-39.822222 62.577778-39.822222 62.577778 0v489.244445c-5.688889 45.511111-45.511111 91.022222-102.4 91.022222z"
                            fill="#000000" p-id="2151"></path>
                    </svg>
                </span>

                <van-icon name="like-o" size="24" />
            </template>
            <template #left>
                <div class="flex items-center">
                    <van-icon name="arrow-left" size="20" />
                    <span class="text-[17px] font-bold ml-[16px]">{{ $t('购物车') }}</span>
                    <!-- <div class="ml-[8px] pl-[8px] pr-[8px] bg-[#F6F6F6] rounded-[20px]">
                        <van-icon class="" name="location-o" size="10" />
                        <span class="ml-[5px] text-[10px] text-[#666] leading">Ship to Singapore</span>
                    </div> -->
                </div>
            </template>
        </van-nav-bar>
        <div class="mt-[64px] flex flex-col items-center pt-[24px] pb-[24px] ml-[16px] mr-[16px]"
            v-if="!cartlist.length > 0">
            <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg"
                style="width:75px;height:75px;" data-v-024e52e8="">
                <path
                    d="M58.4918 10.475C59.08 10.475 59.5568 9.9965 59.5568 9.40625C59.5568 8.816 59.08 8.3375 58.4918 8.3375C57.9036 8.3375 57.4268 8.816 57.4268 9.40625C57.4268 9.9965 57.9036 10.475 58.4918 10.475ZM58.4918 11.1875C57.5115 11.1875 56.7168 10.39 56.7168 9.40625C56.7168 8.42249 57.5115 7.625 58.4918 7.625C59.4721 7.625 60.2668 8.42249 60.2668 9.40625C60.2668 10.39 59.4721 11.1875 58.4918 11.1875Z"
                    fill="#999999" data-v-024e52e8=""></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M28.6249 11.6875C28.9516 11.6875 29.2165 11.4217 29.2165 11.0938C29.2165 10.7658 28.9516 10.5 28.6249 10.5C28.2981 10.5 28.0332 10.7658 28.0332 11.0938C28.0332 11.4217 28.2981 11.6875 28.6249 11.6875Z"
                    fill="white" stroke="#CCCCCC" stroke-width="0.5" data-v-024e52e8=""></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M20.9923 24.854L21.8183 31.5199C22.0251 31.4116 22.2611 31.35 22.5129 31.35H56.0163C56.9192 31.35 57.6175 32.1419 57.5045 33.0378L54.8484 54.0866C54.7538 54.8365 54.116 55.3988 53.3602 55.3988H25.169C24.4132 55.3988 23.7754 54.8365 23.6808 54.0866L22.408 43.9999H22.3571L19.9999 24.9769C19.9378 24.476 19.5122 24.0999 19.0075 24.0999H3.00012V23.0999H19.0075C20.017 23.0999 20.8682 23.8522 20.9923 24.854ZM22.5129 32.35C22.2119 32.35 21.9792 32.614 22.0169 32.9126L24.6729 53.9614C24.7044 54.2114 24.917 54.3988 25.169 54.3988H53.3602C53.6122 54.3988 53.8247 54.2114 53.8563 53.9614L56.5123 32.9126C56.55 32.614 56.3173 32.35 56.0163 32.35H22.5129ZM26.4997 63C25.6713 63 24.9998 63.6716 24.9998 64.5C24.9998 65.3284 25.6713 66 26.4997 66C27.3281 66 27.9996 65.3284 27.9996 64.5C27.9996 63.6716 27.3281 63 26.4997 63ZM23.9998 64.5C23.9998 63.1193 25.119 62 26.4997 62C27.8804 62 28.9996 63.1193 28.9996 64.5C28.9996 65.8807 27.8804 67 26.4997 67C25.119 67 23.9998 65.8807 23.9998 64.5ZM51.9997 67H68.8583C69.1344 67 69.3583 67.2239 69.3583 67.5C69.3583 67.7761 69.1344 68 68.8583 68H8.5083C8.23216 68 8.0083 67.7761 8.0083 67.5C8.0083 67.2239 8.23216 67 8.5083 67H26.4997H51.9997ZM51.9997 67C50.619 67 49.4998 65.8807 49.4998 64.5C49.4998 63.1193 50.619 62 51.9997 62C53.3804 62 54.4996 63.1193 54.4996 64.5C54.4996 65.8807 53.3804 67 51.9997 67ZM50.4998 64.5C50.4998 63.6716 51.1713 63 51.9997 63C52.8281 63 53.4996 63.6716 53.4996 64.5C53.4996 65.3284 52.8281 66 51.9997 66C51.1713 66 50.4998 65.3284 50.4998 64.5ZM2.5918 67C2.31565 67 2.0918 67.2239 2.0918 67.5C2.0918 67.7761 2.31565 68 2.5918 68H6.28747C6.56361 68 6.78747 67.7761 6.78747 67.5C6.78747 67.2239 6.56361 67 6.28747 67H2.5918ZM71.3164 67.5C71.3164 67.2239 71.5403 67 71.8164 67H72.9997C73.2759 67 73.4997 67.2239 73.4997 67.5C73.4997 67.7761 73.2759 68 72.9997 68H71.8164C71.5403 68 71.3164 67.7761 71.3164 67.5Z"
                    fill="#666666" data-v-024e52e8=""></path>
                <path d="M54.8 35L52.5726 52" stroke="#E1E1E1" stroke-linecap="round" stroke-linejoin="round"
                    data-v-024e52e8=""></path>
                <path d="M13.7949 25.5L18.833 25.5" stroke="#E1E1E1" stroke-linecap="round" stroke-linejoin="round"
                    data-v-024e52e8=""></path>
                <path
                    d="M37.8417 22.2162C38.0182 22.31 38.0825 22.5343 37.9852 22.7172L37.6487 23.3495L38.2982 23.6951C38.4612 23.7817 38.5285 23.9795 38.4614 24.1533L38.4418 24.1962C38.3446 24.379 38.1227 24.4512 37.9461 24.3573L37.2967 24.0116L36.9289 24.7038C36.8392 24.8726 36.6432 24.9471 36.4747 24.8837L36.4332 24.865C36.2567 24.7711 36.1924 24.5468 36.2897 24.3639L36.6576 23.6718L35.9366 23.2888C35.7736 23.2021 35.7063 23.0043 35.7734 22.8306L35.793 22.7877C35.8902 22.6049 36.1121 22.5327 36.2887 22.6266L37.0096 23.0097L37.346 22.3773C37.4357 22.2085 37.6317 22.1341 37.8002 22.1974L37.8417 22.2162Z"
                    fill="#999999" data-v-024e52e8=""></path>
                <path opacity="0.7"
                    d="M48.288 15.7511C48.5316 15.9783 48.5384 16.3672 48.303 16.6196L47.4886 17.4928L48.385 18.3289C48.6135 18.542 48.6337 18.8971 48.4413 19.1486L48.4 19.1975C48.1646 19.45 47.7762 19.4704 47.5325 19.2431L46.6361 18.407L45.7455 19.3622C45.5248 19.5989 45.1696 19.6316 44.9253 19.4476L44.878 19.4078C44.6343 19.1806 44.6275 18.7917 44.863 18.5393L45.7535 17.5839L44.7583 16.6561C44.5298 16.443 44.5096 16.0879 44.702 15.8364L44.7433 15.7875C44.9787 15.5351 45.3671 15.5146 45.6108 15.7419L46.606 16.6697L47.4205 15.7967C47.6412 15.56 47.9963 15.5273 48.2406 15.7114L48.288 15.7511Z"
                    fill="#999999" data-v-024e52e8=""></path>
            </svg>
            <div class="mt-[10px]">
                <span class="text-[16px] font-bold">
                    {{ $t('您的购物车目前是空的') }}
                </span>
            </div>
            <div class="mt-[10px] w-full">
                <van-button type="primary" size="small" block class="font-bold text-[16px]"
                    @click="router.push('/choosegoods')">
                    {{ $t('立即购买') }}
                </van-button>
            </div>
        </div>
        <div class="bg-[#F6F6F6] w-full" :class="cartlist.length > 0 ? 'mt-[64px]' : ''">
            <div class="pt-[10px] pl-[10px] pr-[10px]" v-for="item in cartlist">
                <div class="bg-[#fff] p-[10px]">
                    <div class="flex justify-between items-center">
                        <div class="px-4" @click="handlesele(item.id)">
                            <span v-if="cartid.includes(item.id)">
                                <svg t="1740200235204" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="2960" width="30" height="30">
                                    <path
                                        d="M512 922.182057c-226.53748 0-410.18308-183.644577-410.18308-410.18308S285.46252 101.81692 512 101.81692s410.18308 183.644577 410.18308 410.18308S738.53748 922.182057 512 922.182057zM729.058594 327.418075 466.190675 594.737376 315.981604 441.983343l-50.091862 51.280944 199.931518 203.317639 312.797076-318.094732L729.058594 327.418075z"
                                        fill="#020202" p-id="2961"></path>
                                </svg>
                            </span>
                            <span v-else>
                                <svg t="1740142639004" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="2463" width="30" height="30">
                                    <path
                                        d="M511.671007 127.934764c-211.93183 0-383.745964 171.803901-383.745964 383.745964s171.814134 383.743918 383.745964 383.743918c211.951273 0 383.764384-171.802878 383.764384-383.743918S723.62228 127.934764 511.671007 127.934764zM511.671007 855.032704c-189.624778 0-343.332533-153.707754-343.332533-343.341742 0-189.644221 153.707754-343.362208 343.332533-343.362208 189.643198 0 343.370395 153.716964 343.370395 343.362208C855.040379 701.32495 701.314205 855.032704 511.671007 855.032704z"
                                        fill="#CCCCCC" p-id="2464"></path>
                                </svg>
                            </span>
                        </div>
                        <!-- <img class="h-[80px]" :src="item?.img"> -->
                        <van-image height="80px" :src="item?.img" @click.stop = "router.push(`/goodsdetail?id=${item.product_id}`)"/>
                        <div class="flex flex-col pl-[10px] flex-1 " style="max-width: 100%; overflow: hidden;">
                            <div class="flex " @click.stop = "router.push(`/goodsdetail?id=${item.product_id}`)">
                                <span class="text-[12px] textline-2">
                                    {{ item?.title }}
                                </span>
                            </div>
                            <div class="flex pb-2">
                                <div class="h-[20px] rounded-[10px] border-[1px] bg-[#f6f6f6] flex items-center pl-[5px] pr-[5px] justify-between"
                                    @click="handledata(item)">
                                    <span
                                        class="text-[#666] text-[10px] ml-[5px] max-w-[150px] text-ellipsis whitespace-nowrap overflow-hidden">{{
                                            item.spec_name
                                        }}</span>
                                    <van-icon name="arrow-down" class="ml-[5px]" size="14" />
                                </div>
                            </div>
                            <div>
                                <!-- <van-swipe style="height: 20px;" :autoplay="3000" :show-indicators="false"
                                                vertical>
                                                <van-swipe-item v-for="item in 10" :key="image">
                                                    <template #default>
                                                        <div class="flex items-center">
                                                            <van-icon name="hot" size="10" color="#fa6338" />
                                                            <span class="text-[10px] text-[#fa6338] ml-[5px]">10%
                                                                Cashback</span>
                                                        </div>
                                                    </template>
                                    </van-swipe-item>
                                </van-swipe> -->
                            </div>
                            <div class="flex justify-between items-center relative">
                                <span class="text-[12px] font-bold text-[#fa6338]">{{ $sysCurrency }}{{ item?.price
                                    }}</span>
                                <div class="relative">
                                    <van-stepper :disable-input="true" @plus="add(item)" :max="item.store" @minus="decrease(item)"
                                        v-model="item.number" integer input-width="23px" button-size="23px"
                                        class=" relative" />
                                    <span class=" absolute top-[4px] left-0 z-[1] bg-base-selector-color px-2"
                                        @click="decrease(item, 1)" v-if="item.number == 1">
                                        <svg t="1739191009770" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" p-id="2150" width="16" height="16">
                                            <path
                                                d="M574.577778 739.555556V381.155556c0-39.822222 62.577778-39.822222 62.577778 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577778 0z m-193.422222 0V381.155556c0-39.822222 62.577778-39.822222 62.577777 0V739.555556c5.688889 39.822222-62.577778 39.822222-62.577777 0z m551.822222-517.688889h-164.977778V159.288889c0-51.2-45.511111-96.711111-96.711111-96.711111H347.022222c-28.444444 0-51.2 11.377778-68.266666 28.444444-17.066667 17.066667-28.444444 45.511111-28.444445 68.266667v62.577778H85.333333C45.511111 221.866667 45.511111 284.444444 85.333333 284.444444h847.644445c45.511111 0 45.511111-62.577778 0-62.577777zM318.577778 153.6c0-17.066667 17.066667-34.133333 34.133333-34.133333h324.266667c17.066667 0 34.133333 11.377778 34.133333 34.133333v62.577778H318.577778V153.6zM739.555556 967.111111H284.444444c-28.444444 0-51.2-11.377778-68.266666-28.444444-17.066667-17.066667-28.444444-45.511111-28.444445-68.266667V381.155556c0-17.066667 17.066667-34.133333 34.133334-34.133334s34.133333 11.377778 34.133333 34.133334v489.244444c0 17.066667 17.066667 34.133333 34.133333 34.133333h455.111111c17.066667 0 34.133333-17.066667 34.133334-34.133333V386.844444c0-39.822222 62.577778-39.822222 62.577778 0v489.244445c-5.688889 45.511111-45.511111 91.022222-102.4 91.022222z"
                                                fill="#999999" p-id="2151"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#F6F6F6] h-[10px]"></div>
        <div class="block pl-[10px] pr-[10px] pt-[20px] pb-[70px]">
            <div class="text-center">
                <span class="text-center text-[12px] font-bold">
                    {{ $t('你可能想填满它') }}
                </span>
            </div>
            <van-list v-model:loading="loading" :finished="finished" loading-text="loading" finished-text="没有更多了"
                @load="onLoad">
                <div class="flex justify-between w-full pt-[16px] pb-[70px]">
                    <div class="w-[50%] pr-[5px]">
                        <div v-for="(item, index) in grouplist[0]" class="pb-[10px]"
                            @click="router.push(`/goodsdetail?id=${item.id}`)">
                            <img :src="item.img" alt="" class="rounded-[2px] object-cover">
                            <div class="flex flex-col w-full">
                                <div class="flex items-center">
                                    <span v-if="item.cashBack"
                                        class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                            item.cashBack }}</span>
                                    <span
                                        class="ml-[3px] text-[12px] whitespace-nowrap overflow-hidden text-ellipsis">{{
                                            item.title
                                        }}</span>
                                </div>
                                <div v-if="item.score > 0">
                                    <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00"
                                        allow-half />
                                </div>
                                <div class="flex items-center mt-[5px]">
                                    <span class="text-[12px] font-bold text-[#fa6338]"
                                        :style="{ color: item.cashBack ? '#fa6338' : '#222' }">{{ $sysCurrency }}{{
                                        item.min_price
                                        }}</span>
                                    <span class="ml-[2px] text-[#767676] text-[8px]" v-if='item.buy > 0'>{{ item.buy }}+
                                        {{ $t('出售') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-[50%] pl-[5px]">
                        <div v-for="(item, index) in grouplist[1]" class="mb-[10px]"
                            @click="router.push(`/goodsdetail?id=${item.id}`)">
                            <img :src="item.img" alt="" class="rounded-[2px] object-cover">
                            <div class="flex flex-col w-full">
                                <div class="flex items-center">
                                    <span v-if="item.cashBack"
                                        class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                            item.cashBack }}</span>
                                    <span
                                        class="ml-[3px] text-[12px] whitespace-nowrap overflow-hidden text-ellipsis">{{
                                            item.title
                                        }}</span>
                                </div>
                                <!-- <div v-if="item.score > 0">
                                    <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00"
                                        allow-half />
                                </div> -->
                                <div class="flex items-center mt-[5px]">
                                    <span class="text-[12px] font-bold text-[#fa6338]"
                                        :style="{ color: item.cashBack ? '#fa6338' : '#222' }">{{ $sysCurrency }}{{
                                        item.min_price
                                        }}</span>
                                    <span class="ml-[2px] text-[#767676] text-[8px]" v-if='item.buy > 0'>{{ item.buy }}+
                                        {{ $t('出售') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </van-list>
        </div>
        <van-submit-bar class="max-w-[425px] my-0 mx-auto bottom-0 left-0 right-0" :price="total[0] * 100"
            :currency="`${$sysCurrency.value}`" label=" " button-text="提交订单" @submit="onSubmit">
            <!-- <van-checkbox v-model="checked">All</van-checkbox> -->
            <div class="flex items-center" @click="allselect">
                <span v-if="all">
                    <svg t="1740200235204" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2960" width="30" height="30">
                        <path
                            d="M512 922.182057c-226.53748 0-410.18308-183.644577-410.18308-410.18308S285.46252 101.81692 512 101.81692s410.18308 183.644577 410.18308 410.18308S738.53748 922.182057 512 922.182057zM729.058594 327.418075 466.190675 594.737376 315.981604 441.983343l-50.091862 51.280944 199.931518 203.317639 312.797076-318.094732L729.058594 327.418075z"
                            fill="#020202" p-id="2961"></path>
                    </svg>
                </span>
                <span v-else>
                    <svg t="1740142639004" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="2463" width="30" height="30">
                        <path
                            d="M511.671007 127.934764c-211.93183 0-383.745964 171.803901-383.745964 383.745964s171.814134 383.743918 383.745964 383.743918c211.951273 0 383.764384-171.802878 383.764384-383.743918S723.62228 127.934764 511.671007 127.934764zM511.671007 855.032704c-189.624778 0-343.332533-153.707754-343.332533-343.341742 0-189.644221 153.707754-343.362208 343.332533-343.362208 189.643198 0 343.370395 153.716964 343.370395 343.362208C855.040379 701.32495 701.314205 855.032704 511.671007 855.032704z"
                            fill="#CCCCCC" p-id="2464"></path>
                    </svg>
                </span>
                <div class="text-[14px] text-[#222]">{{ $t('全部') }}</div>
            </div>
            <!-- <template #tip>
                你的收货地址不支持配送, <span @click="onClickLink">修改地址</span>
            </template> -->
            <template #button>
                <van-button type="primary" size="small" class="rounded-[0px] font-bold text-[12px]" @click="onSubmit">{{
                    $t('结账（{num}）', { num: total[1] }) }}</van-button>

            </template>
        </van-submit-bar>

        <Cartdetail :show="opendetail" @closedetail="closedetail" :content="detaildata"></Cartdetail>
    </div>
</template>
<script setup>
import { onMounted, ref, computed, reactive, onActivated } from 'vue';
import { useRouter } from 'vue-router';
import { AuthService } from '@/api/home';
import { ShopService } from '@/api/shop';
import Cartdetail from '@/components/cartdetail.vue';
import { useUserStore } from '@/store/userStore';
import { CartStore } from '@/store/cart';
import { showToast } from 'vant';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const cartStore = CartStore()
const store = useUserStore()
const router = useRouter()
const cartlist = ref([])
const opendetail = ref(false)
const loading = ref(false);
const finished = ref(false);
//当前购物车列表ID
const cartlistId = ref([])
const getcartlist = async () => {
    await cartStore.getcartList()
    cartlist.value = cartStore.cartList
    cartlistId.value = cartlist.value.map(item => item.id)
}

//选中当前产品id
const cartid = ref([])
const handlesele = (id) => {
    if (cartid.value.includes(id)) {
        let index = cartid.value.findIndex(item => item == id)
        cartid.value.splice(index, 1);
    } else {
        cartid.value.push(id)
    }

}
const all = computed(() => {
    if (!cartlist.value.length > 0) {
        return false;
    }
    return cartid.value.every(id => cartlistId.value.includes(id)) && cartlistId.value.every(id => cartid.value.includes(id))
})

const total = computed(() => {
    let selectitem = cartlist.value.filter(item => cartid.value.includes(item.id))
    return [selectitem.reduce((sum, item) => sum + item.price * item.number, 0), selectitem.reduce((sum, item) => sum + item.number, 0)]
})
const allselect = () => {
    if (all.value) {
        cartid.value = []
        return
    }
    cartid.value = []
    cartid.value.push(...cartlistId.value)
}

const closedetail = async (num) => {
    if (num) {
        getcartlist()
    }
    opendetail.value = false
}
//处理详情数据
const detaildata = ref({})
const handledata = (item) => {
    detaildata.value = item
    opendetail.value = true
}
//减少
const decrease = async (item, num = 0) => {

    await AuthService.usercart({ "product_id": item.product_id, "spec_id": item.spec_id, "number": 1, change: 'sub' })
    if (num == 1) {
        await cartStore.newcartlist()
        getcartlist();
    }

}
const add = async (item) => {
    await AuthService.usercart({ "product_id": item.product_id, "spec_id": item.spec_id, "number": 1, change: 'add' })
    /*  await cartStore.newcartlist()
     getcartlist(); */
}
// 批量删除购物车
const handleclear = async() =>{
    let productlist = cartlist.value.filter(item => cartid.value.includes(item.id))
    let ids = productlist.map(item => item.id).join(",")
    await AuthService.clear_cart({'id':ids})
    await cartStore.newcartlist()
    getcartlist()
}
const pageInfo = reactive({
    page: 1,
    limit: 10,
    type_id: "",
    classify: 2,
})
const recommend = ref([])
const grouplist = ref([[], []])
const onLoad = async () => {
    const res2 = await AuthService.prodect_list({ ...pageInfo })
    recommend.value = [...recommend.value, ...res2.dataList]
    grouplist.value = [[],[]];
    recommend.value.forEach((item, index) => {
        grouplist.value[index % 2].push(item);
    });
    if (recommend.value.length < res2.total) {
        loading.value = false;
        pageInfo.page++;
    } else {
        finished.value = true;
    }

}
const onSubmit = () => {
    //product_ids

    let productlist = cartlist.value.filter(item => cartid.value.includes(item.id))
    let product_ids = productlist.map(item => {
        return { "product_id": item.product_id, "spec_id": item.spec_id, "number": item.number }
    })
    if (product_ids.length == 0) return showToast(t("请选择商品"));
    if (store.userInfo.address_count == 0) {
        router.push(`/addaddress?cate=2&product_ids=${JSON.stringify(product_ids)}`)
        return;
    }
    router.push(`/confirmorder?product_ids=${JSON.stringify(product_ids)}`);
}
onActivated(async () => {
    getcartlist();
})
</script>
<style scoped>
.home {
    height: 100%;
    padding-bottom: 80px;
    width: 100%;
    margin: 0 auto;
}
</style>