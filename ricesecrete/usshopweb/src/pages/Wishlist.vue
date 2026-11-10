<template>
    <div class="wishlist pb-[50px]">
        <div
            class="h-[44px] bg-white z-10 flex fixed top-0 items-center justify-between border-b border-solid border-[#e5e5e5] max-w-[425px] w-full py-[6px] px-[6px]">
            <van-icon name="arrow-left" class="w-[1/3] flex-1" size="20" @click="router.go(-1)" />
            <div class="relative w-2/3 flex-1 text-center">
                <h2
                    class=" text-ellipsis overflow-hidden whitespace-nowrap  text-[17px] font-bold">
                    {{$t('愿望清单')}}</h2>
            </div>

            <div class="w-1/3">
                <div class="flex justify-end" v-show="!isclear">
                    <div class="pr-2" @click="isclear = true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 24px; height: 24px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.02669 4.87145L6.96603 3.81079L4.84454 5.93163L3.78405 4.87145L2.72339 5.93211L4.84471 8.05343L8.02669 4.87145Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.02669 10.8715L6.96603 9.81079L4.84454 11.9316L3.78405 10.8715L2.72339 11.9321L4.84471 14.0534L8.02669 10.8715Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.96603 15.8108L8.02669 16.8715L4.84471 20.0534L2.72339 17.9321L3.78405 16.8715L4.84454 17.9316L6.96603 15.8108Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21 12.7501V11.2501H10V12.7501H21Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21 5.25012V6.75012H10V5.25012H21Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21 18.7501V17.2501H10V18.7501H21Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="px-2" @click="router.replace('/cart')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width: 24px; height: 24px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.796 6.04999H21.0108L19.6582 16.7695H6.63182L5.27917 6.04999H5.28453L5.15442 5H3V3.5H6.48003L6.796 6.04999ZM6.98187 7.54999L7.03628 7.98912L7.95444 15.2695H18.3356L19.3096 7.54999H6.98187Z"
                                fill="currentColor"></path>
                            <path
                                d="M9.14995 20.25C9.14995 20.9404 8.59032 21.5 7.89999 21.5C7.20965 21.5 6.65002 20.9404 6.65002 20.25C6.65002 19.5596 7.20965 19 7.89999 19C8.59032 19 9.14995 19.5596 9.14995 20.25Z"
                                fill="currentColor"></path>
                            <path
                                d="M19.6999 20.25C19.6999 20.9404 19.1402 21.5 18.4499 21.5C17.7596 21.5 17.2 20.9404 17.2 20.25C17.2 19.5596 17.7596 19 18.4499 19C19.1402 19 19.6999 19.5596 19.6999 20.25Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-end" v-show="isclear" @click="isclear = false">
                    <div class="">{{$t('取消')}}</div>
                </div>
            </div>
        </div>
        <div class="mt-[44px] bg-[#f6f6f6]">
            <!-- <div v-if="!isclear"
                class="flex bg-white  justify-start z-[10] w-full font-bold text-[14px] text-[#767676] py-4 border-b border-solid border-[#e5e5e5]"
                :class="isFixed ? 'fixed' : 'absolute'">
                <div class="w-[50%] text-center" @click="active_tab = 0">
                    <span class="py-2" :class="active_tab == 0 ? 'active-tab' : ''">items(8)</span>
                </div>
            </div> -->
            <div class="" v-if="!collectlist.length > 0">
                <div class="pb-[46.4px] pt-[16.7px] w-full bg-white flex flex-col justify-center items-center">
                    <span class="p-[16px]">
                        <svg width="75" height="75" viewBox="0 0 75 75" fill="none" xmlns="http://www.w3.org/2000/svg"
                            style="width:75px;height:75px;">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.5 38.2501C11.5 37.2836 10.7165 36.5001 9.75 36.5001C8.7835 36.5001 8 37.2836 8 38.2501C8 39.2166 8.7835 40.0001 9.75 40.0001C10.7165 40.0001 11.5 39.2166 11.5 38.2501ZM9 38.2501C9 37.8359 9.33579 37.5001 9.75 37.5001C10.1642 37.5001 10.5 37.8359 10.5 38.2501C10.5 38.6643 10.1642 39.0001 9.75 39.0001C9.33579 39.0001 9 38.6643 9 38.2501Z"
                                fill="#E5E5E5"></path>
                            <path
                                d="M56.659 62.1275L57.0446 61.4024C57.1561 61.1928 57.4158 61.1129 57.6246 61.2239C57.8335 61.335 57.9125 61.595 57.801 61.8046L57.4155 62.5297L58.1839 62.9383C58.3928 63.0494 58.4717 63.3093 58.3603 63.5189C58.2488 63.7286 57.9891 63.8085 57.7802 63.6974L57.0119 63.2888L56.5902 64.0819C56.4787 64.2915 56.219 64.3714 56.0101 64.2604C55.8013 64.1493 55.7223 63.8893 55.8337 63.6797L56.2554 62.8866L55.4022 62.433C55.1933 62.3219 55.1144 62.0619 55.2258 61.8523C55.3373 61.6427 55.597 61.5628 55.8059 61.6739L56.659 62.1275Z"
                                fill="#999999"></path>
                            <path
                                d="M14.1443 15.8824L14.9412 16.0811C15.1715 16.1385 15.3119 16.3712 15.2546 16.6007C15.1974 16.8303 14.9643 16.9698 14.7339 16.9124L13.937 16.7137L13.7265 17.5581C13.6693 17.7877 13.4361 17.9272 13.2058 17.8698C12.9754 17.8123 12.8351 17.5797 12.8923 17.3501L13.1028 16.5057L12.2313 16.2884C12.0009 16.231 11.8606 15.9983 11.9178 15.7688C11.975 15.5392 12.2082 15.3997 12.4385 15.4571L13.3101 15.6744L13.5438 14.7369C13.6011 14.5073 13.8342 14.3678 14.0646 14.4252C14.2949 14.4826 14.4353 14.7153 14.3781 14.9448L14.1443 15.8824Z"
                                fill="#999999"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M63 20.2814C63 21.2652 63.7947 22.0626 64.775 22.0626C65.7553 22.0626 66.55 21.2652 66.55 20.2814C66.55 19.2976 65.7553 18.5001 64.775 18.5001C63.7947 18.5001 63 19.2976 63 20.2814ZM65.84 20.2814C65.84 20.8717 65.3632 21.3502 64.775 21.3502C64.1868 21.3502 63.71 20.8717 63.71 20.2814C63.71 19.6912 64.1868 19.2127 64.775 19.2127C65.3632 19.2127 65.84 19.6912 65.84 20.2814Z"
                                fill="#999999"></path>
                            <path
                                d="M48.4078 9.42614L48.8714 10.2292C49.0693 10.572 49.5817 10.2762 49.3838 9.93339L48.9202 9.1303L49.7204 8.66829C50.062 8.47107 49.7651 7.95687 49.4235 8.15409L48.6233 8.6161L48.1597 7.81315C47.9618 7.47037 47.4494 7.7662 47.6473 8.109L48.1109 8.91194L47.3107 9.37391C46.9691 9.57113 47.266 10.0853 47.6076 9.88811L48.4078 9.42614Z"
                                fill="#CCCCCC"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M38.7536 31.3405L38.0001 32.1161L37.2466 31.3405L37.0804 31.1726C34.6817 28.7909 31.5249 27.5027 28.2462 27.5C27.9701 27.4998 27.746 27.7234 27.7458 27.9996C27.7456 28.2757 27.9692 28.4998 28.2454 28.5C31.332 28.5026 34.3005 29.7431 36.5293 32.0373L37.6414 33.1821L37.6889 33.2251C37.887 33.383 38.1774 33.3686 38.3587 33.1821L39.4708 32.0373L39.6134 31.8929C44.2056 27.3214 51.5229 27.3695 56.0576 32.0373C60.6475 36.7618 60.6475 44.4275 56.0576 49.152L44.6252 60.6802L44.5838 60.7275C44.4321 60.9243 44.4471 61.2078 44.6281 61.3873C44.8242 61.5818 45.1408 61.5805 45.3352 61.3844L56.7713 49.8525L56.9189 49.6981C61.7413 44.5709 61.6921 36.4019 56.7749 31.3405C51.8001 26.2199 43.7283 26.2199 38.7536 31.3405ZM19.6088 31.6491C19.4039 31.4639 19.0878 31.4799 18.9026 31.6847C14.2488 36.8317 14.3807 44.8622 19.2252 49.8488L37.7699 68.3018L37.8171 68.3433C38.0139 68.4954 38.2979 68.4806 38.4776 68.2994L43.5234 63.2113L43.5648 63.1641C43.7165 62.9673 43.7014 62.6837 43.5204 62.5042C43.3243 62.3098 43.0078 62.3111 42.8133 62.5072L38.1202 67.2396L19.9365 49.146L19.7938 48.9965C15.4636 44.3844 15.39 37.0607 19.6443 32.3554C19.8295 32.1505 19.8136 31.8343 19.6088 31.6491ZM21.1508 29.7104C21.3782 29.5536 21.6895 29.6108 21.8463 29.8381C22.0031 30.0654 21.9459 30.3768 21.7186 30.5336C21.4496 30.7191 21.1877 30.9164 20.9332 31.1254C20.7198 31.3007 20.4047 31.2698 20.2295 31.0564C20.0542 30.843 20.0851 30.5279 20.2985 30.3526C20.5746 30.1259 20.8589 29.9117 21.1508 29.7104ZM26.7473 28.0825C26.7016 27.8102 26.4437 27.6265 26.1714 27.6723C25.1907 27.8371 24.2316 28.119 23.314 28.5138C23.0603 28.623 22.9432 28.9171 23.0523 29.1707C23.1614 29.4244 23.4556 29.5415 23.7092 29.4324C24.5531 29.0693 25.4352 28.81 26.3371 28.6584C26.6094 28.6127 26.7931 28.3548 26.7473 28.0825Z"
                                fill="#666666"></path>
                            <path
                                d="M52.7844 35.3761C55.5631 38.3924 55.5631 43.2985 52.7844 46.3148C52.5973 46.5179 52.6102 46.8342 52.8133 47.0213C53.0164 47.2084 53.3327 47.1954 53.5198 46.9923C56.6512 43.5932 56.6512 38.0977 53.5198 34.6986C53.3327 34.4955 53.0164 34.4825 52.8133 34.6696C52.6102 34.8567 52.5973 35.173 52.7844 35.3761Z"
                                fill="#CCCCCC"></path>
                        </svg>
                    </span>
                    <p class="text-[12px] mb-[10px] text-center"> {{$t('这里是空的:-(')}}</p>
                    <!-- <button class="login_btn">{{$t('登录/注册')}}</button> -->
                </div>
            </div>

            <van-list v-if="collectlist.length > 0" v-model:loading="loading" loading-text="loading" :finished="finished" :finished-text="$t('没有更多了')" @load="onLoad">
                <div class="flex  justify-between  w-full pt-4 mt-[16px] px-2 bg-base-selector-color">
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
                                    <span class="text-[12px] font-bold text-[#222]">{{$sysCurrency}}{{ item.min_price }}</span>
                                    <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.buy > 0'>{{
                                        item.buy
                                    }}+
                                        {{$t('出售')}}</span>
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
                                    <span class="text-[12px] font-bold text-[#222]">{{$sysCurrency}}{{ item.min_price }}</span>
                                    <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.buy > 0'>{{
                                        item.buy
                                    }}+
                                        {{$t('出售')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </van-list>

            <div class="bg-white border-t border-[#e5e5e5] fixed w-full bottom-0 z-[5] h-[48px] max-w-[425px] px-4"
                v-if="isclear">
                <div class="flex justify-between items-center h-full">
                    <div class="flex items-center" @click="allselect">
                        <span v-if="!all">
                            <svg t="1740142639004" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" p-id="2463" width="30" height="30">
                                <path
                                    d="M511.671007 127.934764c-211.93183 0-383.745964 171.803901-383.745964 383.745964s171.814134 383.743918 383.745964 383.743918c211.951273 0 383.764384-171.802878 383.764384-383.743918S723.62228 127.934764 511.671007 127.934764zM511.671007 855.032704c-189.624778 0-343.332533-153.707754-343.332533-343.341742 0-189.644221 153.707754-343.362208 343.332533-343.362208 189.643198 0 343.370395 153.716964 343.370395 343.362208C855.040379 701.32495 701.314205 855.032704 511.671007 855.032704z"
                                    fill="#CCCCCC" p-id="2464"></path>
                            </svg>
                            
                        </span>
                        <span v-else>
                            <svg t="1740200235204" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" p-id="2960" width="30" height="30">
                                <path
                                    d="M512 922.182057c-226.53748 0-410.18308-183.644577-410.18308-410.18308S285.46252 101.81692 512 101.81692s410.18308 183.644577 410.18308 410.18308S738.53748 922.182057 512 922.182057zM729.058594 327.418075 466.190675 594.737376 315.981604 441.983343l-50.091862 51.280944 199.931518 203.317639 312.797076-318.094732L729.058594 327.418075z"
                                    fill="#020202" p-id="2961"></path>
                            </svg>
                        </span>
                        <div class="text-[14px] text-[#222]">{{$t('全部')}}</div>
                    </div>
                    <div class="flex items-center text-[15px]" @click="deletecoll">
                        <div class="pr-[40px] text-[#bf4123]">{{$t('删除')}}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white px-[1.6px]" v-if="!isclear">
                <div class="p-4 text-[12px]">
                    <h3 class=" font-bold">Heart It.</h3>
                    <p class="text-[#999]">Store everything you love on one page.</p>
                    <div class="pt-2 flex justify-between items-center">
                        <div class="">
                            <div>Think about it before purchasing it.</div>
                            <div>Get notification about out-of-stock items</div>
                        </div>
                        <img src="../assets/image/wishlist.gif" class="w-[50px]" alt="">
                    </div>
                </div>
                <div class="flex relative justify-center items-center mx-auto my-0 like">
                    <div class="font-bold overflow-hidden text-ellipsis whitespace-nowrap text-[12px]">You May Also Like
                    </div>
                </div>
                <van-list v-model:loading="loading2" loading-text="loading" :finished="finished2" finished-text="没有更多了" @load="onLoad2">
                    <div class="flex justify-between w-full mt-[16px] px-2 bg-base-selector-color">
                        <div class=" w-[32%]">
                            <div v-for="(item, index) in group_likeList[0]" class="mb-[10px] bg-white"
                                @click="router.push(`/goodsdetail?id=${item.id}`)">
                                <img :src="item.img" alt="" class="rounded-[2px]">
                                <div class="flex flex-col">
                                    <div class="flex items-center">
                                        <span v-if="item.cashBack"
                                            class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                item.cashBack }}</span>
                                        <span
                                            class="ml-[3px] max-w-[120px] text-[12px] text-nowrap overflow-hidden whitespace-nowrap text-ellipsis">{{
                                                item.title }}</span>
                                    </div>
                                    <div class="flex items-center mt-[5px]">
                                        <span class="text-[12px] font-bold text-[#222]">{{$sysCurrency}}{{ item.min_price }}</span>
                                        <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.buy > 0'>{{
                                            item.buy
                                            }}+
                                            {{$t('出售')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" w-[32%]">
                            <div v-for="(item, index) in group_likeList[1]" class="mb-[10px] bg-white"
                                @click="router.push(`/goodsdetail?id=${item.id}`)">
                                <img :src="item.img" alt="" class="rounded-[2px]">
                                <div class="flex flex-col">
                                    <div class="flex items-center">
                                        <span v-if="item.cashBack"
                                            class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                item.cashBack }}</span>
                                        <span
                                            class="ml-[3px] max-w-[120px] text-[12px] text-nowrap overflow-hidden whitespace-nowrap text-ellipsis">{{
                                                item.title }}</span>
                                    </div>
                                    <!-- <div v-if="item.score > 0">
                            <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00" allow-half />
                        </div> -->
                                    <div class="flex items-center mt-[5px]">
                                        <span class="text-[12px] font-bold text-[#222]">{{$sysCurrency}}{{ item.min_price }}</span>
                                        <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.classify > 0'>{{
                                            item.classify
                                            }}+{{$t('出售')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" w-[32%]">
                            <div v-for="(item, index) in group_likeList[2]" class="mb-[10px] bg-white"
                                @click="router.push(`/goodsdetail?id=${item.id}`)">
                                <img :src="item.img" alt="" class="rounded-[2px]">
                                <div class="flex flex-col ">
                                    <div class="flex items-center">
                                        <span v-if="item.cashBack"
                                            class="text-[8px] pl-[2px] pr-[2px] rounded-[2px] border-[0.5px] border-[#fa6338] text-[#fa6338]">{{
                                                item.cashBack }}</span>
                                        <span
                                            class="ml-[3px] max-w-[120px] text-[12px] text-nowrap overflow-hidden whitespace-nowrap text-ellipsis">{{
                                                item.title }}</span>
                                    </div>
                                    <!-- <div v-if="item.score > 0">
                            <van-rate disabled disabled-color="#00FF00" :size="15" void-color="#00FF00" allow-half />
                        </div> -->
                                    <div class="flex items-center mt-[5px]">
                                        <span class="text-[12px] font-bold text-[#222]">{{$sysCurrency}}{{ item.min_price }}</span>
                                        <span class="ml-[2px] text-[#767676] text-[10px]" v-if='item.classify > 0'>{{
                                            item.classify
                                            }}+{{$t('出售')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </van-list>
                <!-- <div class="py-2 px-4 bg-[white]">
                    <div class="masonry">
                        <div class="masonry-item" v-for="item in group_likeList">
                            <div class="relative">
                                <img :src="item.img" alt="">
                                <div class=" absolute bottom-2 right-1 z-[2] text-[black] w-[25px] h-[25px] rounded-full flex justify-center items-center"
                                    style="background-color: hsla(0, 0%, 100%, .8);">
                                    <van-icon name="shopping-cart-o" size="25px" />
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-[12px]">{{$sysCurrency}}</div>
                                <div class="text-[14px]">11.7</div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>
<script setup>
import { computed, onActivated, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { UserService } from '@/api/user'
import { AuthService } from "@/api/home";
const router = useRouter();
const loading = ref(false);
const finished = ref(false);
const isclear = ref(false)
const pageInfo = reactive({
    page: 1,
    limit: 10,
    type: 2
})
const collectlist = ref([])
const group_collectlist = ref([[], []])
//收藏列表的id
const itemsIds = ref([])
const onLoad = async () => {
    const res = await UserService.collectlist({ ...pageInfo })
    itemsIds.value = []
    collectlist.value = [...collectlist.value, ...res.items]
    itemsIds.value = collectlist.value.map(item => item.id);
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
const pageInfo2 = reactive({
    page: 1,
    limit: 10,
    classify: 2
})
const loading2 = ref(false)
const finished2 = ref(false)
const likeList = ref([])
const group_likeList = ref([[], [], []])
const onLoad2 = async () => {
    const res = await AuthService.prodect_list({ ...pageInfo2 })
    likeList.value = [...likeList.value, ...res.dataList]
    group_likeList.value = [[],[],[]]
    likeList.value.forEach((item, index) => {
        group_likeList.value[index % 3].push(item);
    });
    if (likeList.value.length < res.total) {
        loading2.value = false
        pageInfo2.page++
    } else {
        finished2.value = true
    }
}
//选中当前收藏id
const collectid = ref([])
const handlesele = (id) => {
    if (collectid.value.includes(id)) {
        let index = collectid.value.findIndex(item => item == id)
        collectid.value.splice(index, 1);
        console.log(collectid.value)
    } else {
        collectid.value.push(id)
    }

}

const all = computed(() => {
    return itemsIds.value.every(id => collectid.value.includes(id)) && collectid.value.every(id => itemsIds.value.includes(id))
})
const allselect = () => {
    if (all.value) {
        collectid.value = []
        return
    }
    collectid.value = []
    collectid.value.push(...itemsIds.value)
}
//删除收藏
const deletecoll = async () => {
    if(!collectid.value.length > 0){
        return;
    }
    let ids = collectid.value.join(',')
    const res = await UserService.collect({ id: ids ,type:2})
    collectlist.value = [];
    group_collectlist.value = [[], []]
    collectid.value = []
    onLoad()
}
</script>
<style lang="less" scoped>
.active-tab {
    color: #222222;
    border-bottom: 3px solid #222222;
}

.login_btn {
    font-size: 14px;
    /* min-width: 2.4rem; */
    /* height: .96rem;
    line-height: .94rem; */
    padding: .32rem .32rem;
    border-color: transparent;
    background-color: #222222;
    color: #fff;
    position: relative;
    margin: 0;
    display: inline-block;
    max-Width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    word-wrap: normal;
    box-sizing: border-box;
    border-width: 1px;
    border-style: solid;
    border-radius: 0;
    outline: none;
    background-image: none;
    background-clip: border-box;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-text-decoration: none;
    text-decoration: none;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-transition: background .2s, border-color, .2s, color .2s;
    transition: background .2s, border-color, .2s, color .2s;
}

.like::before {
    content: "";
    width: 6px;
    height: 6px;
    background-color: #ccc;
    transform: rotate(45deg);
    margin-bottom: 1px;
    margin-right: .52rem;
}

.like::after {
    content: "";
    width: 6px;
    height: 6px;
    background-color: #ccc;
    transform: rotate(45deg);
    margin-bottom: 1px;
    margin-left: .52rem;
}

.masonry {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;

    /* 瀑布流项之间的间距 */
    .masonry-item {
        border-radius: 8px 8px 0px 0px;

        /* max-height: 150px; */
        img {
            background-color: #f6f6f6;
            width: 100%;
            /*  max-height: 150px; */
            border-radius: 6px 6px 0px 0px;
        }
    }

}

.wishlist-content {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;

}

.wishlist-content {
    border-radius: 8px;
}

.wishlist-content-item-title {
    max-width: 160px;

    @media (min-width:420px) {
        max-width: 190px;
    }
}
</style>