<template>
    <div class="ibu_hotel_h5">
        <div class="book-page-content">
            <div class="static-info-wrap">
                <div class="hotel-name-wrap hotel-name-wrap-abTest">
                    <span class="back-btn" @click="router.back()"></span>
                    <div class="hotel-name hotel-name-b">深圳寶安機場RANZ蘭茲國際酒店</div>
                </div>
                <div class="base-info-wrap-new">
                    <div class="base-info-wrap">
                        <div class="check-info-wrap">
                            <div class="check-date">3月6日 週四 - 3月7日 週五</div>
                            <div class="check-rooms">
                                <span>1间</span>
                                <span class="check-x">x</span>
                                <span>1 晚</span>
                            </div>
                        </div>
                        <div class="base-info-content" @click="">
                            <div class="room-header">
                                <div class="room-header-name">舒適大床房</div>
                                <div class="room-header-btn flex items-center">
                                    <span>房間詳情</span>
                                    <van-icon name="arrow" size="3.2vw" color="#3264ff" />
                                </div>
                            </div>
                            <div class="facility-info-wrap">
                                <div class="facility-list">
                                    <div class="facility-item is-guest">
                                        <div class="max-guest">
                                            <van-icon name="contact" />
                                            <van-icon name="contact" />
                                        </div>
                                        <span class="max-guest-plus">+</span>
                                        <div class="max-guest">
                                            <van-icon name="src/assets/css/hotelimage/child_icon.svg"
                                                size="3.466667vw" />
                                        </div>
                                    </div>
                                    <div class="facility-item has-split-item">
                                        <div class="standardFacilityItem_facility-item-txt">1張大床</div>
                                    </div>
                                    <div class="facility-item flex items-center">
                                        <van-icon class="mr-[1.06667vw]"
                                            name="src/assets/css/hotelimage/no_smoking_icon.svg" size="3.466667vw" />
                                        <div class="standardFacilityItem_facility-item-txt">禁煙</div>
                                    </div>
                                    <div class="facility-item flex items-center">
                                        <van-icon class="mr-[1.06667vw]"
                                            name="src/assets/css/hotelimage/family_icon.svg" size="4.466667vw" />
                                        <div
                                            class="standardFacilityItem_facility-item-txt standardFacilityItem_child-pay">
                                            <span>小童免費入住</span>
                                        </div>
                                    </div>
                                    <div class="facility-item flex items-center">
                                        <van-icon class="mr-[1.06667vw]"
                                            name="src/assets/css/hotelimage/order_breakfast_icon.svg"
                                            size="4.466667vw" />
                                        <div class="standardFacilityItem_facility-item-txt">
                                            <span>早餐 HK$30.04（另付） </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="facility-list facility-item-block">
                                    <div class="facility-item">
                                        <van-icon class="mr-[1.06667vw]" name="src/assets/css/hotelimage/has_icon.svg"
                                            size="4.466667vw" />
                                        <div class="facility-desc cancel-facility cancel-free">
                                            <span>免費取消</span> 3月6日18:00前
                                        </div>
                                    </div>
                                    <div class="facility-item">
                                        <van-icon class="mr-[1.06667vw]"
                                            name="src/assets/css/hotelimage/hotel_immediately_icon.svg"
                                            size="4.466667vw" />
                                        <div class="facility-desc text-[#05939f]">
                                            即時確認
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="encourage-wrap-background">
                    <div class="encourage-tip-wrap">
                        <div class="encourage-content">
                            <div class="encourage-item courage">
                                <van-icon class="mr-[1.06667vw]" name="src/assets/css/hotelimage/has_icon.svg"
                                    size="4vw" />
                                <div class="encourage-title">於3月6日18:00前可免費取消</div>
                            </div>
                            <div class="encourage-item warn">
                                <van-icon class="mr-[1.06667vw]" name="src/assets/css/hotelimage/tag_icon.svg"
                                    size="4vw" />
                                <div class="encourage-title">您已合共節省 HK$444.36！</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="guest-and-contact-box">
                <div class="book-guest-card guest-container">
                    <div class="book-card-content">
                        <div class="guestTitle">
                            <div class="guest-tip-box">住客
                                <van-icon class="guest-tip-icon" name="src/assets/css/hotelimage/info_icon_s.svg"
                                    size="4.266667vw" />

                            </div>
                            <van-icon class="guest-tip-icon" name="src/assets/css/hotelimage/add_man_icon.svg"
                                size="5.333333vw" />

                        </div>
                        <div class="room-item-box">
                            <div class="room-item">
                                <div class="guest-list-box">
                                    <div class="guest-item">
                                        <div class="guest-name-group">
                                            <div class="guest-name-box">
                                                <div class="input-box">
                                                    <div class="input-container" :class="{ 'focused': nameFocus }">
                                                        <span class="input-label"
                                                            v-if="nameFocus || fristName.length > 0">{{ $t('姓氏')
                                                            }}<span>&nbsp;*</span></span>
                                                        <div class="input-placeholder"
                                                            v-if="!nameFocus && fristName.length <= 0">
                                                            <span class="input-placeholder-label">{{ $t('姓氏') }}</span>
                                                            <span class="input-placeholder-required">&nbsp;*</span>
                                                        </div>
                                                        <div class="input-wrapper">
                                                            <input type="family-name" id="family-name"
                                                                name="family-name" autocomplete="family-name"
                                                                :placeholder="nameFocus ? $t('只限填写英文字母') : ''"
                                                                @focus="HandleFcus" @blur="handleBulr"
                                                                v-model="fristName">
                                                            <span class="input-success-icon"
                                                                v-if="fristName.length > 0 && !nameFocus">
                                                                <van-icon name="success" size="4.266667vw" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="guest-name-box">
                                                <div class="input-box">
                                                    <div class="input-container" :class="{ 'focused': nameToFocus }">
                                                        <span class="input-label"
                                                            v-if="nameToFocus || lastName.length > 0">{{ $t('名字')
                                                            }}<span>&nbsp;*</span></span>
                                                        <div class="input-placeholder"
                                                            v-if="!nameToFocus && lastName.length <= 0">
                                                            <span class="input-placeholder-label">{{ $t('名字') }}</span>
                                                            <span class="input-placeholder-required">&nbsp;*</span>
                                                        </div>
                                                        <div class="input-wrapper">
                                                            <input type="given-name" id="given-name" name="given-name"
                                                                autocomplete="given-name"
                                                                :placeholder="nameToFocus ? $t('只限填写英文字母') : ''"
                                                                @focus="HandleNameFcus" @blur="handleNameBulr"
                                                                v-model="lastName">
                                                            <span class="input-success-icon"
                                                                v-if="lastName.length > 0 && !nameToFocus">
                                                                <van-icon name="success" size="4.266667vw" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-room-btn">
                            <van-icon class="add-room-icon" name="src/assets/css/hotelimage/add.svg"
                                size="4.266667vw" />
                            <span>新增房間（可選）</span>
                        </div>
                    </div>
                </div>
                <div class="book-guest-card contact-container">
                    <div class="book-card-content">
                        <div class="contact-phone">
                            <div class="phone-input-box">
                                <div class="phone-input-container" :class="{ 'active': telFocus }">
                                    <div class="input-label" v-if="telFocus || telphone.length > 0">{{ $t('电话号码')
                                        }}<span class="">&nbsp;*</span></div>
                                    <span class="phone-areaCode">
                                        <span>+852</span>
                                        <van-icon class="phone-icon" name="arrow" />

                                    </span>
                                    <div class="phone-input-content">
                                        <div class="phone-input-placeholder" v-if="!telFocus && telphone.length <= 0">
                                            <span class="phone-input-placeholder-label">{{ $t('电话号码') }}</span>
                                            <span class="phone-input-placeholder-required">&nbsp;*</span>
                                        </div>
                                        <div class="phone-input-content">
                                            <input class="phone-input" type="tel" id="tel" name="tel" autocomplete="tel"
                                                :placeholder="telFocus ? $t('电话号码') : ''" @focus="HandleTelFcus"
                                                @blur="handleTelBulr" v-model="telphone">
                                            <span class="input-success-icon" v-if="telphone.length > 0 && !telFocus">
                                                <van-icon name="success" size="4.266667vw" />
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-class="email-box">
                            <div class="email-input-container" data-class="email-box">
                                <div class="input-box">
                                    <div class="input-container email-input">
                                        <span class="input-label" v-if="emailFocus || emailStr.length > 0">{{ $t('电子邮箱')
                                        }}<span>&nbsp;*</span></span>
                                        <div class="input-placeholder" v-if="!emailFocus && emailStr.length <= 0">
                                            <span class="input-placeholder-label">{{ $t('电子邮箱') }}</span>
                                            <span class="input-placeholder-required">&nbsp;*</span>
                                        </div>
                                        <div class="input-wrapper">
                                            <input type="email" id="email" name="email" autocomplete="email"
                                                :placeholder="emailFocus ? $t('电子邮箱') : ''" @focus="HandleEmailFcus"
                                                @blur="handleEmailBulr" v-model="emailStr">
                                            <span class="input-success-icon" v-if="emailStr.length > 0 && !emailFocus">
                                                <van-icon name="success" size="4.266667vw" />
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="pay-bar">
            <div class="pay-bar-bottom pb-compatibility-calc pb-compatibility-env">
                <div class="inspair-bar">
                    <div class="inspair-item free-cancel">
                        <van-icon name="certificate" size="4.266667vw" color="#06aebd" />
                        <span>{{ $t('免费取消') }}</span>
                    </div>
                </div>
                <div class="pay-bar-bottom-main">
                    <div class="price-container-wrap">
                        <div class="price-container">
                            <div class="price-title">{{ $t('网上预付（总额）') }}</div>
                            <div class="price-row">
                                <div class="price-content">
                                    <span>{{ $sysCurrency }}613.62</span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="submit-container ">
                        <van-button class="" type="success" color="#3264ff" @click="toSubmitOrder">{{
                            $t('预订')
                        }}</van-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
import { handleError, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
const isExpand = ref(false);
const { t } = useI18n();
const router = useRouter()

const emailFocus = ref(false);
const emailStr = ref('');
const HandleEmailFcus = () => {
    emailFocus.value = true;
}
const handleEmailBulr = () => {
    emailFocus.value = false;
}

const telFocus = ref(false);
const telphone = ref('');
const HandleTelFcus = () => {
    telFocus.value = true;
}
const handleTelBulr = () => {
    console.log('shiqujiaodian ');
    telFocus.value = false;
}

const nameFocus = ref(false);
const fristName = ref('');
const HandleFcus = () => {
    nameFocus.value = true;
}
const handleBulr = () => {
    console.log('shiqujiaodian ');
    nameFocus.value = false;
}
const nameToFocus = ref(false);
const lastName = ref('');
const HandleNameFcus = () => {
    nameToFocus.value = true;
}
const handleNameBulr = () => {
    nameToFocus.value = false;
}

</script>

<style src="@/assets/css/hotel/hotel.css" scoped></style>