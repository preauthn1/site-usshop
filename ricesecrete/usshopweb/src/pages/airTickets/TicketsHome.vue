<template>
    <div class="">
        <div class="page_home bg-[#f0f2f5]">
            <img class=" home-page-content-img"
                src="https://static.tripcdn.com/packages/flight/static-image-online/latest/flight_v2/h5/bg_flights_v2.webp"
                alt="">
            <div class="home-page-opacity-bottom"></div>
            <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 bg-[#00000000]" z-index="2"
                :border="false" @click-left="router.back()" @click-right="router.replace('/wishlist')">
                <template #right>

                </template>
                <template #left>
                    <div class="flex items-center">
                        <van-icon name="arrow-left" color="#fff" size="20" />
                        <div>
                            <svg width="120" height="24" viewBox="0 0 120 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_26_5" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                    width="120" height="24">
                                    <rect width="120" height="24" fill="#D9D9D9" />
                                </mask>
                                <g mask="url(#mask0_26_5)">
                                    <path
                                        d="M26.3359 8.75879L23.3779 8.77148C23.3102 7.71354 22.9082 6.88411 22.1719 6.2832C21.444 5.68229 20.4665 5.38184 19.2393 5.38184C18.2236 5.38184 17.3942 5.61882 16.751 6.09277C16.1077 6.56673 15.7861 7.15495 15.7861 7.85742C15.7861 8.56836 16.0697 9.15234 16.6367 9.60938C17.2038 10.0579 18.3929 10.515 20.2041 10.9805C22.9548 11.6745 24.749 12.4277 25.5869 13.2402C26.4333 14.0527 26.8564 15.1868 26.8564 16.6426C26.8564 18.403 26.1963 19.8206 24.876 20.8955C23.5641 21.9619 21.8079 22.4951 19.6074 22.4951C17.2884 22.4951 15.4814 21.9365 14.1865 20.8193C12.9001 19.7021 12.2103 18.0941 12.1172 15.9951H15.1387C15.2233 17.2054 15.6761 18.1449 16.4971 18.8135C17.3265 19.4821 18.4479 19.8164 19.8613 19.8164C20.9277 19.8164 21.7995 19.5583 22.4766 19.042C23.1621 18.5257 23.5049 17.891 23.5049 17.1377C23.5049 16.8415 23.4076 16.5072 23.2129 16.1348C23.0267 15.7539 22.7855 15.4281 22.4893 15.1572C21.9984 14.7087 20.9066 14.277 19.2139 13.8623C18.8499 13.7692 18.5749 13.7015 18.3887 13.6592C16.2982 13.109 14.8213 12.4108 13.958 11.5645C13.0947 10.7096 12.6631 9.55013 12.6631 8.08594C12.6631 6.52865 13.2936 5.26335 14.5547 4.29004C15.8242 3.30827 17.5085 2.81738 19.6074 2.81738C21.6471 2.81738 23.2721 3.35482 24.4824 4.42969C25.7012 5.49609 26.319 6.93913 26.3359 8.75879ZM32.5739 3.2998H35.6843V10.6631H44.5329V3.2998H47.6432V22H44.5329V13.4434H35.6843V22H32.5739V3.2998ZM54.1732 3.2998H67.5795V6.04199H57.2328V10.9297H66.7796V13.5449H57.2328V19.1943H67.9222V22H54.1732V3.2998ZM73.5127 3.2998H86.919V6.04199H76.5723V10.9297H86.1192V13.5449H76.5723V19.1943H87.2618V22H73.5127V3.2998ZM92.8523 3.2998H96.153L104.862 17.29V3.2998H107.871V22H104.595L95.8357 8.00977V22H92.8523V3.2998Z"
                                        fill="#fff" />
                                </g>
                            </svg>
                        </div>
                    </div>
                </template>
            </van-nav-bar>
            <div class="flex h-[30px] relative">
                <div class=" h-full pt-[4px] mx-[12px] text-[14px] relative font-bold text-[#fff]"
                    @click="router.replace('/hotelhome')">{{ $t('住宿') }}</div>
                <div class=" h-full pt-[4px] mx-[12px] text-[14px] relative font-bold text-[#fff] header-channels-item">
                    {{ $t('机票')
                    }}</div>
                <div class="bg-[#f0f2f533] left-0 bottom-0 absolute h-[3px] w-[150px]"></div>
            </div>
        </div>
        <div class="flight-titleHomeTitle">
            <span class="flight-titleHomeTitle">{{ $t('机票') }}</span>
        </div>
        <div class="search-panel-container">
            <div class="content-wrapper">
                <div class="w-full h-full relative">
                    <div class="bg-[#f0f2f5] rounded-[4px] mx-[12px] p-[4px] relative touch-none">
                        <div class="flex relative items-center">
                            <div class="tab-item" @click="selectedTab(index)" v-for="(item, index) in tabData"
                                :key="index" :class="{ 'active': tabSelectedIndex == index }">{{ item }}
                            </div>
                            <div class="transition-vb"
                                :class="{ 'move-to-left': tabSelectedIndex == 0, 'move-to-middle': tabSelectedIndex == 1, 'move-to-right': tabSelectedIndex == 2 }">
                            </div>
                        </div>
                    </div>
                    <div v-if="tabSelectedIndex != 2">
                        <div class="px-[12px] relative">
                            <div class="airport-info-inner">
                                <div class="flex relative">
                                    <div class="flex-1 w-[50%] pb-[16px]">
                                        <div @click="toSearchAddress(true)">
                                            <div class="w-[87%] flex flex-col">
                                                <span class="city-span">布宜諾斯艾利斯</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 w-[50%] pb-[16px] text-right">
                                        <div @click="toSearchAddress(false)">
                                            <div class="w-[87%] flex flex-col ml-[13%]">
                                                <span class="city-span">布宜諾斯艾利斯</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" absolute my-0 mx-auto left-0 right-0 top-0 h-[32px] w-[32px]">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <mask id="mask0_55_26" style="mask-type:alpha" maskUnits="userSpaceOnUse"
                                                x="0" y="0" width="32" height="32">
                                                <rect width="32" height="32" fill="#606060" />
                                            </mask>
                                            <g mask="url(#mask0_55_26)">
                                            </g>
                                            <g clip-path="url(#clip0_55_26)">
                                                <path
                                                    d="M9.21447 17.2069L13.1603 17.4519L9.39756 25L11.6852 25L18.6804 17.5884L23.0812 17.5884C24.2436 17.5884 26 16.6533 26 15.5001C26 14.3467 24.2436 13.4117 23.0812 13.4117L18.6804 13.4117L11.6852 6L9.39756 6L13.1603 13.4225L8.89069 13.7262C8.78759 13.7262 8.68885 13.7382 8.58976 13.7489L7.35853 12.4009L6 12.4009L7.03805 15.5181L7.05455 15.5675L6 18.5522L7.35853 18.5522L8.62862 17.1603L9.21447 17.2069Z"
                                                    fill="#3B5CFB" />
                                                <path
                                                    d="M0.887153 10.743C2.1695 7.05776 4.75415 3.96817 8.15495 2.05533C11.5557 0.142497 15.5383 -0.461761 19.3536 0.356221C23.1688 1.1742 26.5538 3.35805 28.8718 6.49699C31.1898 9.63593 31.9405 15 31.9405 17.4007L24.6598 11.0003L27.0849 9.60018C25.8303 7.42677 23.8097 5.59257 21.7199 4.54837C19.4751 3.42672 17.2726 3.29151 14.459 3.29151C13.5 3.29151 8.80841 3.61246 6 6C4.08816 7.62533 3.5 8.5 1.5 11.0003L0.887153 10.743Z"
                                                    fill="url(#paint0_linear_55_26)" />
                                                <path
                                                    d="M31.4677 20.4398C30.1853 24.125 27.6007 27.2146 24.1999 29.1274C20.7991 31.0402 16.8165 31.6445 13.0013 30.8265C9.18601 30.0085 5.80103 27.8247 3.48301 24.6857C1.16498 21.5468 7.66257e-07 15.9007 6.88249e-07 13.5L7.695 20.1825L5.26997 21.5826C6.52448 23.756 8.54512 25.5902 10.6349 26.6344C12.8798 27.756 15.0822 27.8912 17.8959 27.8912C18.8548 27.8912 23.5464 27.5703 26.3548 25.1827C28.2667 23.5574 28.8548 22.6827 30.8548 20.1825L31.4677 20.4398Z"
                                                    fill="url(#paint1_linear_55_26)" />
                                            </g>
                                            <defs>
                                                <linearGradient id="paint0_linear_55_26" x1="32" y1="17" x2="0.999999"
                                                    y2="17" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#C5CFFE" />
                                                    <stop offset="1" stop-color="white" stop-opacity="0" />
                                                </linearGradient>
                                                <linearGradient id="paint1_linear_55_26" x1="0.354828" y1="14.1827"
                                                    x2="31.3548" y2="14.1827" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#C5CFFE" />
                                                    <stop offset="1" stop-color="white" stop-opacity="0" />
                                                </linearGradient>
                                                <clipPath id="clip0_55_26">
                                                    <rect width="32" height="32" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="px-[12px]">
                            <div class="flex w-full justify-between relative-bottom">
                                <div class="py-[16px] flex-2" @click="showCalender = true">
                                    <span class="month-day">{{ todayDate }} {{ todayWeekday }}</span>
                                </div>
                                <div class="offset-day" v-if="tabSelectedIndex == 1 && tomorrowDate.length > 0">{{
                                    daysBetween
                                    }}</div>
                                <div class=" text-right flex-2 py-[16px]" v-if="tabSelectedIndex == 1"
                                    @click="showCalender = true">
                                    <span v-if="tomorrowDate.length > 0" class="month-day">{{ tomorrowDate }} {{
                                        tomorrowWeekday
                                        }}</span>
                                    <span v-else class="month-day grey">{{ $t('日期') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="px-[12px] relative">
                            <div class="airport-info-inner">
                                <div
                                    class="bg-[#3264ff] rounded-[1px] text-[#fff] text-center w-[18px] text-[12px] mb-[8px]">
                                    1
                                </div>
                                <div class="flex relative">
                                    <div class="flex-1 w-[50%] pb-[16px]">
                                        <div>
                                            <div class="w-[87%] flex flex-col">
                                                <span class="city-span">布宜諾斯艾利斯</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 w-[50%] pb-[16px] text-right">
                                        <div>
                                            <div class="w-[87%] flex flex-col ml-[13%]">
                                                <span class="city-span">东京</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" absolute my-0 mx-auto left-0 right-0 top-0 h-[32px] w-[32px]"></div>
                                </div>

                            </div>
                        </div>
                        <div class="px-[12px] relative-ele">
                            <div class="flex w-full justify-between relative-bottom">
                                <div class="py-[16px] flex-2">
                                    <span class="month-day">3月5日 週三</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-[12px] relative">
                            <div class="airport-info-inner">
                                <div
                                    class="bg-[#3264ff] rounded-[1px] text-[#fff] text-center w-[18px] text-[12px] mb-[8px]">
                                    2
                                </div>
                                <div class="flex relative">
                                    <div class="flex-1 w-[50%] pb-[16px]">
                                        <div>
                                            <div class="w-[87%] flex flex-col">
                                                <span class="city-span">布宜諾斯艾利斯</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 w-[50%] pb-[16px] text-right">
                                        <div>
                                            <div class="w-[87%] flex flex-col ml-[13%]">
                                                <span class="city-span">东京</span>
                                                <span class="code-span"> 所有機場</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" absolute my-0 mx-auto left-0 right-0 top-0 h-[32px] w-[32px]"></div>
                                </div>

                            </div>
                        </div>
                        <div class="px-[12px]">
                            <div class="flex w-full justify-between relative-bottom">
                                <div class="py-[16px] flex-2">
                                    <span class="month-day">3月5日 週三</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-[16px] px-[12px] flex items-center">
                        <div class="type-in flex justify-between w-full">
                            <div class="flex-1 mr-[8px] block" @click="showTypeView = true">
                                <div class="class-change-span text-[#0f294d] text-[14px] font-medium w-full relative">
                                    {{ typeSelected }}
                                </div>
                            </div>
                            <div class=" flex-shrink-0 text-right" @click="showBottom = true">
                                <div class="flex ">
                                    <span
                                        class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                                        <div class="ml-[8px] mr-[2px]">
                                            <svg t="1741002865147" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="36331" id="mx_n_1741002865149"
                                                width="18" height="18">
                                                <path
                                                    d="M273.066667 716.8l28.910933-346.999467A68.266667 68.266667 0 0 1 370.005333 307.2h283.989334a68.266667 68.266667 0 0 1 68.027733 62.600533L750.933333 716.8h-102.4l-34.133333 307.2h-204.8l-34.133333-307.2H273.066667z m238.933333-716.8a136.533333 136.533333 0 1 1 0 273.066667 136.533333 136.533333 0 0 1 0-273.066667z"
                                                    fill="#ced2d9" p-id="36332"></path>
                                            </svg>
                                        </div>
                                        1
                                    </span>
                                    <span
                                        class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                                        <div class="ml-[8px] mr-[2px]">
                                            <svg t="1741002387094" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="3443" id="mx_n_1741002387095"
                                                width="18" height="18">
                                                <path d="M512 144m-144 0a144 144 0 1 0 288 0 144 144 0 1 0-288 0Z"
                                                    p-id="3444" fill="#ced2d9"></path>
                                                <path
                                                    d="M903.3 282.9L722.8 463.4c-12 12-18.8 28.3-18.8 45.3V1008c0 8.8-7.2 16-16 16H560c-8.8 0-16-7.2-16-16V736h-64v272c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16V508.6c0-17-6.7-33.3-18.8-45.3L120.7 282.9c-31.1-31.1-32.1-81.8-1.4-113.2 15.7-16 36.5-24.1 57.2-24.1 20.5 0 41 7.8 56.6 23.4l141.6 141.6c6 6 14.1 9.4 22.6 9.4h229.2c8.5 0 16.6-3.4 22.6-9.4L790.9 169c15.6-15.6 36.1-23.4 56.6-23.4 20.8 0 41.5 8 57.2 24.1 30.7 31.4 29.7 82.1-1.4 113.2z"
                                                    p-id="3445" fill="#ced2d9"></path>
                                            </svg>
                                        </div>
                                        0
                                    </span>
                                    <span
                                        class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                                        <div class="ml-[8px] mr-[2px]">
                                            <svg t="1741002466956" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg" p-id="13483" id="mx_n_1741002466958"
                                                width="18" height="18">
                                                <path
                                                    d="M508.437935 821.946566q-21.372392 9.79568-40.073235 19.591359-16.029294 8.905163-32.058588 19.591359t-25.824973 23.153424q-8.905163 11.576712-8.459905 20.927134t5.788356 17.810326 14.693519 16.029294 17.365068 15.584036q8.905163 8.014647 4.007323 22.708166t-17.365068 27.160748-30.277555 17.810326-34.730137-6.233614q-17.810326-10.686196-36.511169-30.722813t-32.503846-44.080558-18.700843-48.53314 5.788356-44.971074 24.043941-42.744784 25.824973-40.073235q14.248261-21.372392 28.496522-41.854267 15.138777 4.452582 31.168071 9.79568 13.357745 4.452582 28.94178 12.02197t28.94178 16.474552q14.248261 9.79568 25.379715 20.481875t19.146101 19.591359q9.79568 9.79568 16.91981 20.481875zM695.446363 723.099254q16.029294 20.481875 32.058588 41.854267 13.357745 17.810326 27.606006 40.073235t24.934457 42.744784 5.788356 44.971074-18.700843 48.53314-32.503846 44.080558-35.620653 30.722813q-17.810326 11.576712-35.620653 6.233614t-30.277555-17.810326-17.365068-27.160748 4.007323-22.708166 17.810326-15.584036 14.693519-16.029294 5.788356-17.810326-8.905163-20.927134q-9.79568-12.467229-26.270232-23.153424t-32.503846-19.591359q-18.700843-9.79568-39.182718-19.591359 8.014647-10.686196 17.810326-20.481875 8.905163-8.905163 20.036617-19.146101t26.270232-20.036617q12.467229-8.905163 26.270232-16.029294t26.270232-12.467229q14.248261-6.233614 27.606006-10.686196zM861.082399 505.813271q19.591359 16.029294 21.81765 33.394362t-5.788356 30.277555-24.043941 19.146101-33.83962 0.890516q-17.810326-6.233614-37.401686-14.693519t-37.401686-17.365068q-20.481875-9.79568-40.073235-20.481875l-8.014647 129.124867q-18.700843 2.671549-41.854267 5.343098-20.481875 1.781033-49.423656 3.562065t-66.343466 1.781033-69.460273-1.781033-56.102528-3.562065q-28.496522-2.671549-51.649947-5.343098l0-137.139514q-21.372392 2.671549-44.525816 8.014647-20.481875 4.452582-46.306849 12.02197t-51.649947 19.146101q-26.71549 11.576712-45.861591 2.226291t-27.160748-28.051264-3.116807-39.627976 26.270232-31.613329q15.138777-7.124131 33.394362-17.365068t36.956427-21.372392 35.620653-22.262908 30.277555-19.146101q37.401686 50.75943 94.839988 81.482244t126.00806 30.722813q65.898208 0 121.11022-27.606006t93.504214-75.693887q6.233614 5.343098 19.591359 17.365068t30.277555 26.71549 35.175395 31.168071 35.175395 30.722813zM530.700843 447.039194q-46.306849 0-87.2706-17.365068t-71.241306-47.642623-47.642623-71.241306-17.365068-87.2706 17.365068-87.2706 47.642623-71.241306 71.241306-47.642623 87.2706-17.365068 87.2706 17.365068 71.241306 47.642623 47.642623 71.241306 17.365068 87.2706-17.365068 87.2706-47.642623 71.241306-71.241306 47.642623-87.2706 17.365068z"
                                                    p-id="13484" fill="#ced2d9"></path>
                                            </svg>
                                        </div>
                                        0
                                    </span>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="pt-[16px] px-[12px]">
                        <van-button type="success" color="#3264ff" block @click="router.push('/ticketssearch')">{{
                            $t('搜寻')
                            }}</van-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-[12px]">
            <div class="pt-[12px]">
                <div class="home-link-cardinde relative w-full">
                    <div class="flex justify-between">
                        <div class="flex items-center text-[#0f294d] font-medium text-[14px] leading-[18px]">
                            <van-icon name="records-o" size="17" class="mr-[4px] font-medium" color="#0f294d" />
                            <span class="text-[#0f294d] font-medium text-[14px] leading-[18px]">{{ $t('我的订单')
                            }}</span>
                        </div>
                        <div>
                            <van-icon name="arrow" color="#455873" size="14" />
                        </div>
                    </div>
                    <div class="flex mt-[9px]">
                        <span class="text-[#8592a6] text-[13px] text-left">{{ $t('按此查看所有订单') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <van-popup v-model:show="showSearchView" position="bottom" style="background-color: #00000000;"
        :style="{ height: '100%' }">
        <div class="h-full bg-[#fff] flex flex-col">
            <div class="w-full px-[12px] block">
                <div class="flex py-[16px] relative w-full flex-col">
                    <div class="flex items-center justify-start relative" @click="showSearchView = false">
                        <van-icon name="cross" color="#0f294d" size="24" />
                    </div>
                    <div class="mt-[16px] flex-1 flex relative  h-[40px]">
                        <div
                            class="flex items-center bg-[#fff] absolute w-[32px] h-[32px] left-[1px] top-[6px] justify-center">
                            <van-icon name="search" size="20" color="#0f294d" />
                        </div>
                        <div class="input-inner">
                            <van-field v-model="value" class="text-[14px]" :placeholder="$t('城市或机场')"
                                :autosize="{ minHeight: '40px' }" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-[16px] px-[12px] pb-[20px] bg-[#fff]">
                <div class="bg-[#fff] mb-[16px]">
                    <div class="flex pb-[12px]">
                        <div class="text-[#8592a6] text-[13px]">{{ $t('最近搜寻') }}</div>
                    </div>
                    <ul class="flex overflow-visible flex-wrap">
                        <li v-for="(item, index) in 10" :key="index"
                            class="bg-[#f5f7fa] rounded-[2px] p-[8px] mr-[8px] mb-[8px]">
                            <div class="text-[#455873] flex flex-wrap text-[13px] items-center w-full align-top">布宜諾斯
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </van-popup>
    <van-calendar :style="{ height: '100%' }" close-icon-position="top-left"
        :type="tabSelectedIndex == 1 ? 'range' : 'single'" v-model:show="showCalender" :default-date="defaultDate"
        :round="false" :title="$t('选择日期')" @select="selectedDateAction">
        <template #footer>
            <div class="calendar-action">
                <div class="tips-con">
                    <span class="tip-txt">{{ $t('显示价格为人均$价格。') }}</span>
                    <span class="tip-txt">{{ $t('所有出发／抵达时间均为当地时间') }}</span>
                </div>
                <div class="calendar-date-tags">
                    <div class="calendar-date-tag left">
                        <div class="tag-tip">{{ $t('当地时间') }}</div>
                        <div class="tag-date">{{ tempTodayDate }} {{ tempTodayWeekday }}</div>
                    </div>
                    <div class="calendar-date-tag right" v-if="tabSelectedIndex == 1">
                        <div class="tag-tip">{{ $t('当地时间') }}</div>
                        <div class="tag-date">{{ tempTomorrowDate }} {{ tempTodayWeekday }}</div>
                    </div>
                </div>
                <div class="button-con">
                    <van-button @click="onConfirm" type="success" color="#3264ff" block>{{ $t('确定') }}</van-button>
                </div>
            </div>
        </template>
    </van-calendar>
    <van-popup v-model:show="showBottom" position="bottom" class="bg-[#00000000]" :style="{ height: '50%' }">
        <div class=" h-full flex flex-col justify-start rounded-t-[12px] items-center overflow-x-hidden">
            <div class="flex py-[1.6rem] px-[1.6rem] divider-line w-full rounded-t-[12px] items-center bg-[#fff]">
                <div class="flex-1" @click="showBottom = false">
                    <van-icon name="cross" size="2.2rem" />
                </div>
                <div class="flex-4">
                    <span class="text-[1.8rem] text-[#0f294d] font-bold text-center">{{ $t('乘客') }}</span>
                </div>
                <div class="flex-1"></div>
            </div>
            <div class="flex-1 w-full overflow-y-auto bg-[#f5f7fa]">
                <div class=" px-[1.6rem] w-full bg-[#fff]">
                    <div class="m-guest_item">
                        <div class="flex items-center h-[5.6rem]">
                            <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                {{ $t('成人') }}
                                <span class="text-[#8592a6] text-[13px]">{{ $t('12岁或以上') }}</span>
                            </div>
                            <van-stepper v-model="tempAldultNum" min="1" theme="round" button-size="22" disable-input />
                        </div>
                    </div>
                </div>
                <div class=" px-[1.6rem] w-full bg-[#fff]">
                    <div class="m-guest_item">
                        <div class="flex items-center h-[5.6rem]">
                            <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                {{ $t('小童') }}
                                <span class="text-[#8592a6] text-[13px]">{{ $t('旅游时2-11岁') }}</span>
                            </div>
                            <van-stepper v-model="tempChildNum" min="0" theme="round" button-size="22" disable-input />
                        </div>
                    </div>
                </div>
                <div class=" px-[1.6rem] w-full bg-[#fff]">
                    <div class="m-guest_item">
                        <div class="flex items-center h-[5.6rem]">
                            <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                {{ $t('婴儿（不占用座位）') }}
                                <span class="text-[#8592a6] text-[13px]">{{ $t('旅游时未满2岁') }}</span>
                            </div>
                            <van-stepper v-model="tempRoomNum" min="0" theme="round" button-size="22" disable-input />
                        </div>

                    </div>
                </div>
            </div>
            <div class="flex justify-start items-center p-[1.6rem] bg-[#fff] w-full h-[8rem]">
                <van-button @click="confirmRoom" type="success" color="#3264ff" block>{{ $t('确定') }}</van-button>
            </div>
        </div>
    </van-popup>
    <van-popup v-model:show="showTypeView" position="bottom" style="background-color: #00000000;">
        <div class="h-full bg-[#fff] rounded-t-[12px]">
            <header class="layer-header flex justify-center">
                <div class="flex items-center justify-center text-[18px] font-medium leading-[56px]">艙位</div>
            </header>
            <section class="layer-content">
                <div :class="{ 'current': typeSelected == item }" v-for="item in typeData" @click="didSelectedType(item)">
                    <span>{{ item }}</span>
                    <van-icon name="success" v-if="typeSelected == item" />
                </div>
            </section>
        </div>
    </van-popup>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()

const showTypeView = ref(false);
const typeData = ref([t('经济舱/特选经济舱'), t('经济舱'), t('特选经济舱'), t('商务舱/头等舱'), t('商务舱'), t('头等舱')])
const typeSelected = ref(t('经济舱/特选经济舱'));
const didSelectedType = (item) => {
    typeSelected.value = item;
    showTypeView.value = false;
}

const showBottom = ref(false);
const showCalender = ref(false);
const selectedDateAction = (item) => {
    tempDefaultDate.value = item;
    console.log(item, 1222222);
    if (tabSelectedIndex.value == 1) {
        updateDates(item[0], item[1]);
        daysBetween.value = calculateDaysBetween(item[0], item[1]) + t('日')
    } else {
        updateDates(item);
    }
}
const onConfirm = () => {
    defaultDate.value = tempDefaultDate.value;
    todayDate.value = tempTodayDate.value;
    todayWeekday.value = tempTodayWeekday.value;
    if (tabSelectedIndex.value == 1) {
        tomorrowDate.value = tempTomorrowDate.value;
        tomorrowWeekday.value = tempTodayWeekday.value;
        if (defaultDate.value.length == 2) {
            daysToStay.value = t(`${calculateDaysBetween(defaultDate.value[0], defaultDate.value[1])}日`)
        }
    }
    console.log('defaultDate', defaultDate.value);
    showCalender.value = false;
}

const getWeekday = (date) => {
    const weekdays = [t('周日'), t('周一'), t('周二'), t('周三'), t('周四'), t('周五'), t('周六'),];
    return weekdays[date.getDay()];
};
const formatDate = (date) => {
    const month = String(date.getMonth() + 1).padStart(2, '');
    const day = String(date.getDate()).padStart(2, '');
    return `${month}月${day}日`;
};
const tempTodayDate = ref('');
const tempTodayWeekday = ref('');
const tempTomorrowDate = ref('');
const tempTomorrowWeekday = ref('');
const tempDefaultDate = ref([]);
const updateDates = (today1, nextDay = null) => {
    const today = today1;
    const tomorrow = nextDay;
    tempTodayDate.value = formatDate(today);
    tempTodayWeekday.value = getWeekday(today);
    if (tomorrow) {
        tempTomorrowDate.value = formatDate(tomorrow);
        tempTomorrowWeekday.value = getWeekday(tomorrow);
    } else {
        tempTomorrowDate.value = '';
        tempTomorrowWeekday.value = '';
    }
};
const calculateDaysBetween = (date1, date2) => {
    const oneDay = 24 * 60 * 60 * 1000; // 一天的毫秒数
    const diffDays = Math.round(Math.abs((date2 - date1) / oneDay));
    return diffDays;
};
const today = new Date();
const tomorrow = new Date(today);
tomorrow.setDate(today.getDate() + 1);
const defaultDate = ref([today]);
const daysToStay = ref('')
updateDates(today);
const daysBetween = ref(calculateDaysBetween(today, tomorrow) + t('日'));
const todayDate = ref(tempTodayDate.value);
const todayWeekday = ref(tempTodayWeekday.value);
const tomorrowDate = ref(tempTomorrowDate.value);
const tomorrowWeekday = ref(tempTomorrowWeekday);

const showSearchView = ref(false);
const searchStart = ref(false);
const toSearchAddress = (isStart) => {
    showSearchView.value = !showSearchView.value;
    searchStart.value = isStart;
}

const tabData = ref([t('单程'), t('来回')]);//, t('多个目的地')
const tabSelectedIndex = ref(0);
const selectedTab = (index) => {
    tabSelectedIndex.value = index;
    console.log(tabSelectedIndex.value);
}
</script>

<style lang="less" scoped>
.header-channels-item {
    border-bottom: 3px solid #ffffff66;
}

.page_home {
    position: relative;
}

.home-page-content-img {
    object-fit: cover;
    object-position: 100% 75%;
    position: absolute;
    top: 0;
    width: 100%;
    height: 200px;
    overflow-clip-margin: content-box;
    overflow: clip;
}

.home-page-opacity-bottom {
    background: linear-gradient(hsla(0, 0%, 100%, 0), #f0f2f5);
    height: 48px;
    position: absolute;
    top: 153px;
    width: 100%;
}

.flight-titleHomeTitle {
    margin: 12px 12px 0;
    position: relative;
}

.flight-titleHomeTitle span {
    display: flex;
    -webkit-box-align: baseline;
    align-items: baseline;
    color: #fff;
    font-size: 28px;
    font-weight: 700;
}

.flight-titleHomeTitle span:after {
    background-color: #ffb400;
    border-radius: 50%;
    content: "";
    display: block;
    height: 6px;
    margin-left: 2px;
    width: 6px;
}

span {
    line-height: 1;
}

.search-panel-container {
    border-radius: 8px;
    box-shadow: 0 8px 16px 0 rgba(15, 41, 77, .1);
    margin: 12px 12px 0;
    position: relative;
}

.search-panel-container .content-wrapper {
    background-color: #fff;
    border-radius: 8px;
    padding: 12px 0;
    position: relative;
}

.tab-item {
    position: relative;
    -webkit-box-flex: 1;
    color: #0f294d;
    display: -webkit-box;
    -ms-flex: 1;
    flex: 1;
    font-size: 14px;
    font-weight: 400;
    line-height: 17px;
    overflow: hidden;
    padding: 3px 2px;
    text-align: center;
    text-overflow: ellipsis;
    z-index: 1;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.tab-item.active {
    color: #0f294d;
    font-weight: 500;
}

.move-to-left {
    transform: translateX(0);
    transition: transform .4sease, -webkit-transform .4sease;
}

.move-to-middle {
    transform: translateX(100%);
    transition: transform .4sease, -webkit-transform .4sease;
}

.move-to-right {
    transform: translateX(200%);
    transition: transform .4sease, -webkit-transform .4sease;
}

.transition-vb {
    background-color: #fff;
    border-radius: 2px;
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 50%;
}

.airport-info-inner {
    padding-top: 16px;
    position: relative;
}

.airport-info-inner:after {
    background-color: #dadfe6;
    bottom: 0;
    content: "";
    display: block;
    height: 1px;
    left: -50%;
    position: absolute;
    transform: scale(.5);
    width: 200%;
}

.city-span {
    color: #0f294d;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 0;
    line-height: 24px;
    word-break: break-word !important;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}

.code-span {
    color: #455873;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0;
    line-height: 18px;
    overflow: hidden;
    padding-top: 2px;
}

.relative-bottom {
    position: relative;
}

.relative-bottom:after {
    background-color: #dadfe6;
    bottom: 0;
    content: "";
    display: block;
    height: 1px;
    left: -50%;
    position: absolute;
    transform: scale(.5);
    width: 200%;
}

.month-day {
    color: #0f294d;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 0;
    line-height: 24px;
    margin-right: 4px;
}

.month-day.grey {
    color: #ced2d9;
}

.offset-day {
    color: #8592a6;
    font-size: 13px;
    line-height: 24px;
    padding: 16px 4px;
}

.type-in {
    display: flex;
    position: relative;
    -webkit-box-align: center;
    align-items: center;
}

.type-in:after {
    background-color: #dadfe6;
    bottom: -16px;
    content: "";
    display: block;
    height: 1px;
    left: -50%;
    position: absolute;
    transform: scale(.5);
    width: 200%;
}

.class-change-span {
    line-height: 18px;
}

.home-link-cardinde {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px 0 rgba(15, 41, 77, .04);
    overflow: hidden;
    padding: 12px;
}

.relative-ele {
    position: relative;
}

.relative-ele:after {
    background-color: #f0f2f5;
    bottom: 0;
    content: "";
    display: block;
    height: 8px;
    left: -50%;
    position: absolute;
    transform: scale(.5);
    width: 200%;
}

.input-inner {
    -webkit-box-flex: 1;
    box-sizing: border-box;
    -ms-flex: 1;
    flex: 1;
    // height: 40px;
    padding: 0 0 0 24px;
    border: 1px solid #acb4bf;
    border-radius: 4px;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
}

.calendar-action {
    background: #fff;
    border-top: 1px solid #dadfe6;
    bottom: -1px;
    display: inline-block;
    left: 0;
    position: absolute;
    right: 0;
    width: 100%;
}

.calendar-action .tips-con {
    background: #f5f7fa;
    box-sizing: border-box;
    display: inline-block;
    padding: 8px 12px;
    text-align: start;
    width: 100%;
}

.calendar-action .tips-con .tip-txt {
    color: #455873;
    display: inline-block;
    font-size: 12px;
    line-height: 17px;
    width: 100%;
}

.calendar-action .calendar-date-tags {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    padding: 12px 12px 0;
    width: 100%;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    justify-content: space-between;
    white-space: nowrap;
}

.calendar-action .calendar-date-tags .calendar-date-tag.left {
    -webkit-box-pack: start;
    -ms-flex-pack: start;
    justify-content: flex-start;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
}

.calendar-action .calendar-date-tags .calendar-date-tag {
    display: -webkit-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -ms-flex-negative: 0;
    flex-shrink: 0;
}

.calendar-action .calendar-date-tags .calendar-date-tag .tag-tip {
    color: #8592a6;
    display: inline-block;
    font-size: 13px;
    line-height: 17px;
}

.calendar-action .calendar-date-tags .calendar-date-tag .tag-date {
    color: #0f294d;
    display: inline-block;
    font-size: 20px;
    font-weight: 500;
    line-height: 30px;
    margin: 4px 0 2px;
}

.calendar-action .button-con {
    background: #fff;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    display: inline-block;
    padding: 16px 12px;
    width: 100%;
}

.calendar-action .calendar-date-tags .calendar-date-tag.right {
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    -webkit-box-align: end;
    -ms-flex-align: end;
    align-items: flex-end;
}

.layer-header {
    color: #0f294d;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    font-size: 18px;
    font-weight: 500;
    height: 56px;
    line-height: 56px;
    padding: 0 12px;
    position: relative;
}

.layer-header:before {
    background: #dadfe6;
    bottom: 0;
    content: "";
    display: block;
    height: 1px;
    left: -50%;
    position: absolute;
    -webkit-transform: scale(.5);
    transform: scale(.5);
    width: 200%;
}

.layer-content {
    color: #0f294d;
    font-size: 16px;
    line-height: 54px;
    padding-left: 12px;
}

.layer-content>div {
    height: 54px;
    margin-right: 12px;
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

.layer-content .current {
    color: #3264ff;
    font-size: 16px;
    font-weight: 500;
}

.layer-content>div:not(:last-child):before {
    background: #dadfe6;
    bottom: 0;
    content: "";
    display: block;
    height: 1px;
    left: -50%;
    position: absolute;
    -webkit-transform: scale(.5);
    transform: scale(.5);
    width: 200%;
}

.layer-content>div>span {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
}
</style>