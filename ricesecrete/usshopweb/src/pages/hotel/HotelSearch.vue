<template>
    <div class="bg-[#2346ff]">
        <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 bg-[#2346ff]" z-index="2" :border="false"
            @click-left="router.back()">
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
        <div class="list-ceiling-v8 decline">
            <div class="m-search-v8__wrapper2">
                <div class="m-search-v8__container_search">
                    <div class="m-search-v8__static" @click="showSearch = true">
                        <div class="m-search-v8__static-content">
                            <span
                                class="text-left block font-medium overflow-hidden text-ellipsis whitespace-nowrap break-words">附近的酒店</span>
                            <div class="info">
                                <span class="text-[1.3rem] text-[#0f294d]">3月4日–3月28日 | </span>
                                <span class="text-[1.3rem] text-[#0f294d]">2位成人</span>
                            </div>
                        </div>
                        <div>
                            <van-icon name="search" size="2.6rem" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-barB-V8 new-optimization">
                <div class="m-bar-filters">
                    <div class="m-barB-V8__wrapper" v-for="(item, index) in filterItems" :key="index"
                        @click="filterItemAction(item)">
                        <div class="m-barB-V8__item">
                            <div class="item-bar-wrapper">
                                <div class="item-bar">
                                    {{ item }}
                                </div>
                                <span class="m-barB-V8__supper-dot"></span>
                            </div>
                            <div>
                                <svg t="1741081469186" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="15273" id="mx_n_1741081469187" width="10"
                                    height="10">
                                    <path
                                        d="M477.098667 709.717333a46.805333 46.805333 0 0 0 69.802666 0l296.021334-340.736c24.32-28.032 3.328-70.314667-34.944-70.314666H216.021333c-38.272 0-59.306667 42.282667-34.944 70.314666l296.021334 340.736z"
                                        fill="#0f294d" p-id="15274"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-hotels-v8 gradient m-hotels-list">
            <div class="m-hotels-v8-tip">
                <span>3962间住宿符合您的搜寻条件</span>
            </div>
            <div class="cp-hotel-card-decorator-v8" v-for="(item, index) in 10" :key="index">
                <div class="cp_base_card-v8 cp_hotel_card-v8" @click="router.push('/hoteldetails')">
                    <div class="left_card-v8">
                        <div class="hotel_img">
                            <van-image width="100%" height="100%" fit="cover" position="center center"
                                src="https://ak-d.tripcdn.com/images/1mc1812000hznj8wc63F2_R_300_600_R5_Q50_D.jpg_.webp?proc=crop/w_2221,h_3331,x_1610,y_0,8582" />
                        </div>
                    </div>
                    <div class="htl_cp_right_card-v8">
                        <div class="right-card-box">
                            <div class="htl_cp_hotel_base-v8 ib">
                                <div class="hotel_name">
                                    <span class="hotel_name_inner">貴陽北站德福中心亞朵S酒店</span>
                                    <span class="htl_cp_star_list-v8 ib">
                                        <van-icon size="1.2rem" color="#ffb400" name="gem" />
                                        <van-icon size="1.2rem" color="#ffb400" name="gem" />
                                        <van-icon size="1.2rem" color="#ffb400" name="gem" />
                                        <van-icon size="1.2rem" color="#ffb400" name="gem" />
                                    </span>
                                    <van-icon class="htlf-ic_guest_rating special" name="good-job" size="1.2rem"
                                        color="#fff" />
                                    <span class="hotel_en_name">Atour S Hotel, Defu Center, Guiyang North Station</span>
                                </div>
                            </div>
                            <div class="htl_cp_comments-v8">
                                <div class="score height-fitcontent">
                                    <div class="text text-[#fff]">
                                        <b>4.9</b>
                                        <span>/5</span>
                                    </div>
                                </div>
                                <div class="right">
                                    <p class="score_desc">完美</p>
                                    <p class="comments">49则评价</p>
                                </div>
                            </div>
                            <div class="htl_cp_location-v8">距離您的位置800米 | 鄰近茅台商務中心</div>
                        </div>
                        <div class="right-card-room-detailB b">
                            <div class="htl_cp_price-v8">
                                <div class="htl_cp_price-v8-bottom">
                                    <div class="htl_cp_price-v8_box_B">
                                        <div class="lable-hotel-tag-v8 htl-encourage-follow">166人已收藏</div>
                                    </div>
                                    <div class="htl_cp_price-v8_two_b">
                                        <div class="sold_price un-price-v8 sold_price_version_b">
                                            <span class="un-price_currency">{{ $sysCurrency }}</span>
                                            <span>405</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <van-popup v-model:show="showSearch" position="top" :style="{ height: 'auto' }">
            <div class="m-mask__content">
                <div class="m-search-v8__wrapper">
                    <div class="m-search-v8">
                        <div class="m-search-v8-item m-search-v8__city">
                            <div class="item-content ellipsis">
                                <div class="ellipsis input-text">
                                    <span class="word">附近的酒店</span>
                                </div>
                                <van-icon class="locate-icon htlf" name="guide-o" size="2rem" />
                            </div>
                        </div>
                        <div class="m-search-v8-line"></div>
                        <div class="m-search-v8-item m-search-v8__date">
                            <div class="item-content">
                                <div class="check-date">
                                    <p class="value">3月4日 週二–3月28日 週五</p>
                                </div>
                                <div class="night-num">
                                    <span class="ellipsis">24晚</span>
                                </div>
                            </div>
                        </div>
                        <div class="m-search-v8-line"></div>
                        <div class="m-search-v8-item m-search-v8__person">
                            <div class="item-content">
                                <span>
                                    <span class="number">2</span>
                                    名成人
                                </span>
                                <span>
                                    <span class="number">0</span>
                                    名小童
                                </span>
                                <span>
                                    <span class="number">2</span>
                                    间
                                </span>
                            </div>
                        </div>
                        <div class="m-search-v8-line"></div>
                        <div class="m-search-v8__btn">
                            <van-button type="success" color="#3264ff" block @click="toHotelList">{{ $t('搜寻')
                            }}</van-button>
                        </div>
                    </div>
                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showSort" position="bottom" :style="{ height: 'auto' }">
            <div class="m-drawer__content animation" style="max-height: 798.37px;">
                <div class="pop-header divider-line radius">
                    <div class="pop-header__left">
                        <van-icon name="cross" size="2.2rem" @click="showSort = false" />
                    </div>
                    <span class="pop-header__title">{{ $t('排序') }}</span>
                    <div class="pop-header__right"></div>
                </div>
                <div class="m-drawer__child_content">
                    <div class="m-select">
                        <ul class="m-select_list">
                            <li v-for="item in filterSort" @click="sortSelectedAction(item)">
                                <div class="li-item">
                                    <div class="itemTxt" :class="{ 'active': item == sortSelected }">
                                        <span>{{ item }}</span>
                                    </div>
                                    <van-icon v-if="sortSelected == item" name="success" size="1.8rem"
                                        color="#3264ff" />
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </van-popup>
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
                                <van-stepper v-model="tempAldultNum" min="1" theme="round" button-size="22"
                                    disable-input />
                            </div>
                        </div>
                    </div>
                    <div class=" px-[1.6rem] w-full bg-[#fff]">
                        <div class="m-guest_item">
                            <div class="flex items-center h-[5.6rem]">
                                <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                    {{ $t('小童') }}
                                </div>
                                <van-stepper v-model="tempChildNum" min="0" theme="round" button-size="22"
                                    disable-input />
                            </div>
                        </div>
                    </div>
                    <div class=" px-[1.6rem] w-full bg-[#fff]">
                        <div class="m-guest_item">
                            <div class="flex items-center h-[5.6rem]">
                                <div class=" flex-1 text-[#0f294d] text-[1.6rem]">
                                    {{ $t('房间') }}
                                </div>
                                <van-stepper v-model="tempRoomNum" min="1" theme="round" button-size="22"
                                    disable-input />
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
                                    <span class="star-item"
                                        :class="{ 'star-item-active': selectedStartText == item }">{{
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
                                    :class="{ 'rate-item-active': scoreText == item }"
                                    @click="selectedScoreAction(item)">
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
    </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
import { sysCurrency } from '@/utils/global';
import { HotelService } from '@/api/hotel.js'

const decrease = async () => {
    console.log(122222);
    await HotelService.hotelList({ city_code: 'LON',type:'hotel' });
}

decrease();

const filterSort = ref([t('SHEEN推荐'), t('距离（由近到远）'), t('热门评价'), t('最低价格'), t('最高价格'), t('钻级（由高至低）')]);
const sortSelected = ref(t('SHEEN推荐'));
const sortSelectedAction = (item) => {
    sortSelected.value = item;
    showSort.value = false;
}
const showSort = ref(false);
const filterItemAction = (item) => {
    if (item == t('排序')) {
        showSort.value = true;
    }
}
const showSearch = ref(false);
const filterItems = ref([t('筛选'), t('位置'), t('排序')]);

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
    sliderTitleText.value = sysCurrency.value + '0 - ' + sysCurrency.value + '1550+';
    scoreText.value = '';
    selectedStartText.value = '';
    sliderValue.value = [0, 1600];
}
const confirmFilter = () => {
    showFilter.value = false;
    filterText.value = '';
    if (sliderTitleText.value != sysCurrency.value + '0 - ' + sysCurrency.value + '1550+') {
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
const sliderTitleText = ref(sysCurrency.value + '0 - ' + sysCurrency.value + '1550+');
const sliderSelectData = ref([[0, 250], [250, 400], [400, 550], [550, 850], [850, 1550], [1550, 1600]]);
const sliderSelectedIndex = ref(-1);
const getSelectorText = (item) => {
    if (item[0] == 0) {
        return t('少于') + sysCurrency.value + item[1].toString();
    } else if (item[1] == 1600) {
        return t('多于') + sysCurrency.value + item[0].toString();
    } else {
        return sysCurrency.value + item[0] + ' - ' + sysCurrency.value + item[1];
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
    sliderTipText.value = sysCurrency.value + value[buttonIndex.value];
    if (value[buttonIndex.value] > 1550) {
        sliderTipText.value = '1550+'
    }
    if (value[0] == 0 && value[1] == 1600) {
        sliderTitleText.value = sysCurrency.value + value[0] + ' - ' + sysCurrency.value + '1550' + '+';
    } else if (value[0] == 0) {
        sliderTitleText.value = t('少于') + sysCurrency.value + value[1].toString();
    } else if (value[1] == 1600) {
        sliderTitleText.value = t('多于') + sysCurrency.value + value[0].toString();
    } else {
        sliderTitleText.value = sysCurrency.value + value[0] + ' - ' + sysCurrency.value + value[1];
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