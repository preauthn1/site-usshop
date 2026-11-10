<template>
    <div class="ibu_hotel_h5">
        <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 bg-[#fff]" z-index="2" :border="false"
            @click-left="router.back()">
            <template #left>
                <div class="flex items-center">
                    <van-icon name="arrow-left" color="#0f294d" size="20" />
                </div>
            </template>
            <template #title>
                <div>
                    <svg width="120" height="24" viewBox="0 0 120 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <mask id="mask0_26_5" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="120"
                            height="24">
                            <rect width="120" height="24" fill="#D9D9D9" />
                        </mask>
                        <g mask="url(#mask0_26_5)">
                            <path
                                d="M26.3359 8.75879L23.3779 8.77148C23.3102 7.71354 22.9082 6.88411 22.1719 6.2832C21.444 5.68229 20.4665 5.38184 19.2393 5.38184C18.2236 5.38184 17.3942 5.61882 16.751 6.09277C16.1077 6.56673 15.7861 7.15495 15.7861 7.85742C15.7861 8.56836 16.0697 9.15234 16.6367 9.60938C17.2038 10.0579 18.3929 10.515 20.2041 10.9805C22.9548 11.6745 24.749 12.4277 25.5869 13.2402C26.4333 14.0527 26.8564 15.1868 26.8564 16.6426C26.8564 18.403 26.1963 19.8206 24.876 20.8955C23.5641 21.9619 21.8079 22.4951 19.6074 22.4951C17.2884 22.4951 15.4814 21.9365 14.1865 20.8193C12.9001 19.7021 12.2103 18.0941 12.1172 15.9951H15.1387C15.2233 17.2054 15.6761 18.1449 16.4971 18.8135C17.3265 19.4821 18.4479 19.8164 19.8613 19.8164C20.9277 19.8164 21.7995 19.5583 22.4766 19.042C23.1621 18.5257 23.5049 17.891 23.5049 17.1377C23.5049 16.8415 23.4076 16.5072 23.2129 16.1348C23.0267 15.7539 22.7855 15.4281 22.4893 15.1572C21.9984 14.7087 20.9066 14.277 19.2139 13.8623C18.8499 13.7692 18.5749 13.7015 18.3887 13.6592C16.2982 13.109 14.8213 12.4108 13.958 11.5645C13.0947 10.7096 12.6631 9.55013 12.6631 8.08594C12.6631 6.52865 13.2936 5.26335 14.5547 4.29004C15.8242 3.30827 17.5085 2.81738 19.6074 2.81738C21.6471 2.81738 23.2721 3.35482 24.4824 4.42969C25.7012 5.49609 26.319 6.93913 26.3359 8.75879ZM32.5739 3.2998H35.6843V10.6631H44.5329V3.2998H47.6432V22H44.5329V13.4434H35.6843V22H32.5739V3.2998ZM54.1732 3.2998H67.5795V6.04199H57.2328V10.9297H66.7796V13.5449H57.2328V19.1943H67.9222V22H54.1732V3.2998ZM73.5127 3.2998H86.919V6.04199H76.5723V10.9297H86.1192V13.5449H76.5723V19.1943H87.2618V22H73.5127V3.2998ZM92.8523 3.2998H96.153L104.862 17.29V3.2998H107.871V22H104.595L95.8357 8.00977V22H92.8523V3.2998Z"
                                fill="#2346ff" />
                        </g>
                    </svg>
                </div>
            </template>
        </van-nav-bar>
        <div class="search-box-com">
            <div class="search-box-siting"></div>
            <div class="search-box-container">
                <div class="search-box">
                    <div class="search-box-body">
                        <div class="date-room">
                            <span class="search-box-date">3月5日–3月6日</span>
                            <div class="dividing-line"></div>
                            <span class="search-box-room">2名成人</span>
                        </div>
                    </div>
                    <van-icon name="search" size="5.33333vw" color="#0f294d" />
                </div>
            </div>
        </div>
        <div>
            <div class="photo-slider">
                <div class="album-swiper-container">
                    <div class="album-swiper-block-wrapper">
                        <van-swipe class="album-swiper-wrapper" indicator-color="white">
                            <van-swipe-item v-for="item in 10">
                                <img src="https://ak-d.tripcdn.com/images/0200n120009mkx9sd8BED_W_750_1624_R5.webp"
                                    alt="">
                            </van-swipe-item>
                            <template #indicator="{ active, total }">
                                <div class="custom-indicator">{{ active + 1 }}/{{ total }}</div>
                            </template>
                        </van-swipe>
                        <div class="image-swiper-arrow-box left detail-top-album-slide-arrow-box">
                            <i></i>
                        </div>
                        <div class="image-swiper-arrow-box right detail-top-album-slide-arrow-box">
                            <i></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="htl-basecard with-wrapper hotelinfo htl-basecard-poi-g htl-basecard-b htl-basecard-search-b">
                <div class="baseinfo-container baseinfo-search-b">
                    <div class="nameinfo">
                        <div class="nameline">
                            <span>深圳寶安機場RANZ蘭茲國際酒店</span>
                            <div class="star-badge">
                                <span class="star-box">
                                    <i class="level diamond" v-for="item in 3"></i>
                                </span>
                                <i class="badge special"></i>
                            </div>
                        </div>
                    </div>
                    <div style="color: rgb(15, 41, 77);">Shenzhen Baoan Airport Lanzi International Hotel</div>
                </div>
                <div>
                    <div class="hotel-info-address">
                        <span class="left-address">航城街道新瑞路1號801, 寶安區, 深圳</span>
                    </div>
                </div>
                <div class="comment-wrapper with-arrow width-background">
                    <div class="comment">
                        <div class="card">
                            <span class="score" dir="ltr">4.8
                                <span>/5</span>
                            </span>
                            <span class="tag">极好</span>
                            <div class="comment-number">6,993则评价</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hotelinfo-map-wrapper-g">
                <div class="hotel-map-title">
                    <div class="map-title-content">地理位置優越</div>
                </div>
                <div class="hotelinfo-location-poi-b">
                    <div class="poi-item">
                        <i class="hotel-icons-subway"></i>
                        <div class="poi-item-title">地鐵：后瑞</div>
                        <div class="poi-item-desc">240米</div>
                    </div>
                    <div class="poi-item">
                        <i class="hotel-icons-subway"></i>
                        <div class="poi-item-title">地鐵：黃田</div>
                        <div class="poi-item-desc">1.0公里</div>
                    </div>
                    <div class="poi-item">
                        <i class="hotel-icons-airplane"></i>
                        <div class="poi-item-title">機場：寶安國際機場</div>
                        <div class="poi-item-desc">6.6公里</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="hotel-rooms">
            <div class="physic-room-list">
                <div class="physic-room-container" style="top: 66.0992px;">
                    <div class="physic-room-info-top">
                        <div class="physic-room-img">
                            <span>4</span>
                            <img src="https://ak-d.tripcdn.com/images/0204a120009mnbgxs5340_W_405_540_R5.webp" alt="">
                        </div>
                        <div class="physic-room-info">
                            <div class="top">
                                <div class="left">
                                    <h2>舒適大床房
                                        <van-icon name="arrow" size="4.26667vw" />
                                    </h2>
                                    <div class="physic-room-info-container">
                                        <div class="bed-info">
                                            <van-icon name="src/assets/css/hotelimage/bed_icon.svg" size="4.26667vw" />
                                            <span class="bed-icon-text p-s-4">1張大床</span>
                                        </div>
                                        <ul class="facility-info">
                                            <li class="facility-item">
                                                <van-icon class="mr-[1.06667vw]"
                                                    name="src/assets/css/hotelimage/no_smoking_icon.svg"
                                                    size="4.26667vw" />
                                                禁煙
                                            </li>
                                            <li class="facility-item">
                                                <van-icon class="mr-[1.06667vw]"
                                                    name="src/assets/css/hotelimage/wifi_icon.svg" size="4.26667vw" />
                                                免費 Wi-Fi
                                            </li>
                                            <li class="facility-item">
                                                <van-icon class="mr-[1.06667vw]"
                                                    name="src/assets/css/hotelimage/area_icon.svg" size="4.26667vw" />
                                                25平方米
                                            </li>
                                            <li class="facility-item">
                                                <van-icon class="mr-[1.06667vw]"
                                                    name="src/assets/css/hotelimage/stairs_icon.svg" size="4.26667vw" />
                                                5-6樓
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="price-info" :style="{ display: isExpand ? 'none' : 'flex' }">
                                    <span>-42%</span>
                                    <div class="price">
                                        <p class="delete-price m-e-4">{{ $sysCurrency }}482</p>
                                        <div>
                                            <span>{{ $sysCurrency }}</span>263
                                        </div>
                                    </div>
                                </div>
                                <div class="arrow" @click="isExpand = !isExpand" :class="{ 'arrow-up': isExpand }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="bottom: 1px;" v-if="!isExpand"></div>
                <div :style="{ display: isExpand ? 'block' : 'none' }">
                    <div class="sale-room-container first padding-bottom">
                        <div class="sale-room-container-inner" @click="toDetails">
                            <div class="card-info-wrapper">
                                <div class="card-tags-box">
                                    <div class="meal-info">
                                        <p class="highlight">包括1份早餐</p>
                                    </div>
                                    <ul class="room-label-list">
                                        <li class="room-label has-icon ic_advantage">
                                            <div>
                                                免費取消
                                                <span> 3月5日18:00前</span>
                                            </div>

                                        </li>
                                    </ul>
                                    <div class="room-desc room-desc-index0 ic_new_confirmation_line">即時確認</div>
                                    <div class="guest-icons with-guest-icon">最多可入住：2名成人</div>
                                </div>
                            </div>
                            <p class="pay-method">網上預付</p>
                            <div class="price-info-container">
                                <div class="price-info">
                                    <div class="price-label-container">
                                        <div class="price-label promotion">
                                            <span>首次預訂優惠</span>
                                        </div>
                                        <div class="price-label discount">
                                            <span>-42%</span>
                                        </div>
                                    </div>
                                    <div class="price highlight">
                                        <div @click="noteAction">
                                            <img src="@/assets/css/hotelimage/info_icon_s.svg" alt="">
                                        </div>
                                        <p class="delete-price">{{ $sysCurrency }}482</p>
                                        <div>
                                            <span>{{ $sysCurrency }}</span>263
                                        </div>
                                    </div>
                                    <div class="price-explain">總額（連稅及附加費）：HK$302</div>
                                </div>
                                <div class="tripui-h5-btn-middle">
                                    <van-button class="" type="success" color="#3264ff" @click="toSubmitOrder">{{
                                        $t('预订')
                                        }}</van-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="bottom: 1px;"></div>
                    <div class="sale-room-container padding-bottom">
                        <div class="sale-room-container-inner">
                            <div class="card-info-wrapper" @click="">
                                <div class="card-tags-box">
                                    <div class="meal-info">
                                        <p class="highlight">包括1份早餐</p>
                                    </div>
                                    <ul class="room-label-list">
                                        <li class="room-label has-icon ic_advantage">
                                            <div>
                                                免費取消
                                                <span> 3月5日18:00前</span>
                                            </div>

                                        </li>
                                    </ul>
                                    <div class="room-desc room-desc-index0 ic_new_confirmation_line">即時確認</div>
                                    <div class="guest-icons with-guest-icon">最多可入住：2名成人</div>
                                </div>
                            </div>
                            <p class="pay-method">網上預付</p>
                            <div class="price-info-container">
                                <div class="price-info">
                                    <div class="price-label-container">
                                        <div class="price-label promotion">
                                            <span>首次預訂優惠</span>
                                        </div>
                                        <div class="price-label discount">
                                            <span>-42%</span>
                                        </div>
                                    </div>
                                    <div class="price highlight">
                                        <div @click="noteAction">
                                            <img src="@/assets/css/hotelimage/info_icon_s.svg" alt="">
                                        </div>
                                        <p class="delete-price">{{ $sysCurrency }}482</p>
                                        <div>
                                            <span>{{ $sysCurrency }}</span>263
                                        </div>
                                    </div>
                                    <div class="price-explain">總額（連稅及附加費）：HK$302</div>
                                </div>
                                <div class="tripui-h5-btn-middle">
                                    <van-button class="" type="success" color="#3264ff" @click="toSubmitOrder">{{
                                        $t('预订')
                                        }}</van-button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <van-calendar :style="{ height: '100%' }" v-model:show="showCalender" :default-date="defaultDate" :round="false"
        :title="$t('选择日期')" type="range" @select="selectedDateAction">
        <template #footer>
            <div class="w-full">
                <div class="basic-calendar flex justify-center items-center">
                    <span class="text-[#ff6f00] text-1.3rem">{{ $t('入住和退房时间均为酒店当地时间') }}</span>
                </div>
                <div class=" bg-[#fff] py-[1.6rem]" v-if="showComfirmButton">
                    <div @click="onConfirm"
                        class="bg-[#3264ff] rounded-[0.4rem] flex justify-center items-center h-[44px]">
                        <span class="text-[#fff] text-[1.6rem]">{{ confirmText }}</span>
                    </div>
                </div>
            </div>
        </template>
    </van-calendar>
    <van-popup v-model:show="showBottom" position="bottom" :style="{ height: '100%' }">
        <div class=" h-full flex flex-col justify-start items-center overflow-x-hidden">
            <div class="flex py-[1.6rem] px-[1.6rem] divider-line w-full items-center bg-[#fff]">
                <div class="flex-1" @click="showBottom = false">
                    <van-icon name="cross" size="2.2rem" />
                </div>
                <div class="flex-4">
                    <span class="text-[1.8rem] text-[#0f294d] font-bold text-center">{{ $t('住客及房間') }}</span>
                </div>
                <div class="flex-1"></div>
            </div>
            <div class="flex-1 w-full overflow-y-auto bg-[#f5f7fa]">
                <div class=" px-[1.6rem] w-full bg-[#fff]">
                    <div class="m-guest_item">
                        <div class="flex items-center h-[5.6rem]">
                            <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                {{ $t('成人') }}
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
                            </div>
                            <van-stepper v-model="tempChildNum" min="0" theme="round" button-size="22" disable-input />
                        </div>
                    </div>
                </div>
                <div class=" px-[1.6rem] w-full bg-[#fff]">
                    <div class="m-guest_item">
                        <div class="flex items-center h-[5.6rem]">
                            <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                {{ $t('房间') }}
                            </div>
                            <van-stepper v-model="tempRoomNum" min="1" theme="round" button-size="22" disable-input />
                        </div>
                        <div class="text-[#051a37] text-[1.3rem] pb-[1.6rem]">{{ $t('如有更多住客，可以考虑增加房间数量') }}</div>
                    </div>
                </div>
            </div>
            <div class="flex justify-start items-center p-[1.6rem] bg-[#fff] w-full h-[8rem]">
                <van-button @click="confirmRoom" type="success" color="#3264ff" block>{{ $t('确定') }}</van-button>
            </div>
        </div>
    </van-popup>

    <van-popup v-model:show="showFilter" position="bottom" style="background-color: #00000000;"
        :style="{ height: '75%' }">
        <div class="h-full bg-[#fff] rounded-t-[1.2rem] flex flex-col">
            <div class="flex p-[1.6rem] rounded-t-[1.2rem] divider-line w-full items-center bg-[#fff]">
                <div class="flex-1" @click="showFilter = false">
                    <van-icon name="cross" size="2.2rem" />
                </div>
                <div class="flex-4">
                    <span class="text-[1.8rem] text-[#0f294d] font-bold text-center"></span>
                </div>
                <div class="flex-1  text-right" @click="confirmFilter">
                    <span class="text-[1.6rem] text-[#3264ff]">{{ $t('完成') }}</span>
                </div>
            </div>
            <div class=" overflow-y-auto relative">
                <div class="pt-[1.6rem] px-[2.4rem] pb-[0.4rem]">
                    <div class="flex flex-col">
                        <span class="text-[#0f294d] text-[1.8rem] font-bold">{{ $t('价格') }}</span>
                        <span class="text-[#455873] text-[1.4rem]">{{ $t('每房每晚价格（未连税及附加费）') }}</span>
                    </div>
                    <div class="divider-line pb-[1.6rem]">
                        <span class="text-[#0f294d] text-[1.4rem] font-medium">{{ sliderTitleText }}</span>
                        <div class="mt-[1.8rem] mx-[2rem] mb-[2.2rem] relative">
                            <van-slider class=" relative" step="50" min="0" max="1600" v-model="sliderValue"
                                bar-height="0.4rem" button-size="2.4rem" range @change="priceRangeChangeAction"
                                @drag-start="sliderStart" @drag-end="sliderEnd" @update:model-value="sliderUpdate">
                            </van-slider>
                            <div class="slider_tip" :style="{ left: sliderTipLift }" v-if="showSliderTip">
                                {{ sliderTipText }}
                            </div>
                        </div>
                        <div class=" inline-block mt-[0.8rem] mr-[0.8rem]" v-for="(item, index) in sliderSelectData"
                            :key="index" @click="selectedSliderItem(item, index)">
                            <span :class="{ 'price-range-detail-active': sliderSelectedIndex == index }"
                                class="bg-[#f5f7fa] inline-block rounded-[0.4rem] text-[1.4rem] p-[1.2rem] text-[#0f294d] slider-select-span">{{
                                    getSelectorText(item) }}</span>
                        </div>
                    </div>
                    <div class="py-[1.6rem]">
                        <div class="flex items-center">
                            <div class="text-[#0f294d] text-[1.6rem] font-bold">{{ $t('钻级') }}</div>
                            <van-icon name="question-o" size="1.6rem" class=" px-[0.4rem]" />
                        </div>
                        <div class="mt-[1.2rem] flex">
                            <div class="flex-1 mr-[0.8rem]" v-for="(item, index) in startData" :key="index"
                                @click="selectedStartIndexAction(item)">
                                <span class="star-item" :class="{ 'star-item-active': selectedStartText == item }">{{
                                    item }}
                                    <van-icon name="gem" size="1.6rem" />
                                    <div class="icon-ok-wrap"
                                        :class="{ 'icon-ok-wrap-active': selectedStartText == item }">
                                        <van-icon name="success" size="1.4rem" class="icon-ok" />
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-[243m]">
                        <div class="mb-[1.2rem]">
                            <span class="text-[1.6rem] font-bold text-[#0f294d]">{{ $t('住客评分') }}</span>
                        </div>
                        <div class="mt-[1.2rem] flex">
                            <div v-for="(item, index) in scoreData" :key="index" class="rate-item"
                                :class="{ 'rate-item-active': scoreText == item }" @click="selectedScoreAction(item)">
                                {{ item
                                }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </van-popup>
    <van-popup v-model:show="showSearchView" position="bottom" style="background-color: #00000000;"
        :style="{ height: '100%' }">
        <div class="h-full bg-[#fff] flex flex-col">
            <div class="flex items-center w-full py-[0.8rem] px-[1.2rem]">
                <van-icon name="cross" size="1.7rem" class="mr-[1.1rem]" @click="showSearchView = false" />
                <div class="search-inputWrapper">
                    <van-icon name="search" size="1.4rem" class="mr-[1rem]" />
                    <van-field v-model="value" :placeholder="$t('输入目的地或酒店')" />
                </div>
            </div>
            <div class="bg-[#dadfe6] h-[0.1rem] w-full"></div>
            <div class="flex-1 flex flex-col w-full overflow-x-hidden overflow-y-scroll">
                <div class="bg-[#fff]">
                    <div class=" p-[1.2rem] flex items-center justify-start">
                        <van-icon name="location" size="1.6rem" color="#006ff6" class="mr-[0.8rem]" />
                        <span v-if="locationLoading"
                            class="text-[#006ff6] whitespace-normal overflow-hidden text-ellipsis flex-1 text-[1.5rem] text-left">{{
                                $t('目前位置') }}：{{ $t('获取定位中...') }}</span>
                        <span v-else-if="error.length > 0"
                            class="text-[#006ff6] whitespace-normal overflow-hidden text-ellipsis flex-1 text-[1.5rem] text-left">{{
                                $t('目前位置') }}：{{ error }}</span>
                        <span v-else
                            class="text-[#006ff6] whitespace-normal overflow-hidden text-ellipsis flex-1 text-[1.5rem] text-left">{{
                                $t('目前位置') }}：{{ address }}</span>
                    </div>
                    <div class="bg-[#f0f0f0] h-[0.05rem] ml-[3.4rem]"></div>
                    <div v-for="(item, index) in 1" class=" p-[1.2rem] flex items-center justify-start">
                        <van-icon name="map-marked" size="1.6rem" color="#006ff6" class="mr-[0.8rem]" />
                        <span class="text-[#111111] text-[1.5rem] text-left font-medium">{{ $t('贵阳') }}</span>
                        <span class="text-[#aaa] flex-1 text-[1.5rem] text-left ml-[0.9rem]">{{ $t('中国') }}</span>
                    </div>
                </div>
                <div class="flex flex-col justify-start">
                    <div class="bg-[#ebeff5] h-[0.6rem]">
                        <div class="px-[1.2rem] pt-[1.6rem] flex justify-between items-center">
                            <span class="text-[#111] text-left text-[1.8rem]">{{ $t('最近查看过') }}</span>
                            <span class="text-[#006ff6] mx-[0.6rem] text-[1.4rem]">{{ $t('清除记录') }}</span>
                        </div>
                        <div v-for="(item, index) in 2" class="flex flex-col pl-[1.2rem]">
                            <div class="w-full mt-[1.2rem] flex justify-start items-start">
                                <div class="h-[2.2rem] flex items-center">
                                    <van-icon name="underway-o" size="1.8rem" color="#111" class="mr-[0.9rem]" />
                                </div>
                                <div class="flex-1 flex flex-col w-[5rem]">
                                    <span
                                        class="text-[#111] search-record-span text-ellipsis overflow-hidden break-words inline-block whitespace-nowrap text-left text-[1.5rem]">貴陽</span>
                                    <div class="flex-1 mt-[0.4rem] flex justify-start items-center">
                                        <span
                                            class="text-[#555] text-ellipsis overflow-hidden break-words inline-block whitespace-nowrap text-left text-[1.3rem]">3月3日-3月28日</span>
                                        <div class="bg-[#555] mx-[0.8rem] w-[0.1rem] h-[0.8rem]"></div>
                                        <span
                                            class="text-[#555] text-ellipsis overflow-hidden break-words inline-block whitespace-nowrap text-left text-[1.3rem]">2位成人</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-[#f0f0f0] mt-[0.8rem] ml-[2.9rem] h-[0.05rem]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </van-popup>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const isExpand = ref(false);
const { t } = useI18n();
const router = useRouter()

const toSubmitOrder = () => {
    console.log(122221);
    router.push('/hotelsubmitorder')
}

const location = ref(null);
const error = ref('');
const locationLoading = ref(false);
const address = ref('');
const getLocation = () => {
    locationLoading.value = true;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                location.value = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                };
                error.value = '';
                getAddress(position.coords.latitude, position.coords.longitude);
            },
            (err) => {
                locationLoading.value = false;
                error.value = '无法获取地址信息';
            }
        );
    } else {
        locationLoading.value = false;
        error.value = '无法获取地址信息';
    }
};
const getAddress = async (latitude, longitude) => {
    try {
        const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=YOUR_API_KEY`);
        const data = await response.json();
        if (data.status === 'OK') {
            address.value = data.results[0].formatted_address;
        } else {
            error.value = '无法获取地址信息';
            locationLoading.value = false;
        }
    } catch (err) {
        error.value = `请求失败: ${err.message}`;
        locationLoading.value = false;
    }
};
const showSearchView = ref(false);
const toSearchView = () => {
    showSearchView.value = true;
    getLocation();
    // router.push('/hotelsearch')
}

const filterText = ref('');
const clearFilter = () => {
    filterText.value = '';
    sliderTitleText.value = '$0 - $1550+';
    scoreText.value = '';
    selectedStartText.value = '';
    sliderValue.value = [0, 1600];
}
const confirmFilter = () => {
    showFilter.value = false;
    filterText.value = '';
    if (sliderTitleText.value != '$0 - $1550+') {
        filterText.value = filterText.value + sliderTitleText.value;
    }
    if (scoreText.value.length > 0) {
        if (filterText.value.length > 0) {
            filterText.value = filterText.value + ' / ' + scoreText.value;
        } else {
            filterText.value = scoreText.value;
        }
    }
    if (selectedStartText.value.length > 0) {
        if (filterText.value.length > 0) {
            filterText.value = filterText.value + ' / ' + selectedStartText.value;
        } else {
            filterText.value = selectedStartText.value;
        }
    }
}

const scoreData = ref(['3+', '3.5+', '4', '4.5+']);
const scoreText = ref('');
const selectedScoreAction = (item) => {
    if (item == scoreText.value) {
        scoreText.value = '';
    } else {
        scoreText.value = item;
    }

}

const startData = ref(['≤2', '3', '4', '5']);
const selectedStartText = ref('');
const selectedStartIndexAction = (item) => {
    if (item == selectedStartText.value) {
        selectedStartText.value = '';
    } else {
        selectedStartText.value = item;
    }
}

const showFilter = ref(false);
const sliderValue = ref([0, 1600]);
const showSliderTip = ref(false);
const sliderTipLift = ref('0%');
const sliderTipText = ref('');
const sliderTitleText = ref('$0 - $1550+');
const sliderSelectData = ref([[0, 250], [250, 400], [400, 550], [550, 850], [850, 1550], [1550, 1600]]);
const sliderSelectedIndex = ref(-1);
const getSelectorText = (item) => {
    if (item[0] == 0) {
        return t('少于') + '$' + item[1].toString();
    } else if (item[1] == 1600) {
        return t('多于') + '$' + item[0].toString();
    } else {
        return '$' + item[0] + ' - ' + '$' + item[1];
    }
}
const selectedSliderItem = (item, index) => {
    sliderValue.value = [];
    sliderValue.value.push(...item);
    sliderTitleText.value = getSelectorText(item);
    sliderSelectedIndex.value = index;
}
const priceRangeChangeAction = (value) => {
    console.log(value);
}
const sliderUpdate = (value) => {
    let v = value[buttonIndex.value] / 1600;
    sliderTipLift.value = (v * 100).toString() + '%';
    sliderTipText.value = '$' + value[buttonIndex.value];
    if (value[buttonIndex.value] > 1550) {
        sliderTipText.value = '1550+'
    }
    if (value[0] == 0 && value[1] == 1600) {
        sliderTitleText.value = '$' + value[0] + ' - ' + '$' + '1550' + '+';
    } else if (value[0] == 0) {
        sliderTitleText.value = t('少于') + '$' + value[1].toString();
    } else if (value[1] == 1600) {
        sliderTitleText.value = t('多于') + '$' + value[0].toString();
    } else {
        sliderTitleText.value = '$' + value[0] + ' - ' + '$' + value[1];
    }
}
const buttonIndex = ref(0);
const sliderStart = (event) => {
    sliderSelectedIndex.value = -1;
    showSliderTip.value = true;
    const sliderButtons = event.target.closest('.van-slider').querySelectorAll('.van-slider__button-wrapper');
    sliderButtons.forEach((button, index) => {
        if (button.contains(event.target)) {
            buttonIndex.value = index;
        }
    });
}
const sliderEnd = (event) => {
    showSliderTip.value = false;
}


const showBottom = ref(false);
const tempAldultNum = ref(1);
const tempChildNum = ref(0);
const tempRoomNum = ref(1);
const aldultNum = ref(1);
const childNum = ref(0);
const roomNum = ref(1);
const confirmRoom = () => {
    aldultNum.value = tempAldultNum.value;
    childNum.value = tempChildNum.value;
    roomNum.value = tempRoomNum.value;
    showBottom.value = false;
}

const confirmText = ref(t('确认（1晚）'));
const daysToStay = ref(t('1晚'));
const showComfirmButton = ref(true);
const showCalender = ref(false);
const tempDate = ref([]);
const selectedDateAction = (item) => {
    if (item.length <= 1) {
        showComfirmButton.value = false;
    } else {
        tempDate.value = item;
        showComfirmButton.value = true;
        confirmText.value = t(`确认（${calculateDaysBetween(item[0], item[1])}晚）`);
    }
}

const onConfirm = () => {
    defaultDate.value = tempDate.value;
    if (defaultDate.value.length == 2) {
        daysToStay.value = t(`${calculateDaysBetween(defaultDate.value[0], defaultDate.value[1])}晚`)
        updateDates(defaultDate.value[0], defaultDate.value[1]);
        showCalender.value = false;
    }
}

const todayDate = ref('');
const todayWeekday = ref('');
const tomorrowDate = ref('');
const tomorrowWeekday = ref('');
const getWeekday = (date) => {
    const weekdays = [t('周日'), t('周一'), t('周二'), t('周三'), t('周四'), t('周五'), t('周六'),];
    return weekdays[date.getDay()];
};
const formatDate = (date) => {
    const month = String(date.getMonth() + 1).padStart(2, '');
    const day = String(date.getDate()).padStart(2, '');
    return `${month}月${day}日`;
};
const updateDates = (today1, nextDay) => {
    const today = today1;
    const tomorrow = nextDay;
    todayDate.value = formatDate(today);
    todayWeekday.value = getWeekday(today);
    tomorrowDate.value = formatDate(tomorrow);
    tomorrowWeekday.value = getWeekday(tomorrow);
};
const calculateDaysBetween = (date1, date2) => {
    const oneDay = 24 * 60 * 60 * 1000; // 一天的毫秒数
    const diffDays = Math.round(Math.abs((date2 - date1) / oneDay));
    return diffDays;
};
const today = new Date();
const tomorrow = new Date(today);
tomorrow.setDate(today.getDate() + 1);
const defaultDate = ref([today, tomorrow]);
updateDates(today, tomorrow);
</script>

<style src="@/assets/css/hotel/hotel.css" scoped></style>