<template>
    <div>
        <div class="" id="main" style="display: block;padding-bottom: unset; background-color: #f0f2f5; min-height: 100vh;">
            <div>
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
            <div class="background-section_NBP" style="--color: #3264ff;">
                <div class="solid_NBZ"></div>
                <div class="fade_NBE"></div>
            </div>
            <div class="header-blue_7u"></div>
            <div></div>
            <div class="">
                <div>
                    <div class="flightcard_7m">
                        <div class="card-head-wrapper_N8W">
                            <div class="od-city_N8s">
                                <span class="city_N8S">紐約
                                    <van-icon name="src/assets/css/ticket/images/arrow_right.svg"
                                        class="uranus-font icon-arrow_N8j" />
                                </span>
                                <span class="city_N8S">倫敦</span>
                            </div>
                            <van-button plain type="primary" size="mini" color="#3264ff">
                                <div class=" flex items-center">
                                    <span class="px-[10px] text-[13px]">更改航班</span>
                                    <span>
                                        <van-icon name="src/assets/css/ticket/images/change_edit.svg" size="14" />
                                    </span>
                                </div>
                            </van-button>
                        </div>
                        <div class="flt-detail-wrapper_NNI">
                            <div></div>
                            <div class="seg-info-wrapper_NNu">
                                <div class="detail_NNU">
                                    <div class="tag_NNz">去程</div>
                                    <div>3月12日 週三</div>
                                    <div class="duration_NNm">7小時10分</div>
                                </div>
                            </div>
                            <div class="segment-wrapper_NGp">
                                <div class="time_NGj">20:01</div>
                                <div class="line_NGk lt-rt-radius_NGS first-line_NG1"></div>
                                <div class="airport_NGc">
                                    <span>JFK</span>
                                    <span>&nbsp;紐約 肯尼迪國際機場&nbsp;</span>
                                    <span class="">T4</span>
                                    <div class="info-wrapper_NGY">
                                        <div class="flt-relevant-info_NGt">
                                            <div>
                                                <span class="item_NG7">
                                                    <van-icon
                                                        name="https://static.tripcdn.com/packages/flight/airline-logo/latest/airline_logo/3x/dl.png"
                                                        size="12" />
                                                    達美航空 DL5992
                                                </span>
                                                <span><br>
                                                    <i class="i-share_NGy"></i>營運者：&nbsp;
                                                    <van-icon
                                                        name="https://static.tripcdn.com/packages/flight/airline-logo/latest/airline_logo/3x/vs.png"
                                                        size="12" />
                                                    VS046
                                                </span>
                                                <span>
                                                    <span class="splitter_NGH"></span>
                                                    <span class="item_NG7 icon-list_NGi">
                                                        <van-icon name="src/assets/css/ticket/images/air_denner.svg"
                                                            size="12" />
                                                        <van-icon name="src/assets/css/ticket/images/charge_icon.svg"
                                                            size="12" />
                                                    </span>
                                                    <span class="splitter_NGH"></span>
                                                    <span class="item_NG7">空中巴士A350
                                                        <van-icon name="arrow" />
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="segment-wrapper_NGp">
                                <div class="time_NGj">
                                    <div class="day-plus_NGq orange-color_NGu">3月13日</div>
                                    06:55
                                </div>
                                <div class="line_NGk lb-rb-radius_NGF first-line_NG1"></div>
                                <div class="airport_NGc">
                                    <span class="">LHR</span>
                                    <span>&nbsp;倫敦 希思羅機場&nbsp;</span>
                                    <span class="">T3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="prime-info-container_i2" style="background: unset;">
                    <div class="multi-cabin-title_iG">選擇艙位及價格</div>
                    <div class="price-tip_i3">連稅及附加費</div>
                    <div class="m-ota-wp-new_NfB m-ota-wp-more_NMn">
                        <div style="display: block;" class="m-pos-item-recommend_Nfa" v-for="i in 4">
                            <div class="m-pos-item_Nf2">
                                <div class="label-box_NfO">
                                    <div class="icon-recommand_NfJ">
                                        <span class="left_Nfh"></span>
                                        <span class="uranus-font ic-thumpsup_solid ic-well_NfR">
                                            <van-icon name="src/assets/css/ticket/images/air_recommend.svg" size="12" />
                                        </span>
                                        推薦航班
                                    </div>
                                    <div class="top-label_NCy"></div>
                                    <div class="price-book_Nfg">
                                        <div class="price-box_Nfc">
                                            <div class="prc_Nft">
                                                <span class="avg-prc_Nf7">
                                                    <span>
                                                        <span class="c">HK$</span>
                                                        <em class="p">2,351</em>
                                                    </span>
                                                </span>
                                            </div>
                                            <p class="cabin-txt_NfU">經濟艙</p>
                                        </div>
                                        <div class="e-bok_NMo">
                                            <van-button type="primary" size="normal" color="#3264ff"
                                                @click="selectedTicket">
                                                <div class=" flex items-center">
                                                    <span class="font-medium text-[16px]">選擇</span>
                                                </div>
                                            </van-button>
                                        </div>
                                    </div>
                                    <div class="policy-issue_NMX">
                                        <div class="issue-list_NKj">
                                            <div class="virtual-container_NKq">
                                                <p class="issue_NKF">
                                                    <van-icon name="src/assets/css/ticket/images/social-no-luggage.svg"
                                                        size="14" />
                                                    <span class="txt_NK5">無手提行李限額</span>
                                                </p>
                                                <p class="issue_NKF">
                                                    <van-icon name="src/assets/css/ticket/images/tuoyun_icon.svg"
                                                        size="14" />
                                                    <span class="txt_NK5">托运行李：1件X5kg</span>
                                                </p>
                                                <p class="issue_NKF">
                                                    <van-icon name="src/assets/css/ticket/images/no_icon.svg"
                                                        size="14" />
                                                    <span class="txt_NK5">不设退款</span>
                                                </p>
                                                <p class="issue_NKF">
                                                    <van-icon name="src/assets/css/ticket/images/no_icon.svg"
                                                        size="14" />
                                                    <span class="txt_NK5">不可更改</span>
                                                </p>
                                                <p class="issue_NKF">
                                                    <van-icon name="src/assets/css/ticket/images/notice_icon.svg"
                                                        size="14" />
                                                    <span class="txt_NK5">只限1-3位乘客预订</span>
                                                </p>
                                            </div>
                                            <p class="virtual-btn-1_NKS"></p>
                                        </div>
                                        <div class="detail-open-policy open-policy_NMa nowrap_NM2">
                                            <span class="tripui-h5-click open-policy-btn_NMJ">機票政策及行李限額
                                                <van-icon name="arrow" />
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="price-change-time-txt_i6">
                <div class="time-bar_N8C">
                    <span>資料擷取時間：19:23:10
                        <van-icon name="src/assets/css/ticket/images/wenhao_icon.svg" size="13" />
                    </span>
                    <div class="time-bar-img_N8T"></div>
                </div>
            </div>
            <div class="faq-container_N8F">
                <van-swipe class="" :autoplay="3000" indicator-color="white">
                    <van-swipe-item>
                        <div class="faq-detail faq-detail_8j in-detail_8E swiper-slide-active"
                            style="max-width: 388px; min-width: 388px; width: 388px; margin-right: 12px;">
                            <div class="question_8q">
                                <div class="notice-img_8S"></div>
                                <span class="notice-info_8F">甚麼是聯營航班？</span>
                            </div>
                            <div class="ans-list_85">
                                <div class="answer_8g">
                                    <div style="--line: 0;">
                                        <span>此為聯營航班，表示座位可由一間航空公司出售，而航班則由另一間航空公司營運</span>
                                    </div>
                                </div>
                            </div>
                            <div class="more-tips_8i no-circle_8Z">更多航班貼士
                                <van-icon name="arrow" />
                            </div>
                        </div>
                    </van-swipe-item>
                    <van-swipe-item>
                        <div class="faq-detail faq-detail_8j in-detail_8E swiper-slide-active"
                            style="max-width: 388px; min-width: 388px; width: 388px; margin-right: 12px;">
                            <div class="question_8q">
                                <div class="notice-img_8S"></div>
                                <span class="notice-info_8F">如果我的機票不包括免費託運行李限額，我是否仍可以託運行李？</span>
                            </div>
                            <div class="ans-list_85">
                                <div class="answer_8g">
                                    <div class="circle_8c"></div>
                                    <div style="--line: 1;">
                                        <span class="notice-info_8F">如果我的機票不包括免費託運行李限額，我是否仍可以託運行李？</span>
                                    </div>
                                </div>
                                <div class="answer_8g">
                                    <div class="circle_8c"></div>
                                    <div style="--line: 1;">
                                        <span>如機票沒有免費託運行李限額，您可以在 SHEEN 購買託運行李限額</span>
                                    </div>
                                </div>
                                <div class="answer_8g">
                                    <div class="circle_8c"></div>
                                    <div style="--line: 1;">
                                        <span>如果在 SHEEN 無法購買額外行李限額，我們建議您在航空公司網站或機場櫃檯購買</span>
                                    </div>
                                </div>
                            </div>
                            <div class="more-tips_8i no-circle_8Z">更多航班貼士
                                <van-icon name="arrow" />
                            </div>
                        </div>
                    </van-swipe-item>
                    <van-swipe-item>
                        <div class="faq-detail faq-detail_8j in-detail_8E swiper-slide-active"
                            style="max-width: 388px; min-width: 388px; width: 388px; margin-right: 12px;">
                            <div class="question_8q">
                                <div class="notice-img_8S"></div>
                                <span class="notice-info_8F">為甚麼機票價格會持續變動？</span>
                            </div>
                            <div class="ans-list_85">
                                <div class="answer_8g">
                                    <div style="--line: 0;">
                                        <span>機票價格會根據市場情況頻繁變動。航空公司會因應不同因素持續調整票價，例如機票銷售情況、淡旺季、航線熱門程度、距離出發日期還剩多少日以及剩餘機票數量。</span>
                                    </div>
                                </div>
                            </div>
                            <div class="more-tips_8i no-circle_8Z">更多航班貼士
                                <van-icon name="arrow" />
                            </div>
                        </div>
                    </van-swipe-item>
                </van-swipe>
            </div>
        </div>


        <div id="book-page" style="padding-bottom: unset; background-color: #f0f2f5; min-height: 100vh; display: none;">
            <div class="book-header_lp">
                <div>
                    <van-nav-bar class="max-w-[425px] my-0 mx-auto top-0 left-0 right-0 bg-[#2346ff]" z-index="2"
                        :border="false" @click-left="router.back()">
                        <template #left>
                            <div class="flex items-center">
                                <van-icon name="arrow-left" color="#fff" size="20" />
                                <div>
                                    <svg width="120" height="24" viewBox="0 0 120 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_26_5" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0"
                                            y="0" width="120" height="24">
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
                <div class="in-book_Uf progress-bar-container_uz">
                    <div class="step-text_Uo">乘客資料</div>
                    <div class="progress-wrapper_Ur animated-progress_U9">
                        <div class="progress-item_UA">
                            <div class="circle_UK">
                                <span class="num_UC">1</span>
                            </div>
                        </div>
                        <div class="progress-item_UA inactive_UT">
                            <div class="circle_UK"><span class="num_UC">2</span></div>
                        </div>
                        <div class="progress-item_UA inactive_UT">
                            <div class="circle_UK"><span class="num_UC">2</span></div>
                        </div>
                    </div>
                </div>
                <div class="sub-header_lj">
                    <div class="sub-header-content_xFL">
                        <div class="sub-header-item_xFM">
                            <div class="progress-wrapper_xSE finish_xSH">
                                <van-icon name="src/assets/css/ticket/images/gou_icon.svg" size="20" />
                            </div>
                            <span class="text-[13px]">立即行動，鎖定當前價格和艙位</span>
                        </div>
                    </div>
                </div>
                <div class="home-page-bg-content_lq"></div>
            </div>
            <div class="book-page_lf pb-[100px]">
                <div class="simple-flight-info_lN">
                    <div></div>
                    <div class="content_lG">
                        <div>
                            <div class="flight-outline_xcO">
                                <div class="wrapper_xcJ">
                                    <div class="content_xch">
                                        <div class="lf_xcR">
                                            <div>
                                                <div class="city-container_xcq">
                                                    <div class="city-depart-arrive_xcS"><span
                                                            class="">紐約</span><span>&nbsp;-&nbsp;</span><span
                                                            class="">倫敦</span>
                                                    </div>
                                                    <div><span class="divider-line_xc1">|</span><span>3月12日
                                                            週三</span><span class="divider-line_xc1">|</span></div>
                                                    <div><span class="newline_xcv">20:01&nbsp;-&nbsp;06:55</span><span
                                                            class="days_xc6">&nbsp;+1</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="rt_xcW" data-testid="book_flightInfo_right_arrow">
                                            <div class="details_xcs"><van-icon name="arrow-down" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="policy-button_xtR items-center">
                            <div class="left_xtv"></div>
                            <div class="content_xt6">政策 | 行李限額</div>
                            <van-icon name="arrow" />
                        </div>
                        <div data-testid="book_notice" class="m-notice-list-container_xYG">
                            <div class="tripui-h5-click knowing-layer-anchor_xYL" data-testid="book_notice_title">
                                <div class="title-content_xYX">
                                    <van-icon name="src/assets/css/ticket/images/notice_icon.svg" />
                                    <div class="anchor-title_xYM">先了解再出發</div>
                                </div>
                                <van-icon name="arrow" />
                            </div>
                            <div class="single-notice_xYK" data-testid="book_notice_singleNotice">付款確認後，機票將於2小時內出票</div>
                        </div>
                    </div>
                </div>
                <div data-testid="passenger-major-block" id="majorBlock" class="passenger_lL">
                    <section id="passenger" class="passenger_x5g">
                        <div class="passenger-list_NNG passenger-list-b_NNL">
                            <div data-testid="book_passenger_container_header" class="header-title_xym">
                                <div data-testid="book_passenger_container_header_title">乘客</div>
                                <div data-testid="name-guidelines" class="guidelines_xk0">姓名填寫指南
                                    <van-icon name="info-o" class="ml-[4px]" size="14px" />
                                </div>
                            </div>
                        </div>
                        <div class="passenger-edit_x5c">
                            <div style="transition: height 0.3s ease-in-out; height: auto; overflow: visible;">
                                <section class="wrapper_2y">
                                    <div class="scroll ">
                                        <div id="editInfo" class="passenger-edit_2w passenger-edit-border_2U">
                                            <div class="take-photo-box_x0l guest-box_x0u">
                                                <div class="take-photo-inner_x0z">
                                                    <van-icon name="src/assets/css/ticket/images/camera_icon.svg"
                                                        class="" size="20px" />

                                                    <span class="text-[#3264ff] text-[16px]">自動填寫護照資料</span>
                                                </div>
                                            </div>
                                            <ul class="non-card-box_OA">
                                                <li>請確保您輸入的姓名與辦理登機時使用的證件上的姓名一致</li>
                                                <li>為確保您的旅程順利，請確保乘客的旅遊文件由旅程完結日起至少6個月內有效</li>
                                                <li>確認後，訂單將無法更改。如果您的姓名有誤，您可能<b>無法登機</b>並<b>將被收取取消費用</b>。</li>
                                            </ul>
                                            <div class="input-form_xls input-form-b_xlj input-form-new-guest_xlp">
                                                <div class="text-input_aH text-input-b_2N">
                                                    <div class="input-area_ay"><label for="surname"
                                                            class="">姓氏</label><input id="surname" name="surname"
                                                            type="email" placeholder="" data-testid="textInput_surname"
                                                            value=""></div>
                                                    <div class="ic-close-wrapper hide"><i
                                                            class="ic-ad-close2 uranus-font ic-delete_solid"></i></div>
                                                </div>
                                                <div class="text-input_aH text-input-b_2N">
                                                    <div class="input-area_ay"><label for="givenName"
                                                            class="">名字</label><input id="givenName" name="givenName"
                                                            type="email" placeholder=""
                                                            data-testid="textInput_givenName" value=""></div>
                                                    <div class="ic-close-wrapper hide"><i
                                                            class="ic-ad-close2 uranus-font ic-delete_solid"></i></div>
                                                </div>
                                                <div id="gender" class="radio_xmT"><label>證件上的性別</label>
                                                    <div class="container_xm9">
                                                        <div class="item_Nos">
                                                            <div class="radio-item_Noj">
                                                                <div class="radio-icon_Noq not-selected_NoF"></div>
                                                                <div data-hj-suppress="true" class="label_No1">女性</div>
                                                            </div>
                                                        </div>
                                                        <div class="item_Nos">
                                                            <div class="radio-item_Noj">
                                                                <div class="radio-icon_Noq not-selected_NoF"></div>
                                                                <div data-hj-suppress="true" class="label_No1">男性</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="country" class="picker_cK picker-box-b_cJ">
                                                    <div class="container_cC">
                                                        <label>國籍</label>
                                                        <div class="value_cT" data-hj-suppress="true"></div>
                                                        <van-icon name="arrow" />
                                                    </div>
                                                </div>
                                                <div id="birthday" class="picker_cK picker-box-b_cJ">
                                                    <div class="container_cC">
                                                        <label>出生日期</label>
                                                        <div class="value_cT" data-hj-suppress="true"></div>
                                                        <van-icon name="arrow" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ktn-component_2u">
                                                <div class="ktn-box-b-ktn_xUE ktn-box-new_xUZ ktn-box-guest-b_xUb">
                                                    <div class="ktn-box_xU5">
                                                        <div class="ktn_xUg">
                                                            <div class="card-title_xUc">使用已知旅客號碼（KTN）<div
                                                                    class="triplet-switch left">
                                                                    <div class="icon-wrapper"><span class="check"
                                                                            style="border-color: rgb(50, 100, 255); background-color: rgb(218, 223, 230);"><span
                                                                                class="circle triplet-animated"></span></span>
                                                                    </div>
                                                                    <div class="text-wrapper"><span
                                                                            class="switch-text"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ffp-component_20 mb_2l">
                                                <div class="ffp-box-b_xzG ffp-box-new_xzN ffp-box-guest-b_xz3">
                                                    <div class="ffp-box_xUH">
                                                        <div class="ffp_xUy">
                                                            <div class="card-title_xUI">
                                                                <div>飛行常客計劃</div><span class="card-switch_xUw">
                                                                    <van-icon name="arrow-down" />
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="guest-check_2k">
                                                <van-button type="primary" block size="normal" color="#3264ff"
                                                    @click="">
                                                    <div class=" flex items-center">
                                                        <span class="font-medium text-[16px]">儲存</span>
                                                    </div>
                                                </van-button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="add-box-b_xI4 ad-box_xI2 no-border_xIv">
                            <div data-testid="book_passenger_container_addPassenger_btn"
                                class="tripui-h5-click add-btn_xIh no-border_xIv intensify_xI6">
                                <div class="horizont-center_xIO">
                                    <div class="add-icon_xIR">
                                        <van-icon name="add-o" />
                                    </div>
                                    輸入乘客資料
                                </div>
                            </div>
                        </div>
                    </section>
                    <div id="contactInfo" class="contact-info_x57 margin-space_x5V contact-info-b_xg3">
                        <ul class="inputList_x5P">
                            <p class="contact-title_x5Z" data-testid="book_contactInfo_title">聯絡資料</p>
                            <li id="contactName">
                                <div class="text-input_aH text-input-b_2N">
                                    <div class="input-area_ay"><label for="name"
                                            class="email-label_aI">聯絡人姓名</label><input id="name" name="name"
                                            autocomplete="off" maxlength="100" type="text" placeholder="聯絡人姓名"
                                            data-test-id="book_contactInfo_name_input" value=""></div>
                                    <div class="ic-close-wrapper hide" data-testid="book_contactInfo_name_delete_icon">
                                        <i class="ic-ad-close2 uranus-font ic-delete_solid"></i></div>
                                </div>
                            </li>
                            <li id="contactEmail">
                                <div class="text-input_aH text-input-b_2N">
                                    <div class="input-area_ay"><label for="email"
                                            class="email-label_aI">電郵</label><input id="email" name="email"
                                            autocomplete="off" maxlength="254" type="email" placeholder="確認郵件將傳送至此電郵地址"
                                            data-test-id="book_contactInfo_email_input" value="">
                                    </div>
                                    <div class="ic-close-wrapper hide" data-testid="book_contactInfo_email_delete_icon">
                                        <i class="ic-ad-close2 uranus-font ic-delete_solid"></i></div>
                                </div>
                                <div id="id-false" class="">

                                    <div style="display: none;">
                                        <div
                                            id="ibu-slider-v4-id-FltH5ContactEmail-ibu_inlinecomponent_query_account_h5_pic">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li id="contactPhone">
                                <div class="lab-tel_x5E">
                                    <span data-testid="book_contactInfo_countryCode">+852</span>
                                    <van-icon name="arrow" color="#0f294d" />
                                </div>
                                <div class="text-input_aH text-input-b_2N tel-text-input-b_2L pale-label_23">
                                    <div class="input-area_ay" style="padding-left: 58px;">
                                        <label for="phone" class="">手提電話號碼</label>
                                        <input id="phone" name="phone" type="tel" placeholder=""
                                            data-testid="textInput_phone" value="">
                                    </div>
                                    <div class="ic-close-wrapper hide">
                                        <i class="ic-ad-close2 uranus-font ic-delete_solid"></i>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="x-product-list_lM">
                        <div data-exposure="{&quot;ubtKey&quot;:&quot;flt_x_component_expose&quot;,&quot;data&quot;:{&quot;pageId&quot;:&quot;10320667444&quot;,&quot;channel&quot;:&quot;Main&quot;,&quot;productType&quot;:&quot;Baggage&quot;,&quot;uuid&quot;:&quot;d4c3e2a7-0f3d-47b1-b62a-98d3708819a5&quot;}}"
                            testid="{}">
                            <div id="additional-baggage" class="baggage-container_oiX baggage-container-b_oiW">
                                <div class="blue-bcg_oiM">
                                    <div class="baggage-head-wrapper_Xx baggage-head-wrapper-b_XX">
                                        <div class="comp-header-container_oKm">
                                            <div class="title-block_oKV">
                                                <div class="title_oCG"><span class="title-text_oCf">新增行李</span><span
                                                        class="tag-wrapper_oCA">
                                                        <div class="tag-block_oCr"></div>
                                                    </span></div>
                                            </div>
                                            <div class="baggage-sell-point_XG">現在新增行李限額更便宜，更方便！ <span
                                                    class="policy-btn_X3">行李政策</span></div>
                                        </div>
                                    </div>
                                    <div class="body_oir">
                                        <div class="baggage-card-wrapper_oiK">
                                            <div class="head-wrapper_oiC">
                                                <div class="city-info-wrapper_oiB">
                                                    <div class="city-info_oia">
                                                        <div>紐約 - 倫敦</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="">
                                                <div id="personal-item-baggage">
                                                    <div class="baggage-info-wrapper_xMy">
                                                        <div class="personal-item-img_xMI pi-no-free_xMe"></div>
                                                        <div class="main-info_xMw">
                                                            <div>隨身物品</div>
                                                            <div class="free-desc_xMQ free-desc-high_xMk">
                                                                有關免費限額的詳情，請聯絡航空公司
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="divider_oiR"></div>
                                                <div id="carry-on-baggage">
                                                    <div>
                                                        <div class="baggage-info-wrapper_Xf">
                                                            <div class="info-wrapper_XM">
                                                                <div class="checked-img_Xr carryon-img_XA"></div>
                                                                <div class="main-info_XT">
                                                                    <div>手提行李</div>
                                                                    <div class="policy-info_fo">
                                                                        <div class="desc_fx">已包括： 1x10公斤/成人</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div></div>
                                                </div>
                                                <div class="divider_oiR"></div>
                                                <div id="checked-baggage">
                                                    <div>
                                                        <div class="baggage-info-wrapper_Xf">
                                                            <div class="info-wrapper_XM">
                                                                <div class="checked-img_Xr c-no-free_XK"></div>
                                                                <div class="main-info_XT">
                                                                    <div>託運行李</div>
                                                                    <div class="policy-info_fo no-free-baggage_fN">
                                                                        <div class="desc_fx">無免費限額</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="select-btn-carryon-0" class="select-btn_X9">新增
                                                                <van-icon name="arrow-down" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div data-exposure="{&quot;ubtKey&quot;:&quot;flt_x_component_expose&quot;,&quot;data&quot;:{&quot;pageId&quot;:&quot;10320667444&quot;,&quot;channel&quot;:&quot;Main&quot;,&quot;productType&quot;:&quot;Insurance&quot;,&quot;uuid&quot;:&quot;d4c3e2a7-0f3d-47b1-b62a-98d3708819a5&quot;}}"
                            testid="{}">
                            <div id="link-insurance-container">
                                <div class="container_o9H">
                                    <div class="insurance_container_opb">
                                        <div class="header_opH"><span class="header_title_opy">加購保障，出行無憂</span></div>
                                        <div class="content_opw">
                                            <div class="title_opQ"><span class="title_text_opk">國際旅遊保險</span></div>
                                            <div class="beneficial_opU">
                                                <div class="beneficial_item_opz">
                                                    <van-icon name="success" class="pt-[4px]" size="15px" />
                                                    <span
                                                        class="beneficial_text_opV">醫療費用保障，高達<span>HK$1,500,000</span>；提供24小时全球緊急支援服務</span>
                                                </div>
                                                <div class="beneficial_item_opz">
                                                    <van-icon name="success" class="pt-[4px]" size="15px" />
                                                    <span
                                                        class="beneficial_text_opV">個人意外保障，高達<span>HK$300,000</span></span>
                                                </div>
                                                <div class="beneficial_item_opz">
                                                    <van-icon name="success" class="pt-[4px]" size="15px" />
                                                    <span
                                                        class="beneficial_text_opV">個人行李及個人財物保障，高達<span>HK$20,000</span></span>
                                                </div>
                                                <div class="beneficial_item_opz">
                                                    <van-icon name="success" class="pt-[4px]" size="15px" />
                                                    <span
                                                        class="beneficial_text_opV">取消旅程保障，高達<span>HK$30,000</span></span>
                                                </div>
                                                <div class="beneficial_item_opz">
                                                    <van-icon name="success" class="pt-[4px]" size="15px" />
                                                    <span
                                                        class="beneficial_text_opV">縮短旅程保障，高達<span>HK$40,000</span></span>
                                                </div>
                                            </div>
                                            <div class="agreement_ojo">
                                                <span class="agreement_text_ojx"
                                                    data-testid="ins-agreement">總保額高達4,000,000港幣（適用於18至70歲的受保人）| 共 12
                                                    項保障項目</span>
                                                <van-icon name="arrow" />
                                            </div>
                                            <div class="purchase_ojN">為意外情況做好準備</div>
                                        </div>
                                        <div class="add_area_ojG">
                                            <div>
                                                <div class="container_oqF">
                                                    <span class="c">HK$</span>
                                                    <em class="p">168</em>
                                                    <em class="d">/人</em>
                                                </div>
                                                <div class="container_oq1">
                                                    <span class="period_title_oq5">保障時期</span><span
                                                        data-testid="period-icon" class="period_icon_oqg">
                                                        <van-icon name="info-o" />
                                                    </span>
                                                    <span>15日</span>
                                                </div>
                                            </div>
                                            <div class="add_btn_ojf" data-testid="ins-add-btn"><span
                                                    class="btn_text_ojM">新增</span></div>
                                        </div>
                                        <div class="insurance_divider_ojO"></div>
                                        <div class="container_ojm">
                                            <div class="disclaimers_more_ojV" style="-webkit-line-clamp: 4;"><span
                                                    data-testid="disclaimer">我同意該<a target="_blank"
                                                        href="https://pages.trip.com/m/assests/pdf/hk/1167_TAIPING_GLOBAL_TRAVEL_2023.pdf?_gl=1*wa1j0a*_gcl_au*MTU1OTM1NTg1NS4xNzAwNzI2MjM3LjE3OTM0NzI1MzkuMTcwMzIxNDYzOS4xNzAzMjE0NjM4">保險條款與聲明。</a>本保險產品在香港地區銷售。所有理賠在香港進行，並以港幣結算。本保險產品由中國太平保險(香港)有限公司承保。Ctrip.com
                                                    (Hong Kong) Ltd為委任之保險代理。</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fix-to-bottom_mT fix-to-bottom-b_VG">
                <div class="price-box_mB" id="payment-price-box">
                    <div data-testid="book_bottom_priceBar_totalPrice" class="total-price-funb_mW more-height_ms">
                        <div class="price-wrapb_mj">
                            <div class="price-tip_mg">人均價格：</div>
                            <div class="tripui-h5-click row-total_mc">
                                <span data-price="12018" class="prc-b_mt average-price_V3">
                                    <span>
                                        <span class="c">HK$</span>
                                        <em class="p">3,004.50</em>
                                    </span>
                                </span>
                                <van-icon name="arrow-up" />
                            </div>
                            <div class="price-tip_mg default-total-price_m5">2位成人+1位小童+1個嬰兒（不佔用座位）總額：HK$12,018</div>
                        </div>
                        <div data-testid="book_bottom_priceBar_pay" class="pay-button_VN">

                            <van-button type="primary" size="normal" color="#3264ff"
                                @click="router.push('/ticketssubmitorder')">
                                <div class=" flex items-center">
                                    <span class="font-medium text-[16px]">继续</span>
                                </div>
                            </van-button>
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

const selectedTicket=()=>{
    const bookPage = document.getElementById('book-page');
    const mianPage = document.getElementById('main');
    if (bookPage && mianPage) {
        bookPage.style.display = 'block';
        mianPage.style.display = 'none';
    }
}
</script>
<style src="@/assets/css/ticket/ticket.css" scoped></style>