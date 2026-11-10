<template>
    <div class="myorders bg-base-selector-color">
        <div class=" h-[44px] bg-white z-10 flex fixed top-0 items-center justify-between border-b border-solid
            border-[#e5e5e5] max-w-[425px] w-full py-[6px] px-[6px]">
            <div class="flex items-center w-1/4">
                <van-icon name="arrow-left" class="pr-4" @click="router.go(-1)" size="20" />
                <!-- <span>
                    <svg aria-label="Search" width="24" height="24" viewBox="0 0 24 24" fill="none" handleExpose="true"
                        class="app-header__icon-right" style="">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.34315 5.34315C8.46734 2.21895 13.5327 2.21895 16.6569 5.34315C19.6022 8.2885 19.7708 12.9591 17.1627 16.102L21.4298 20.3692L20.3692 21.4298L16.102 17.1627C12.9591 19.7708 8.28851 19.6022 5.34315 16.6569C2.21895 13.5327 2.21895 8.46734 5.34315 5.34315ZM15.5962 6.40381C13.0578 3.8654 8.94221 3.8654 6.40381 6.40381C3.8654 8.94221 3.8654 13.0578 6.40381 15.5962C8.94221 18.1346 13.0578 18.1346 15.5962 15.5962C18.1346 13.0578 18.1346 8.94221 15.5962 6.40381Z"
                            fill="var(--shein-common-header-icon-color, #000)"></path>
                    </svg>
                </span> -->
            </div>
            <div class="text-[17px] font-semibold">{{ $t('我的订单') }}</div>
            <div @click="contactUs" class="flex items-center justify-end w-1/4">
                <span class="pr-4">
                    <svg data-v-1949c568="" data-v-11cce465="" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;">
                        <rect data-v-1949c568="" data-v-11cce465="" width="24" height="24" fill="white"
                            fill-opacity="0.01"></rect>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M21.6 12C21.6 11.4477 21.1523 11 20.6 11H18.6V16H20.6C21.1523 16 21.6 15.5523 21.6 15V12Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M1.40002 15C1.40002 15.5523 1.84774 16 2.40002 16L4.40002 16L4.40002 11L2.40002 11C1.84774 11 1.40002 11.4477 1.40002 12L1.40002 15Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465="" fill-rule="evenodd" clip-rule="evenodd"
                            d="M7 10C7 9.44772 7.44772 9 8 9H15C15.5523 9 16 9.44772 16 10V14C16 14.5523 15.5523 15 15 15H8C7.44772 15 7 14.5523 7 14V10ZM6 10C6 8.89543 6.89543 8 8 8H15C16.1046 8 17 8.89543 17 10V14C17 15.1046 16.1046 16 15 16H8C6.89543 16 6 15.1046 6 14V10Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M12.3916 11.883C12.3916 12.1172 12.4846 12.3418 12.6502 12.5074C12.8158 12.673 13.0404 12.766 13.2746 12.766C13.5088 12.766 13.7334 12.673 13.899 12.5074C14.0646 12.3418 14.1576 12.1172 14.1576 11.883C14.1576 11.7671 14.1348 11.6522 14.0904 11.5451C14.046 11.438 13.981 11.3406 13.899 11.2586C13.817 11.1766 13.7197 11.1116 13.6125 11.0672C13.5054 11.0228 13.3906 11 13.2746 11C13.1587 11 13.0438 11.0228 12.9367 11.0672C12.8296 11.1116 12.7322 11.1766 12.6502 11.2586C12.5682 11.3406 12.5032 11.438 12.4588 11.5451C12.4144 11.6522 12.3916 11.7671 12.3916 11.883Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M8.5 11.883C8.5 12.1172 8.59303 12.3418 8.75863 12.5074C8.92423 12.673 9.14882 12.766 9.38301 12.766C9.6172 12.766 9.8418 12.673 10.0074 12.5074C10.173 12.3418 10.266 12.1172 10.266 11.883C10.266 11.6488 10.173 11.4242 10.0074 11.2586C9.8418 11.093 9.6172 11 9.38301 11C9.14882 11 8.92423 11.093 8.75863 11.2586C8.59303 11.4242 8.5 11.6488 8.5 11.883Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M20 16C20 16 19.1 16 18.6 16C18.6 14.5 18.7188 13.0781 18.6008 11.0723C18.4828 9.26883 17.6833 7.57776 16.3646 6.34221C15.0459 5.10665 13.3066 4.41914 11.4998 4.41914C9.69288 4.41914 7.95366 5.10665 6.63493 6.34221C5.3162 7.57776 4.51672 9.26883 4.39869 11.0723C4.39869 14 4.39869 15.8235 4.39869 15.8235C4.39869 15.8235 2.79869 15.6483 2.48692 15.3364C2.17515 15.0246 2 14.6016 2 14.1606V12.7352C2.00048 12.4182 2.09173 12.1079 2.26294 11.8411C2.43414 11.5743 2.67816 11.3621 2.96613 11.2296C3.04689 9.01972 3.98132 6.92729 5.57286 5.39245C7.1644 3.85762 9.289 3 11.4998 3C13.7105 3 15.8351 3.85762 17.4267 5.39245C19.0182 6.92729 19.9526 9.01972 20.0334 11.2296C20.0334 13 20 14.0695 20 16Z"
                            fill="currentColor"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M19.4004 14V15.5C19.4004 17.5 16.1 20.5 13.6 20.5" stroke="currentColor"
                            stroke-width="1.5"></path>
                        <path data-v-1949c568="" data-v-11cce465=""
                            d="M11 20.4998C11 19.8371 11.5373 19.2998 12.2 19.2998H13.8C14.4627 19.2998 15 19.8371 15 20.4998C15 21.1625 14.4627 21.6998 13.8 21.6998H12.2C11.5373 21.6998 11 21.1625 11 20.4998Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <!-- <span>
                    <svg t="1739604654010" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="4249" width="18" height="18">
                        <path
                            d="M607.897867 768.043004c-17.717453 0-31.994625-14.277171-31.994625-31.994625L575.903242 383.935495c0-17.717453 14.277171-31.994625 31.994625-31.994625s31.994625 14.277171 31.994625 31.994625l0 351.94087C639.892491 753.593818 625.61532 768.043004 607.897867 768.043004z"
                            fill="#575B66" p-id="4250"></path>
                        <path
                            d="M415.930119 768.043004c-17.717453 0-31.994625-14.277171-31.994625-31.994625L383.935495 383.935495c0-17.717453 14.277171-31.994625 31.994625-31.994625 17.717453 0 31.994625 14.277171 31.994625 31.994625l0 351.94087C447.924744 753.593818 433.647573 768.043004 415.930119 768.043004z"
                            fill="#575B66" p-id="4251"></path>
                        <path
                            d="M928.016126 223.962372l-159.973123 0L768.043004 159.973123c0-52.980346-42.659499-95.983874-95.295817-95.983874L351.94087 63.989249c-52.980346 0-95.983874 43.003528-95.983874 95.983874l0 63.989249-159.973123 0c-17.717453 0-31.994625 14.277171-31.994625 31.994625s14.277171 31.994625 31.994625 31.994625l832.032253 0c17.717453 0 31.994625-14.277171 31.994625-31.994625S945.73358 223.962372 928.016126 223.962372zM319.946246 159.973123c0-17.545439 14.449185-31.994625 31.994625-31.994625l320.806316 0c17.545439 0 31.306568 14.105157 31.306568 31.994625l0 63.989249L319.946246 223.962372 319.946246 159.973123 319.946246 159.973123z"
                            fill="#575B66" p-id="4252"></path>
                        <path
                            d="M736.048379 960.010751 288.123635 960.010751c-52.980346 0-95.983874-43.003528-95.983874-95.983874L192.139761 383.591466c0-17.717453 14.277171-31.994625 31.994625-31.994625s31.994625 14.277171 31.994625 31.994625l0 480.435411c0 17.717453 14.449185 31.994625 31.994625 31.994625l448.096758 0c17.717453 0 31.994625-14.277171 31.994625-31.994625L768.215018 384.795565c0-17.717453 14.277171-31.994625 31.994625-31.994625s31.994625 14.277171 31.994625 31.994625l0 479.231312C832.032253 916.835209 789.028725 960.010751 736.048379 960.010751z"
                            fill="#575B66" p-id="4253"></path>
                    </svg>
                </span> -->
            </div>
        </div>
        <div class="mt-[44px]">
            <van-sticky>
                <van-tabs v-model:active="active" @click-tab="onClickTab">
                    <van-tab v-for="tab in tablist" :title="tab" class="text-black">




                    </van-tab>
                </van-tabs>
            </van-sticky>

            <van-list v-if="dataList.length > 0" v-model:loading="loading" loading-text="loading" :finished="finished"
                :finished-text="$t('没有更多了')" @load="onLoad">

                <div class="bg-[#FFFFFF]" v-if="dataList.length > 0" v-for="item in dataList">

                    <div class="p-[10px] mt-[10px]"
                        @click="router.push({ path: '/orderdetails', query: { orderId: item.id } })">
                        <div class="flex pt-[12px] pl-[16px] pr-[16px] items-center">
                            <div class="w-[6px] h-[6px] rounded-[3px] bg-[#2d68a8]"></div>
                            <span class=" overflow-hidden text-ellipsis text-[14px] font-bold ml-[9px]">{{
                                getOrderTitle(item.status) }}</span>
                        </div>
                        <div class="pl-[16px] pt-[5px] pr-[16px] pb-[14px] border-b border-[#e5e5e5]">
                            <div class="flex">
                                <span class="text-[#666] text-[14px]">{{ $t('订单号') }}：</span>
                                <span class="text-[#666] text-[14px]">{{ item.order_no }}</span>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="p-[16px]">
                                <div class="w-[75px]">
                                    <img :src="item.product_img" />
                                </div>
                            </div>

                            <div class="flex pt-[16px] pr-[8px]">
                                <div class="block " style="max-width: 100%; overflow: hidden;">
                                    <div class="flex items-start justify-between">
                                        <span
                                            class=" text-[14px] text-[#222] font-bold flex-1 overflow-hidden text-ellipsis line-clamp-3">{{
                                                item.product_name }}</span>

                                    </div>
                                    <div class="mt-[10px] flex justify-between">
                                        <span
                                            class="text-[#666] w-[80%] overflow-hidden text-ellipsis line-clamp-3 text-[12px]">{{item.spec_json.spec}}</span>
                                        <span class="text-[#666] text-[12px]">x{{item.spec_json.number}}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class=" text-right pb-[16px] pl-[16px] pr-[16px] flex justify-end">
                            <!-- <span class="text-[14px] text-[#666]">{{ $t('2个', { num: '2' }) }}</span> -->
                            <span class="text-[14px] text-[#666] ml-[5px]">{{ $t('实际价格') }}:</span>
                            <span class="text-[14px] ml-[5px] font-semibold">{{ $sysCurrency }}{{ item.amount }}</span>
                        </div>
                    </div>
                    <div class="pl-[16px] pb-[16px] pr-[16px]" v-if="item.status == 1">
                        <div class="flex justify-end">
                            <!-- <span class="text-[#2d68a8] text-[14px] font-semibold leading-normal">{{
                                $t('更多') }}</span> -->
                            <div class="flex">
                                <div class="pl-[12px] pr-[12px]">
                                    <van-button type="default" size="small" class="rounded-[0px] font-bold text-[14px]"
                                        @click="onSubmitCancel(item)">{{
                                            $t('取消') }}</van-button>
                                </div>
                                <van-button type="primary" size="small" class="rounded-[0px] font-bold text-[14px]"
                                    @click="router.push({ path: '/orderdetails', query: { orderId: item.id } })">{{
                                        $t('立即付款') }}</van-button>
                            </div>
                        </div>
                    </div>

                </div>
            </van-list>


            <!-- 数据为空状态 -->
            <div class="pt-4"></div>
            <div class="empty bg-white" v-if="dataList.length <= 0">
                <div>
                    <svg style="width: 75px; height: 75px;" width="75" height="75" viewBox="0 0 75 75" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M19.75 6.49998C20.1642 6.49998 20.5 6.16419 20.5 5.74998C20.5 5.33576 20.1642 4.99998 19.75 4.99998C19.3358 4.99998 19 5.33576 19 5.74998C19 6.16419 19.3358 6.49998 19.75 6.49998ZM19.75 7.49998C20.7165 7.49998 21.5 6.71648 21.5 5.74998C21.5 4.78348 20.7165 3.99998 19.75 3.99998C18.7835 3.99998 18 4.78348 18 5.74998C18 6.71648 18.7835 7.49998 19.75 7.49998Z"
                            fill="#E5E5E5"></path>
                        <path
                            d="M50.9576 5.03888L51.743 4.79876C51.97 4.72935 52.2102 4.85649 52.2793 5.08273C52.3485 5.30897 52.2205 5.54864 51.9935 5.61806L51.2081 5.85818L51.4625 6.6904C51.5317 6.91664 51.4037 7.15632 51.1767 7.22573C50.9496 7.29514 50.7095 7.16801 50.6404 6.94177L50.3859 6.10954L49.5269 6.37215C49.2999 6.44156 49.0598 6.31443 48.9906 6.08819C48.9214 5.86195 49.0494 5.62227 49.2765 5.55286L50.1354 5.29025L49.8529 4.36618C49.7837 4.13994 49.9117 3.90026 50.1388 3.83085C50.3658 3.76144 50.6059 3.88857 50.6751 4.11481L50.9576 5.03888Z"
                            fill="#DDDDDD"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M66.4916 11.1875C65.5112 11.1875 64.7166 10.39 64.7166 9.40625C64.7166 8.42249 65.5112 7.625 66.4916 7.625C67.4719 7.625 68.2666 8.42249 68.2666 9.40625C68.2666 10.39 67.4719 11.1875 66.4916 11.1875ZM66.4916 10.475C67.0798 10.475 67.5566 9.99653 67.5566 9.40627C67.5566 8.81602 67.0798 8.33752 66.4916 8.33752C65.9034 8.33752 65.4266 8.81602 65.4266 9.40627C65.4266 9.99653 65.9034 10.475 66.4916 10.475Z"
                            fill="#999999"></path>
                        <path
                            d="M8.2585 21.2595L8.64406 20.5343C8.75552 20.3247 9.01521 20.2448 9.2241 20.3559C9.43299 20.4669 9.51197 20.7269 9.40051 20.9365L9.01494 21.6617L9.78333 22.0702C9.99222 22.1813 10.0712 22.4413 9.95974 22.6509C9.84828 22.8605 9.58859 22.9404 9.37971 22.8293L8.61132 22.4208L8.18963 23.2138C8.07817 23.4235 7.81848 23.5034 7.6096 23.3923C7.40071 23.2812 7.32173 23.0213 7.43319 22.8116L7.85487 22.0186L7.00169 21.5649C6.7928 21.4539 6.71382 21.1939 6.82528 20.9843C6.93674 20.7746 7.19643 20.6947 7.40531 20.8058L8.2585 21.2595Z"
                            fill="#999999"></path>
                        <path
                            d="M66.196 36.6044L66.6574 35.5681C66.7907 35.2685 67.1409 35.1334 67.4394 35.2663C67.7379 35.3992 67.8718 35.7498 67.7384 36.0494L67.277 37.0857L68.3752 37.5746C68.6737 37.7075 68.8076 38.0581 68.6742 38.3577C68.5408 38.6573 68.1907 38.7924 67.8922 38.6595L66.794 38.1705L66.2894 39.3039C66.156 39.6034 65.8059 39.7385 65.5074 39.6056C65.2089 39.4727 65.075 39.1221 65.2084 38.8226L65.713 37.6892L64.4938 37.1464C64.1953 37.0135 64.0614 36.6629 64.1948 36.3633C64.3281 36.0638 64.6783 35.9287 64.9768 36.0616L66.196 36.6044Z"
                            fill="#999999"></path>
                        <path
                            d="M20.25 18.5C19.9739 18.5 19.75 18.7239 19.75 19C19.75 19.2761 19.9739 19.5 20.25 19.5H55.5C56.8807 19.5 58 20.6193 58 22V45.5C58 45.7761 58.2239 46 58.5 46C58.7761 46 59 45.7761 59 45.5V22C59 20.067 57.433 18.5 55.5 18.5H20.25Z"
                            fill="#666666"></path>
                        <path
                            d="M19 47.25C19 46.9739 18.7761 46.75 18.5 46.75C18.2239 46.75 18 46.9739 18 47.25V66.5C18 68.433 19.567 70 21.5 70H39C39.2761 70 39.5 69.7761 39.5 69.5C39.5 69.2239 39.2761 69 39 69H21.5C20.1193 69 19 67.8807 19 66.5V47.25Z"
                            fill="#666666"></path>
                        <path
                            d="M26 43C25.7239 43 25.5 43.2239 25.5 43.5C25.5 43.7761 25.7239 44 26 44H40.9272C41.2033 44 41.4272 43.7761 41.4272 43.5C41.4272 43.2239 41.2033 43 40.9272 43H26Z"
                            fill="#666666"></path>
                        <path
                            d="M2.59167 74C2.31553 74 2.09167 74.2239 2.09167 74.5C2.09167 74.7761 2.31553 75 2.59167 75H6.28735C6.56349 75 6.78735 74.7761 6.78735 74.5C6.78735 74.2239 6.56349 74 6.28735 74H2.59167Z"
                            fill="#666666"></path>
                        <path
                            d="M71.8167 74C71.5405 74 71.3167 74.2239 71.3167 74.5C71.3167 74.7761 71.5405 75 71.8167 75H73C73.2761 75 73.5 74.7761 73.5 74.5C73.5 74.2239 73.2761 74 73 74H71.8167Z"
                            fill="#666666"></path>
                        <path
                            d="M8.00842 74.5C8.00842 74.2239 8.23228 74 8.50842 74H68.8584C69.1346 74 69.3584 74.2239 69.3584 74.5C69.3584 74.7761 69.1346 75 68.8584 75H8.50842C8.23228 75 8.00842 74.7761 8.00842 74.5Z"
                            fill="#666666"></path>
                        <path
                            d="M49 54.5C49 54.2239 48.7761 54 48.5 54C48.2239 54 48 54.2239 48 54.5L48 60.4955C48 60.695 48.1169 60.8672 48.2859 60.9474C48.3532 60.9811 48.4291 61 48.5093 61H53.9451C54.2212 61 54.4451 60.7761 54.4451 60.5C54.4451 60.2239 54.2212 60 53.9451 60H49L49 54.5Z"
                            fill="#666666"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M49.5 69C55.0228 69 59.5 64.5228 59.5 59C59.5 53.4772 55.0228 49 49.5 49C43.9772 49 39.5 53.4772 39.5 59C39.5 64.5228 43.9772 69 49.5 69ZM49.5 68C54.4706 68 58.5 63.9706 58.5 59C58.5 54.0294 54.4706 50 49.5 50C44.5294 50 40.5 54.0294 40.5 59C40.5 63.9706 44.5294 68 49.5 68Z"
                            fill="#666666"></path>
                        <path
                            d="M18.5 20C18.7761 20 19 20.2239 19 20.5V38.5C19 38.7761 18.7761 39 18.5 39C18.2239 39 18 38.7761 18 38.5V20.5C18 20.2239 18.2239 20 18.5 20Z"
                            fill="#666666"></path>
                        <path
                            d="M19 42C19 41.7239 18.7761 41.5 18.5 41.5C18.2239 41.5 18 41.7239 18 42V46C18 46.2761 18.2239 46.5 18.5 46.5C18.7761 46.5 19 46.2761 19 46V42Z"
                            fill="#666666"></path>
                        <path
                            d="M25.5 35C25.2239 35 25 35.2239 25 35.5C25 35.7761 25.2239 36 25.5 36H43.5C43.7761 36 44 35.7761 44 35.5C44 35.2239 43.7761 35 43.5 35H25.5Z"
                            fill="#CCCCCC"></path>
                        <path
                            d="M26 52.5C25.7239 52.5 25.5 52.7239 25.5 53C25.5 53.2761 25.7239 53.5 26 53.5H35.2441C35.5202 53.5 35.7441 53.2761 35.7441 53C35.7441 52.7239 35.5202 52.5 35.2441 52.5H26Z"
                            fill="#CCCCCC"></path>
                        <path
                            d="M44.5 35.5C44.5 35.2239 44.7239 35 45 35H50C50.2761 35 50.5 35.2239 50.5 35.5C50.5 35.7761 50.2761 36 50 36L45 36C44.7239 36 44.5 35.7761 44.5 35.5Z"
                            fill="#CCCCCC"></path>
                        <path
                            d="M51.5 35C51.2239 35 51 35.2239 51 35.5C51 35.7761 51.2239 36 51.5 36H52C52.2761 36 52.5 35.7761 52.5 35.5C52.5 35.2239 52.2761 35 52 35H51.5Z"
                            fill="#CCCCCC"></path>
                    </svg>
                </div>
                <div class="mt-[16px] text-[12px] text-center">
                    {{ $t('这里是空的:-(') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Header from '../components/Header.vue';
import { useRouter, useRoute } from 'vue-router';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { UserService } from '@/api/user'
const { t } = useI18n();
const router = useRouter()
const route = useRoute();
const active = ref(0)
const tablist = ref(
    [t('全部订单'), t('未付款'),t('待发货'), t('已发货'), t('已收货'), t('退货')]//, t('处理中')
)


const contactUs =()=>{
    console.log('afafa');
    
    if(document.querySelector('#s-chat-plugin')){
        document.querySelector('#s-chat-plugin').contentDocument.querySelector('.view_btn_chat_pglag').click()
        setTimeout(()=>{
            document.querySelector('#s-chat-plugin').style.setProperty('display', 'block', 'important');
        },200)

    }else{
        showToast("客服链接未加载，请稍后再试...")
    }
}

onMounted(() => {
    if (route.query.tab) {
        active.value = Number(route.query.tab)
    } else {
        active.value = 0;
    }
    from.value.tab = active.value;
    console.log(from.tab);
    getOrderList();
})
const from = ref({
    page: 1,
    limit: 20,
    tab: 0,
    type: 2,
});
const dataList = ref([]);
const loading = ref(false);
const finished = ref(false);
const getOrderList = async () => {
    const res = await UserService.orderList(from.value);
    if (from.value.page == 1) {
        dataList.value = [];
    }
    dataList.value = [...dataList.value, ...res.dataList]
    if (dataList.value.length < res.count) {
        loading.value = false;
        from.value.page++;
    } else {
        finished.value = true;
    }
}

const onClickTab = (item) => {
    console.log(item,'1111111111')
    if (item.name == 0) {
        from.value.tab = item.name;
    } else if (item.name == 1) {
        from.value.tab = item.name;
    } else if (item.name == item.name) {
        from.value.tab = item.name;
    } else if (item.name == 3) {
        from.value.tab = item.name;
    } else if (item.name == 4) {
        from.value.tab = item.name;
    } else if (item.name == 5) {
        from.value.tab = item.name;
    }
    dataList.value = [];
    from.value.page = 1;
    getOrderList();
}

const getOrderTitle = (status) => {
    if (status == 1) {
        return t('未付款');
    } else if (status == 2) {
        return t('待发货');
    } else if (status == 3) {
        return t('已发货');
    } else if (status == 5 || status == 6) {
        return t('退货');
    } else if (status == 4 || status == 7) {
        return t('完成');
    } else if (status == 0) {
        return t('取消');
    }
}
const onSubmitCancel = async (item) => {
    const res = await UserService.orderCancel({order_id:item.id});
    getOrderList();
}
</script>

<style lang="less" scoped>
.empty {
    padding: 2.1322rem 0.6397rem;
    margin: 0 auto;
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-align-items: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
}

:deep(.van-sticky--fixed) {
    top: 40px !important;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>