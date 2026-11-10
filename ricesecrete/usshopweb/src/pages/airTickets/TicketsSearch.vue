<template>
    <div class="ContentContainer_page__QrbTV">
        <div class="xtaro-xview">
            <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 bg-[#2346ff]" z-index="2"
                :border="false" @click-left="router.back()">
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
        </div>
        <div class="xtaro-xview styles_list-search-box__Cscrk">
            <div class="xtaro-xview styles_box-head__xt5_b">
                <div v-for="(item, index) in tabData" @click="selectedTab(index)"
                    class="xtaro-xview styles_box-item__OnrzB"
                    :class="{ 'styles_box-item-active__JL0h0': tabSelectedIndex == index }">
                    <span class="taro-text xt-text_no_highlight styles_tab-item-name__zG2Au"
                        :class="{ 'styles_active-item__Q2z7E': tabSelectedIndex == index }">{{ item }}</span>
                </div>
            </div>
            <div class="xtaro-xview styles_selector-wrap__S_qQn">
                <div class="xtaro-xview styles_input-item__9QZgQ styles_mr16__57Lb9 styles_pdr32__AZ4Ce ">
                    <span class="taro-text xt-text_no_highlight">紐約（所有機場）</span>
                </div>
                <div class="xtaro-xview styles_city-transfer__Lex1d">
                    <span class="taro-text xt-text_no_highlight styles_city-transfer-icon__imKO0">
                        <svg t="1741324871425" class="icon" viewBox="0 0 1024 1024" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" p-id="25306" width="1.6rem" height="1.6rem">
                            <path
                                d="M512.436267 49.028267c-255.250133 0-462.171733 206.9216-462.171733 462.171733s206.9216 462.171733 462.171733 462.171733 462.171733-206.9216 462.171733-462.171733S767.6864 49.028267 512.436267 49.028267zM761.528533 554.202667 603.771733 554.202667 484.3968 753.166933l-56.855467 0 71.0688-198.964267L384.8992 554.202667l-56.837333 56.846933L285.421867 611.0496l39.5872-92.372267-39.5872-92.3904 42.64 0 56.837333 56.8544L484.3968 483.141333l-56.855467-198.974933L484.3968 284.1664l113.693867 198.974933 163.438933 0c19.618133 0 35.518933 15.9072 35.518933 35.536C797.045333 538.295467 781.147733 554.202667 761.528533 554.202667z"
                                fill="#3264ff" p-id="25307"></path>
                        </svg>
                    </span>
                </div>
                <div class="xtaro-xview styles_input-item__9QZgQ styles_pdl32__qt_Y7">
                    <span class="taro-text xt-text_no_highlight">倫敦（所有機場）</span>
                </div>
            </div>
            <div class="xtaro-xview styles_selector-wrap__S_qQn styles_mgt16__5TGqS">
                <div class="xtaro-xview styles_input-item__9QZgQ ">
                    <span class="taro-text xt-text_no_highlight">3月8日 週六</span>
                </div>
                <div class="xtaro-xview styles_input-item__9QZgQ ml-[0.8rem]" v-if="tabSelectedIndex==1">
                    <span class="taro-text xt-text_no_highlight">3月8日 週六</span>
                </div>
            </div>
            <div class="xtaro-xview styles_classtype-passenger__yGdYv">
                <div>
                    <span class="taro-text xt-text_no_highlight styles_classtype__BiJLn">經濟艙／特選經濟艙</span>
                    <span class="taro-text xt-text_no_highlight styles_class-select-icon__E_88k"
                        style="display:inline-block">
                        <van-icon name="arrow-down" size="1.2rem" />
                    </span>
                </div>
                <div class="xtaro-xview styles_passenger__aDN88">
                    <div class="xtaro-xview styles_passenger-item__G7SrA">

                        <span class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                            <div class="ml-[0.8rem] mr-[0.4rem]">
                                <svg t="1741002865147" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="36331" id="mx_n_1741002865149" width="18"
                                    height="18">
                                    <path
                                        d="M273.066667 716.8l28.910933-346.999467A68.266667 68.266667 0 0 1 370.005333 307.2h283.989334a68.266667 68.266667 0 0 1 68.027733 62.600533L750.933333 716.8h-102.4l-34.133333 307.2h-204.8l-34.133333-307.2H273.066667z m238.933333-716.8a136.533333 136.533333 0 1 1 0 273.066667 136.533333 136.533333 0 0 1 0-273.066667z"
                                        fill="#ced2d9" p-id="36332"></path>
                                </svg>
                            </div>
                            1
                        </span>
                        <span class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                            <div class="ml-[0.8rem] mr-[0.4rem]">
                                <svg t="1741002387094" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="3443" id="mx_n_1741002387095" width="18"
                                    height="18">
                                    <path d="M512 144m-144 0a144 144 0 1 0 288 0 144 144 0 1 0-288 0Z" p-id="3444"
                                        fill="#ced2d9"></path>
                                    <path
                                        d="M903.3 282.9L722.8 463.4c-12 12-18.8 28.3-18.8 45.3V1008c0 8.8-7.2 16-16 16H560c-8.8 0-16-7.2-16-16V736h-64v272c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16V508.6c0-17-6.7-33.3-18.8-45.3L120.7 282.9c-31.1-31.1-32.1-81.8-1.4-113.2 15.7-16 36.5-24.1 57.2-24.1 20.5 0 41 7.8 56.6 23.4l141.6 141.6c6 6 14.1 9.4 22.6 9.4h229.2c8.5 0 16.6-3.4 22.6-9.4L790.9 169c15.6-15.6 36.1-23.4 56.6-23.4 20.8 0 41.5 8 57.2 24.1 30.7 31.4 29.7 82.1-1.4 113.2z"
                                        p-id="3445" fill="#ced2d9"></path>
                                </svg>
                            </div>
                            0
                        </span>
                        <span class="flex items-center text-[13px] font-medium whitespace-nowrap text-[#0f294d]">
                            <div class="ml-[0.8rem] mr-[0.4rem]">
                                <svg t="1741002466956" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="13483" id="mx_n_1741002466958" width="18"
                                    height="18">
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
        <div>
            <div class="xtaro-xview" v-for="i in 4">
                <div @click="router.push('/ticketsdetail')" class="xtaro-xview standardColor_light_bgColor_contentwhite__VZlzu standardColor_light_borderColor_tertiarygray__vJrkF FlightCard_border__xhvt8">
                    <div class="xtaro-xview standardColor_light_bgColor_contentwhite__VZlzu"></div>
                    <div class="xtaro-xview standardColor_light_bgColor_contentwhite__VZlzu Background_wrapper__HhWIq">
                        <div class="xtaro-xview standardColor_light_bgColor_contentwhite__VZlzu styles_flight-top-tags__GR6j_">
                            <div class="xtaro-xview styles_tag-list__x290l" style="background-color: rgba(5, 147, 159, 0.08);">
                                <span class="taro-text xt-text_no_highlight Text_light_color_deepcyan__Tt3Nh styles_tag-list-text__EKhin">
                                    最低價直航
                                </span>
                            </div>
                            <div class="taro-img styles_img__i6WxN">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABQCAYAAACpv3NFAAAAAXNSR0IArs4c6QAAAHhlWElmTU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAIdpAAQAAAABAAAATgAAAAAAAACQAAAAAQAAAJAAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAAECgAwAEAAAAAQAAAFAAAAAA9DxtkwAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAAtJJREFUeAHlm89L40AUx1+svyrdgyB79aIXvS4LggspFKW4XvOPehX8T7x6LLVp0zRpunluB0JM26SZSea918vYICHfz3znk+lhHHh5uwCpnx7EB1Kzr3PPD8UC6J6twP0dym1AZxaC46zkAgCYY/tlAkjlB/1+LBfAevYRgDwJruWH4fEjbwms5fc/vkQAmfrLa0BGflIb8PXqU+HlNcB1w2x4WQB66cYn3fnJBZCTnwIh4zVYID9ZADbMvhwHFMhPTgM2yE8OgC31578EtshPSgO+7fxUcDXyfg1ukR9/ADvkxx/ADvnxBlBCfrwBlJx9hMBTgiXkx7cBJeXHF0CF+vNbAhXkx7UBO3d+KrgaeUmwgvz4AagoP34AKsqPF4A95McLwJ6zjxB4SHAP+fFpwJ7y4wOgRv3pL4Ea8uPSgMo7PxVcjbQlWEN+9AHUlB99ADXlRxuABvnRBqBp9hECTQlqkB/dBmiSH10AGutPbwlolB/VBtTe+angaqQlQY3yowdAs/zoAdAsP1oADMiPFgBDs48QaEjQgPzoNMCQ/OgAMFh/hGD3oanTHxH0f30db1Mzpnu02wGj90B34Pz97AUQp4cbPW+Rf2Dd3+0FEP2c6Q5bdD87HXAaR/B8a3z2EYh9DeguVzAa+UWzZeKafQ1YXPrgDZYmwhbd0y4A50ch3F9/O9pW9OC6rtmzBJzDJby+TnUFK3sfOxqA4Z/ux/D3T1L2wXX9X/sNODpJYPbxmR5qbDw8Qmy3ARj+824MntOY9PLNaQ8AvusH7qStmVcg2gFwvAhg8DArOsurHqypsVkHYOU7wQQeH6c2hEfIzTUAZ/3BtWLWs+0yD6ATLMD3p/DstSa6bOD832YAYNWdSQjj8Tz9SWtlcAVCHwAMHSYRXJ2HcHMT2bLGVdBNYzUAUfpL7TgNGsUrOOkm4AcJ9IIIhkMM3MpGZlOwstf/AYitxeBp2mltAAAAAElFTkSuQmCC" class="taro-img__content taro-img__scale-to-fill">
                            </div>
                        </div>
                        <div class="xtaro-xview FlightCard_bg__8GiY0 FlightCard_card-taglist__Y9t9l" style="background-color: rgb(255, 255, 255);">
                            <div class="xtaro-xview FlightCard_img-cnt__vW_tW">
                                <div class="xtaro-xview FlightCard_img-box__04Kc0">
                                    <div class="taro-img FlightCard_air-logo__R70a3">
                                        <img src="https://static.tripcdn.com/packages/flight/airline-logo/latest/airline_logo/3x/vs.webp" class="taro-img__content taro-img__aspect-fit">
                                    </div>
                                </div>
                            </div>
                            <div class="xtaro-xview FlightCard_container__AUR7J">
                                <div class="xtaro-xview FlightCard_top-container__aGy_U">
                                    <div class="xtaro-xview FlightCard_left-panel__wLahX">
                                        <div class="xtaro-xview">
                                            <div class="xtaro-xview styles_flight-info__G24Xq">
                                                <div class="xtaro-xview TimeAndLocation_wrapper__lLsmY TimeAndLocation_l-wrapper__3APM9" style="align-items: flex-start;">
                                                    <div class="xtaro-xview Time_wrapper___hUUk" style="align-items: flex-start;">
                                                        <span class="taro-text xt-text_no_highlight Text_light_color_black__yRoYs Time_l-time__RB8Wl" style="text-align: left;">
                                                            19:30
                                                        </span>
                                                        
                                                    </div>
                                                    <span class="taro-text xt-text_no_highlight Text_text__single-line__dg7s0 Text_light_color_black__yRoYs Location_location__jH3QH" style="text-align: left;">JFK T4</span>
                                                </div>
                                                <div class="xtaro-xview MetroLine_wrapper__vU_8q MetroLine_top-10__GOaiS">
                                                    <span class="taro-text xt-text_no_highlight Text_light_color_tertiaryblack__JNJ5b MetroLine_duration__ygnSZ" style="font-size: 11px;">6小時45分</span>
                                                    <div class="xtaro-xview StopsOverLine_stops__QPleu">
                                                        <div class="taro-img StopsOverLine_line-ow-h5__Z4yNP">
                                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAKAgMAAAAC4HKLAAAACVBMVEUAAADZ3+Xa3+aTgdPeAAAAAnRSTlMAgJsrThgAAAAtSURBVBjTfckxAQAgDAOwHjygCokTsWeoxEHyJrBC232uu9ztnuTJdZe73fMBoYhReNemOjAAAAAASUVORK5CYII=" class="taro-img__content taro-img__bottom">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="xtaro-xview TimeAndLocation_wrapper__lLsmY TimeAndLocation_l-wrapper__3APM9 TimeAndLocation_gap-span__xmNFN">
                                                    <div class="xtaro-xview Time_wrapper___hUUk" style="align-items: flex-end;">
                                                        <span class="taro-text xt-text_no_highlight Text_light_color_black__yRoYs Time_l-time__RB8Wl" style="text-align: right;">06:00</span>
                                                        <span class="taro-text xt-text_no_highlight Text_text__single-line__dg7s0 Text_light_color_deeporange__303tr Time_badge__uo5No">+1</span>
                                                    </div>
                                                    <span class="taro-text xt-text_no_highlight Text_text__single-line__dg7s0 Text_light_color_black__yRoYs Location_location__jH3QH" data-testid="1_flightlist_arrivalairport_1" style="text-align: right;">LGW S</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="xtaro-xview styles_loose__HGNxm">
                                        <div class="xtaro-xview styles_wrapper__bub8L">
                                            <span class="taro-text xt-text_no_highlight diff_price__kXipg Price_medium-20__G53V8" style="color: rgb(50, 100, 255);">HK$2,351</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="xtaro-xview">
                                    <div class="xtaro-xview styles_container__qzYuU" style="margin-top: 5px;">
                                        <div class="xtaro-xview styles_airline-comfort__XLTuM">
                                            <span class="taro-text xt-text_no_highlight">
                                                <span class="taro-text xt-text_no_highlight Text_light_color_tertiaryblack__JNJ5b styles_airline-name__vW4_P">挪威大西洋航空</span>
                                            </span>
                                            <div class="xtaro-xview standardColor_light_bgColor_tertiarygray__0XMvf" style="display: flex; flex-grow: 0; flex-shrink: 0; width: 1px; height: 10px; margin-left: 4px; margin-right: 4px;"></div>
                                            <span class="taro-text xt-text_no_highlight Text_light_color_tertiaryblack__JNJ5b styles_airline-name__vW4_P">波音 787-9</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="xtaro-xview styles_new-container__GPCmw styles_modern-container__9e1Br">
                                    <div class="xtaro-xview">
                                        <div class="xtaro-xview styles_collapse__x9MNd" style="max-width: 335.953px; flex-wrap: wrap;">
                                            <div class="xtaro-xview standardColor_light_borderColor_tertiarygray__vJrkF styles_box__d1Sho" style="max-width: 335.953px;">
                                                <span class="taro-text xt-text_no_highlight standardColor_light_color_deepcyan__nvIkC CategoryIcon_icon__CkUYY">
                                                    <svg t="1741584586142" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19360" width="16" height="16"><path d="M654 285.9h-48.6V124.7c0-16.7-13.6-30.3-30.3-30.3H429c-16.7 0-30.3 13.6-30.3 30.3v161.2h-28.1c-52.1 0-94.6 42.4-94.6 94.6v429.7c0 52.1 42.4 94.6 94.6 94.6h22.1c0 1.1-0.1 2.1-0.1 3.2 0 26.8 15 46.3 35.7 46.3 20.7 0 35.7-19.5 35.7-46.3 0-1.1 0-2.2-0.1-3.2h105.7c0.1 26.6 15.1 45.9 35.7 45.9s35.6-19.3 35.7-45.9h13c52.1 0 94.6-42.4 94.6-94.6V380.5c0-52.2-42.5-94.6-94.6-94.6zM437 170.3c0-14.1 11.5-25.6 25.6-25.6h79c14.1 0 25.6 11.5 25.6 25.6v115.6H437V170.3z m2.6 737.7c-2.6 2-6.5 3.7-11.3 3.7s-8.7-1.8-11.3-3.7c1.8-1.3 4.2-2.6 7-3.2h8.7c2.7 0.6 5.1 1.8 6.9 3.2z m165.7 0.1c-4.5 0-8.2-1.5-10.8-3.3H616c-2.5 1.8-6.2 3.3-10.7 3.3z m93.3-97.9c0 24.6-20 44.6-44.6 44.6H370.6c-24.6 0-44.6-20-44.6-44.6V380.5c0-24.6 20-44.6 44.6-44.6H654c24.6 0 44.6 20 44.6 44.6v429.7z" fill="#05939f" p-id="19361"></path><path d="M403.9 435.4h40v347.1h-40zM492.3 435.4h40v347.1h-40zM580.7 430.7h40v347.1h-40z" fill="#05939f" p-id="19362"></path></svg>
                                                </span>
                                                <span class="taro-text xt-text_no_highlight Text_light_color_tertiaryblack__JNJ5b styles_content__t6y7p styles_text-flex__E7W7A">已包括手提行李</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()

const tabData = ref([t('单程'), t('来回')]);//, t('多个目的地')
const tabSelectedIndex = ref(0);
const selectedTab = (index) => {
    tabSelectedIndex.value = index;
    console.log(tabSelectedIndex.value);
}
</script>
<style src="@/assets/css/ticket/ticket.css" scoped></style>