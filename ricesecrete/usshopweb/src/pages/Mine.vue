<template>
    <div class="mine">
        <div
            class="h-[44px] bg-white z-10 flex fixed top-0 items-center justify-end max-w-[425px] w-full py-[6px] px-[6px]">
            <div @click="router.push('/setting')">
                <svg data-v-0f93ac02="" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                    <path data-v-0f93ac02="" fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 8.74976C10.2051 8.74976 8.75 10.2048 8.75 11.9998C8.75 13.7947 10.2051 15.2498 12 15.2498C13.7949 15.2498 15.25 13.7947 15.25 11.9998C15.25 10.2048 13.7949 8.74976 12 8.74976ZM12 10.2498C12.9665 10.2498 13.75 11.0333 13.75 11.9998C13.75 12.9663 12.9665 13.7498 12 13.7498C11.0335 13.7498 10.25 12.9663 10.25 11.9998C10.25 11.0333 11.0335 10.2498 12 10.2498Z"
                        fill="currentColor"></path>
                    <path data-v-0f93ac02="" fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.503 3.88614L14.2429 1.74756L9.67491 1.76972L8.43999 3.86814L7.72099 4.21014L5.28616 3.86274L2.45452 7.44325L3.32149 9.69864L3.15249 10.4836L1.37122 12.1588L2.41437 16.6195L4.69549 17.3411L5.21849 18.0056L5.4152 20.3781L9.57864 22.3532L11.501 21.0666L12.432 21.0681L14.3669 22.3625L18.5196 20.3412L18.7135 18.0026L19.2435 17.3386L21.5208 16.6185L22.5252 12.125L20.8035 10.5066L20.6125 9.69214L21.4802 7.43496L18.6013 3.86891L16.195 4.21214L15.503 3.88614ZM10.535 3.2651L13.3875 3.2511L14.445 5.04547L15.9619 5.76112L17.969 5.4741L19.7695 7.7041L19.0472 9.58544L19.4468 11.2901L20.8745 12.6321L20.245 15.4481L18.3559 16.0466L17.2568 17.4238L17.0955 19.3661L14.485 20.6366L12.8888 19.5685L11.0466 19.5666L9.4525 20.6326L6.8385 19.3926L6.6764 17.4321L5.58849 16.0504L3.6805 15.4466L3.0285 12.6591L4.52098 11.2568L4.88209 9.57719L4.1615 7.7026L5.9275 5.4691L7.9572 5.7592L9.4992 5.02572L10.535 3.2651Z"
                        fill="currentColor"></path>
                </svg>
            </div>
        </div>
        <div class="mt-[44px]  bg-base-selector-color ">
            <div class="bg-white p-4 pt-0 ">
                <div class="p-2 pt-0 text-[18px] font-bold">
                    <h1>{{ userStore.userInfo.nick_name ? userStore.userInfo.nick_name : userStore.userInfo.email }}
                    </h1>
                </div>
                <div class="flex justify-between items-center pt-2 px-10 pr-0" @click="router.push('/wallet')">
                    <!--  <div class="flex items-center justify-center flex-col">
                        <div class="text-[14px] font-bold">{{userStore.userInfo.integral}}</div>
                        <div class="text-[12px]">{{ $t('积分') }}</div>
                    </div> -->
                    <div class="flex  items-center">
                        <div>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_51_8)">
                                    <path
                                        d="M22.0032 18.4409H18.0026C16.9416 18.4409 15.924 18.0194 15.1738 17.2691C14.4235 16.5189 14.002 15.5013 14.002 14.4403C14.002 13.3793 14.4235 12.3617 15.1738 11.6114C15.924 10.8612 16.9416 10.4397 18.0026 10.4397H22.0032C22.2685 10.4397 22.5229 10.5451 22.7104 10.7326C22.898 10.9202 23.0033 11.1746 23.0033 11.4398C23.0033 11.7051 22.898 11.9595 22.7104 12.1471C22.5229 12.3346 22.2685 12.44 22.0032 12.44H18.0026C17.4721 12.44 16.9633 12.6507 16.5882 13.0259C16.2131 13.401 16.0023 13.9098 16.0023 14.4403C16.0023 14.9708 16.2131 15.4796 16.5882 15.8547C16.9633 16.2298 17.4721 16.4406 18.0026 16.4406H22.0032C22.2685 16.4406 22.5229 16.5459 22.7104 16.7335C22.898 16.9211 23.0033 17.1755 23.0033 17.4407C23.0033 17.706 22.898 17.9604 22.7104 18.1479C22.5229 18.3355 22.2685 18.4409 22.0032 18.4409Z"
                                        fill="black" />
                                    <path
                                        d="M17.0025 14.4403C17.0024 14.6374 17.0412 14.8325 17.1166 15.0145C17.192 15.1965 17.3025 15.362 17.4418 15.5013C17.5811 15.6406 17.7465 15.7511 17.9285 15.8265C18.1106 15.9019 18.3057 15.9408 18.5027 15.9408C18.6997 15.9408 18.8948 15.9019 19.0768 15.8265C19.2589 15.7511 19.4243 15.6406 19.5636 15.5013C19.7029 15.362 19.8134 15.1965 19.8888 15.0145C19.9641 14.8325 20.0029 14.6374 20.0029 14.4403C20.0029 14.2433 19.9641 14.0482 19.8888 13.8662C19.8134 13.6841 19.7029 13.5187 19.5636 13.3794C19.4243 13.2401 19.2589 13.1296 19.0768 13.0542C18.8948 12.9788 18.6997 12.9399 18.5027 12.9399C18.3057 12.9399 18.1106 12.9788 17.9285 13.0542C17.7465 13.1296 17.5811 13.2401 17.4418 13.3794C17.3025 13.5187 17.192 13.6841 17.1166 13.8662C17.0412 14.0482 17.0024 14.2433 17.0025 14.4403Z"
                                        fill="black" />
                                    <path
                                        d="M21.043 4.93897L19.1128 1.60849C18.9136 1.25595 18.6463 0.946588 18.3264 0.698432C18.0064 0.450277 17.6403 0.268296 17.2493 0.163101C16.8583 0.0579067 16.4503 0.0316026 16.049 0.0857227C15.6478 0.139843 15.2613 0.273304 14.9122 0.478325L7.24105 4.89897H3.00043C2.20467 4.89897 1.4415 5.21508 0.878807 5.77777C0.316116 6.34046 0 7.10364 0 7.8994L0 20.9013C0 21.6971 0.316116 22.4602 0.878807 23.0229C1.4415 23.5856 2.20467 23.9017 3.00043 23.9017H21.003C21.7988 23.9017 22.562 23.5856 23.1247 23.0229C23.6874 22.4602 24.0035 21.6971 24.0035 20.9013V7.8994C23.9931 7.11747 23.6779 6.37047 23.1249 5.81752C22.572 5.26456 21.825 4.94933 21.043 4.93897ZM15.9123 2.20858C16.0331 2.13873 16.1665 2.09342 16.3048 2.07522C16.4431 2.05703 16.5837 2.06632 16.7184 2.10256C16.8531 2.13879 16.9794 2.20127 17.0899 2.28639C17.2004 2.37152 17.2931 2.47763 17.3625 2.59863L18.7127 4.93897H11.2016L15.9123 2.20858ZM21.9832 20.9413C21.9832 21.2065 21.8778 21.4609 21.6902 21.6485C21.5027 21.8361 21.2483 21.9414 20.983 21.9414H2.98043C2.71518 21.9414 2.46079 21.8361 2.27322 21.6485C2.08566 21.4609 1.98029 21.2065 1.98029 20.9413V7.93941C1.98029 7.67415 2.08566 7.41976 2.27322 7.2322C2.46079 7.04463 2.71518 6.93926 2.98043 6.93926H20.983C21.2483 6.93926 21.5027 7.04463 21.6902 7.2322C21.8778 7.41976 21.9832 7.67415 21.9832 7.93941V20.9413Z"
                                        fill="black" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_51_8">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>

                        <div class="text-[12px] px-4">{{ $t('钱包') }}</div>
                    </div>
                    <div class="flex items-center justify-center" @click="router.push('/wallet')">
                        <div class="text-[14px] font-bold">{{ $sysCurrency }}</div>
                        <div class="text-[14px] font-bold">{{ userStore.userInfo.balance }}</div>
                        <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                    </div>
                </div>
            </div>
            <div class="mt-4 bg-white">
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class=" font-bold text-[14px]">{{ $t('我的订单') }}</h3>
                        </div>
                        <div class="flex text-base-text-color-gray text-[12px] items-center"
                            @click="router.push('/myorders?tab=0')">
                            <div>{{ $t('查看全部') }}</div>
                            <span>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.70711 2L8.59619 5.88909L4.70711 9.77817L4 9.07107L7.18155 5.88909L4 2.70711L4.70711 2Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>

                        </div>
                    </div>
                    <div class="myorder py-2">
                        <div class="flex relative flex-col items-center justify-center"
                            @click="router.push('/myorders?tab=1')">
                            <span class="relative"><svg data-v-7496abdc="" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14 15.75H18.75V14.25H14V15.75Z" fill="currentColor"></path>
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M21.75 3.75H2.25V20.25H21.75V3.75ZM20.25 5.25V8.5H3.75V5.25H20.25ZM3.75 10H20.25V18.75H3.75V10Z"
                                        fill="currentColor"></path>
                                </svg>
                                <div class=" absolute top-[-6px] right-[-8px] w-[14px] h-[14px] bg-[#FF0000] rounded-[8px] flex justify-center items-center"
                                    v-if="!userStore.userInfo.order_status_1 == 0">
                                    <span class="text-[8px] text-[#fff]">{{ userStore.userInfo.order_status_1 < 99 ?
                                        userStore.userInfo.order_status_1 : '99+' }}</span>
                                </div>
                            </span>
                            <div class="text-[12px]">{{ $t('未付款') }}</div>

                        </div>
                        <div class="flex flex-col items-center justify-center" @click="router.push('/myorders?tab=2')">
                            <span class=" relative"><svg data-v-7496abdc="" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2 8H3.25V21H20.75V8H22V4H2V8ZM20.5 5.5H3.5V6.5H20.5V5.5ZM9.25 8H19.25V19.5H4.75V8H7.75V12.5H9.25V8Z"
                                        fill="currentColor"></path>
                                </svg>
                                <div class=" absolute top-[-6px] right-[-8px] w-[14px] h-[14px] bg-[#FF0000] rounded-[8px] flex justify-center items-center"
                                    v-if="!userStore.userInfo.order_status_2 == 0">
                                    <span class="text-[8px] text-[#fff]">{{ userStore.userInfo.order_status_2 < 99 ?
                                        userStore.userInfo.order_status_2 : '99+' }}</span>
                                </div>
                            </span>
                            <div class="text-[12px]">{{ $t('处理中') }}</div>
                        </div>
                        <div class="flex flex-col items-center justify-center" @click="router.push('/myorders?tab=3')">
                            <span class="relative"><svg data-v-7496abdc="" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.25 3.88818V8.46018L22.1288 10.0948V19.4191H20.6949C20.6075 20.8591 19.412 21.9999 17.95 21.9999C16.488 21.9999 15.2925 20.8591 15.2051 19.4191H10.1449C10.0575 20.8591 8.86196 21.9999 7.39999 21.9999C5.93803 21.9999 4.74251 20.8591 4.65511 19.4191H2.97241V15.4404H4.47241L4.472 17.9189H15.75V5.38788H4.472V8.24988H8.5V9.74988H2.97241V3.88818H17.25ZM17.25 17.9188V10.0418L20.6285 11.1738V17.9188H17.25ZM17.95 20.4999C17.317 20.4999 16.7939 20.0294 16.7114 19.4191H19.1887C19.1061 20.0294 18.583 20.4999 17.95 20.4999ZM7.39999 20.4999C6.76702 20.4999 6.24393 20.0294 6.16135 19.4191H8.63864C8.55606 20.0294 8.03297 20.4999 7.39999 20.4999Z"
                                        fill="currentColor"></path>
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.33401 13.2499V11.7499H1.87708V13.2499H5.33401Z" fill="currentColor">
                                    </path>
                                </svg>
                                <div class=" absolute top-[-6px] right-[-8px] w-[14px] h-[14px] bg-[#FF0000] rounded-[8px] flex justify-center items-center"
                                    v-if="!userStore.userInfo.order_status_3 == 0">
                                    <span class="text-[8px] text-[#fff]">{{ userStore.userInfo.order_status_3 < 99 ?
                                        userStore.userInfo.order_status_3 : '99+' }}</span>
                                </div>
                            </span>
                            <div class="text-[12px]">{{ $t('已发货') }}</div>
                        </div>
                        <div class="flex flex-col items-center justify-center" @click="router.push('/myorders?tab=4')">
                            <span><svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <mask id="mask0_43_8" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                        width="24" height="24">
                                        <rect width="24" height="24" fill="#D9D9D9" />
                                    </mask>
                                    <g mask="url(#mask0_43_8)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7 13.5L8.25 12.3333L10.75 14.6667L15.75 10L17 11.1667L10.75 17L7 13.5Z"
                                            fill="black" />
                                        <rect x="4" y="6" width="16" height="14" stroke="black" />
                                        <rect x="2" y="4" width="20" height="2" stroke="black" />
                                    </g>
                                </svg>

                            </span>
                            <div class="text-[12px]">{{ $t('已收货') }}</div>
                        </div>
                        <div class="flex flex-col items-center justify-center" @click="router.push('/myorders?tab=5')">
                            <span><svg data-v-7496abdc="" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M11.1914 16.1523L7.5 13.0762L11.1914 10L12.1517 11.1523L10.7145 12.35H16.0758V15.6H14.5758V13.85H10.7716L12.1517 15L11.1914 16.1523Z"
                                        fill="currentColor"></path>
                                    <path data-v-7496abdc="" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M22 7.5V3.5H2V7.5H3.25V20.25H20.75V7.5H22ZM20.5 6H3.5V5H20.5V6ZM4.75 18.75V7.5H19.25V18.75H4.75Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <div class="text-[12px]">{{ $t('退货') }}</div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="my-4 bg-white p-4">
                <h3 class=" font-bold text-[14px]">{{ $t('更多服务') }}</h3>
                <div class="myorder" @click="contactUs">
                    <div class="flex flex-col justify-center items-center">
                        <img src="@/assets/image/server.webp" class="w-[24px] h-[24px]" alt="">
                        <div class="text-[12px] text-center">{{ $t('客户服务') }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white pb-[50px]">
                <van-tabs v-model:active="active" @click-tab="handletab">
                    <van-tab v-for="tab in tablist" :title="tab" class="text-black"></van-tab>
                </van-tabs>
                <van-list v-model:loading="loading" loading-text="loading" :finished="finished"
                    :finished-text="$t('没有更多了')" @load="onLoad">
                    <div class="flex  justify-between  w-full pt-4 mt-[16px] px-2">
                        <div class="w-1/2">
                            <div v-for="(item, index) in group_collectlist[0]" class="mb-[10px] relative bg-white">
                                <img :src="item.img" alt="" class="rounded-[2px] relative"
                                    @click="router.push(`/goodsdetail?id=${item.id}`)">
                                <span class=" absolute top-[10px] right-3 z-[1]" @click="handlesele(item.id)"
                                    v-if="isclear">
                                    <!--  -->
                                    <span v-if="collectid.includes(item.id)">
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

                                </span>
                                <div class="flex flex-col w-[150px]" @click="router.push(`/goodsdetail?id=${item.id}`)">
                                    <div class="flex items-center">
                                        <span v-if="item.cashBack"
                                            class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                item.cashBack }}</span>
                                        <span
                                            class="ml-[3px] max-w-[150px] text-[12px] text-nowrap overflow-hidden whitespace-nowrap text-ellipsis">{{
                                                item.title }}</span>
                                    </div>
                                    <div class="flex items-center mt-[5px]">
                                        <span class="text-[12px] font-bold text-[#222]">{{ $sysCurrency }}{{
                                            item.min_price }}</span>
                                        <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.buy > 0'>{{
                                            item.buy
                                            }}+
                                            {{ $t('出售') }}</span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 ml-2">
                            <div v-for="(item, index) in group_collectlist[1]" class="mb-[10px] relative bg-white">
                                <img :src="item.img" alt="" class="rounded-[2px] relative"
                                    @click="router.push(`/goodsdetail?id=${item.id}`)">
                                <span class=" absolute top-[10px] right-3 z-[1]" @click="handlesele(item.id)"
                                    v-if="isclear">
                                    <!--  -->
                                    <span class="mt-2 mr-2" v-if="collectid.includes(item.id)">
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

                                </span>
                                <div class="flex flex-col" @click="router.push(`/goodsdetail?id=${item.id}`)">
                                    <div class="flex items-center">
                                        <span v-if="item.cashBack"
                                            class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                item.cashBack }}</span>
                                        <span
                                            class="ml-[3px] max-w-[150px] text-[12px] text-nowrap overflow-hidden whitespace-nowrap text-ellipsis">{{
                                                item.title }}</span>
                                    </div>
                                    <div class="flex items-center mt-[5px]">
                                        <span class="text-[12px] font-bold text-[#222]">{{ $sysCurrency }}{{
                                            item.min_price }}</span>
                                        <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.buy > 0'>{{
                                            item.buy
                                            }}+
                                            {{ $t('出售') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </van-list>

            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref, reactive, onActivated } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/store/userStore';
import { UserService } from '@/api/user'
const userStore = useUserStore();
import { useI18n } from 'vue-i18n';
import { showToast } from 'vant';
const { t } = useI18n();
const active = ref(0)
const loading = ref(false);
const finished = ref(false);
const pageInfo = reactive({
    page: 1,
    limit: 10,
    type: 2
})
const collectlist = ref([])
const group_collectlist = ref([[], []])
const handletab = () => {
    pageInfo.page = 1
    finished.value = false
    onLoad(1)
}
const onLoad = async (num) => {
    let res;
    if (active.value == 0) {
        res = await UserService.collectlist({ ...pageInfo })
    } else {
        res = await UserService.view_list({ ...pageInfo })
    }
    if (num == 1) {
        collectlist.value = []
    }
    group_collectlist.value = [[], []]
    collectlist.value = [...collectlist.value, ...res.items]
    collectlist.value.forEach((item, index) => {
        group_collectlist.value[index % 2].push(item);
    });
    if (collectlist.value.length < res.total) {
        loading.value = false
        pageInfo.page++
    } else {
        finished.value = true
    }
}
const tablist = [t('愿望清单'), t('最近浏览过')]
const router = useRouter()

const contactUs = () => {
    if (document.querySelector('#s-chat-plugin')) {
        document.querySelector('#s-chat-plugin').contentDocument.querySelector('.view_btn_chat_pglag').click()
        setTimeout(() => {
            document.querySelector('#s-chat-plugin').style.setProperty('display', 'block', 'important');
        }, 200)

    } else {
        showToast(t("客服链接未加载，请稍后再试..."))
    }
}

onActivated(async () => {
    await userStore.getUserInfo()
})
</script>
<style lang="less" scoped>
.mineTop {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    width: 100%;
}

.myorder {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    width: 100%;
}
</style>