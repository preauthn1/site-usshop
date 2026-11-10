<template>
    <div class="tab-bar">
        <van-tabbar route v-model="active" style="z-index: 999;">
            <van-tabbar-item replace to="/">
                <span>{{ $t("首页") }}</span>
                <template #icon="props">
                    <svg v-if="props.active" width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px; height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15 5L5 13.375V25H13.5V17.5H16.5V25H25V13.375L15 5Z" fill="currentColor"></path>
                    </svg>
                    <svg v-else width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.25 25V17.5H15.75V25H25V13.375L15 5L5 13.375V25H14.25ZM6.5 14.0751L15 6.95605L23.5 14.0751V23.5001H17.25V16.0001H12.75V23.5001H6.5V14.0751Z"
                            fill="currentColor"></path>
                    </svg>
                </template>
            </van-tabbar-item>
            <van-tabbar-item replace to="/category" icon="search">
                <span>{{ $t('Category') }}</span>
                <template #icon="props">
                    <svg v-if="props.active" width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M19.0637 5.63968C14.1695 4.77669 9.50236 8.04466 8.63937 12.9389C7.77639 17.8331 11.0444 22.5003 15.9386 23.3633C18.4081 23.7987 20.8198 23.1824 22.7141 21.8362L26.6894 25.8109L28.8106 23.6895L24.837 19.7164C25.5886 18.6607 26.1229 17.4253 26.3629 16.064C27.2259 11.1698 23.958 6.50266 19.0637 5.63968ZM11.5938 13.4598C12.1691 10.1973 15.2802 8.01881 18.5428 8.59409C21.8053 9.16936 23.9838 12.2805 23.4085 15.5431C22.8332 18.8056 19.7221 20.9841 16.4595 20.4088C13.197 19.8335 11.0185 16.7224 11.5938 13.4598Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.62673 8.00024C7.96797 8.89805 7.43922 9.90571 7.07324 11.0002H2V8.00024H8.62673Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.07182 18.0002C7.4345 19.0782 7.96145 20.0899 8.62801 21.0002H2V18.0002H7.07182Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.60545 13.0002C6.46447 14.0185 6.46849 15.0257 6.60228 16.0002H2V13.0002H6.60545Z"
                            fill="currentColor"></path>
                    </svg>
                    <svg v-else width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M19.0637 5.63968C14.1695 4.77669 9.50234 8.04466 8.63936 12.9389C7.77637 17.8331 11.0443 22.5003 15.9386 23.3633C18.6758 23.8459 21.3421 23.0364 23.3115 21.3729L27.2197 25.2806L28.2803 24.2199L24.3731 20.3132C25.369 19.1378 26.0761 17.6907 26.3629 16.064C27.2259 11.1698 23.9579 6.50266 19.0637 5.63968ZM10.1166 13.1994C9.39743 17.2777 12.1206 21.1669 16.199 21.886C20.2774 22.6052 24.1666 19.8819 24.8857 15.8036C25.6048 11.7252 22.8816 7.83601 18.8032 7.11688C14.7248 6.39775 10.8357 9.12097 10.1166 13.1994Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.71482 8.75024C8.40506 9.22299 8.13163 9.72407 7.89917 10.2502H2V8.75024H8.71482Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.89969 18.7502C8.13131 19.2724 8.40476 19.774 8.71655 20.2502H2V18.7502H7.89969Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.02874 13.7502C6.99189 14.2554 6.99197 14.7564 7.02691 15.2502H2V13.7502H7.02874Z"
                            fill="currentColor"></path>
                    </svg>
                    <!-- <img class=" w-[25px] h-[auto!important]" -->
                    <!-- :src="props.active ? $getImagePath('icon_upgrade_ac.png') : $getImagePath('icon_upgrade.png')" /> -->
                </template>
            </van-tabbar-item>
            <van-tabbar-item replace to="/new" icon="friends-o">
                <span>{{ $t("New") }}</span>
                <template #icon="props">
                    <svg v-if="props.active" width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M20 5.5C21.933 5.5 23.5 3.933 23.5 2C23.5 3.933 25.067 5.5 27 5.5C25.067 5.5 23.5 7.067 23.5 9C23.5 7.067 21.933 5.5 20 5.5Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 5L15 9L18 5L21 9L18.5 13H11.5L8.99997 9L12 5ZM18.5 13C21.39 15.8965 23.4861 19.5851 24.4344 23.712L24.4977 24C21.437 24.6572 18.2588 25 15 25C11.7411 25 8.56296 24.6572 5.50012 24.0008L5.56552 23.712C6.49012 19.6883 8.50581 16.0812 11.2845 13.2188L11.5 13H18.5Z"
                            fill="currentColor"></path>
                    </svg>
                    <svg v-else width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M20 5.5C21.933 5.5 23.5 3.933 23.5 2C23.5 3.933 25.067 5.5 27 5.5C25.067 5.5 23.5 7.067 23.5 9C23.5 7.067 21.933 5.5 20 5.5Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M18 5L21 9L18.5 13C21.39 15.8965 23.4861 19.5851 24.4344 23.712L24.4977 24C21.437 24.6572 18.2588 25 15 25C11.7411 25 8.56296 24.6572 5.50012 24.0008L5.56552 23.712C6.49012 19.6883 8.50581 16.0812 11.2845 13.2188L11.4997 13.0001L8.99997 9L12 5L15 9L18 5ZM18 7.5L15 11.5L12 7.5L10.817 9.0775L13.3989 13.2088L12.3607 14.2636C10.1051 16.5872 8.422 19.4 7.45288 22.4813L7.34546 22.833L7.40456 22.8437C9.71227 23.2482 12.0658 23.4674 14.4494 23.4966L15 23.5C17.5728 23.5 20.1111 23.2792 22.5943 22.8433L22.6535 22.832C21.705 19.6184 19.9875 16.689 17.6583 14.2835L17.4381 14.0595L16.5962 13.2157L19.183 9.077L18 7.5Z"
                            fill="currentColor"></path>
                    </svg>
                </template>
            </van-tabbar-item>
            <van-tabbar-item replace to="/cart" icon="setting-o">
                <span>{{ $t('Cart') }}</span>
                <template #icon="props">
                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 26px; height: 26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.11735 8.16443H25.4557L23.9527 20.076H9.09852L7.59546 8.16443H7.60588L7.39492 6.46191H4.6051V4.96191H8.72053L9.11735 8.16443ZM9.30322 9.66443L9.37669 10.2573H9.37145L10.4211 18.576H22.63L23.7545 9.66443H9.30322Z"
                            fill="#767676"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.37 25.1919C9.67964 25.1919 9.12 24.6322 9.12 23.9418C9.12 23.2514 9.67964 22.6918 10.37 22.6918C11.0604 22.6918 11.62 23.2514 11.62 23.9418C11.62 24.6322 11.0604 25.1919 10.37 25.1919Z"
                            fill="#767676"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M23.95 23.9418C23.95 24.6322 23.3904 25.1919 22.7 25.1919C22.0097 25.1919 21.45 24.6322 21.45 23.9418C21.45 23.2514 22.0097 22.6918 22.7 22.6918C23.3904 22.6918 23.95 23.2514 23.95 23.9418Z"
                            fill="#767676"></path>
                    </svg>
                </template>
            </van-tabbar-item>
            <van-tabbar-item replace to="/me" icon="setting-o">
                <span>{{ $t('Me') }}</span>
                <template #icon="props">
                    <svg v-if="props.active" width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width: 26px; height: 26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15 15.5C17.8995 15.5 20.25 13.1495 20.25 10.25C20.25 7.35051 17.8995 5 15 5C12.1005 5 9.75 7.35051 9.75 10.25C9.75 13.1495 12.1005 15.5 15 15.5Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15 17C19.765 17 23.7225 20.4572 24.501 25H5.49908C6.27762 20.4572 10.2351 17 15 17Z"
                            fill="currentColor"></path>
                    </svg>
                    <svg v-else width="30" height="30" viewBox="0 0 30 30" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="width: 26px; height: 26px;">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15 5C17.8995 5 20.25 7.35051 20.25 10.25C20.25 13.102 17.9759 15.4228 15.1421 15.4981L15 15.5C12.1005 15.5 9.74997 13.1495 9.74997 10.25C9.74997 7.39804 12.024 5.07721 14.8578 5.00189L15 5ZM24.5009 24.9999C23.7223 20.4571 19.7649 17 15 17L14.8217 17.0016C10.1366 17.0866 6.26788 20.5139 5.49902 24.9999H24.5009ZM7.56171 23.3257C8.83889 20.4602 11.7146 18.5 15 18.5L15.1726 18.5018C18.4452 18.5703 21.2868 20.5831 22.5063 23.4829L22.513 23.4995H7.48645L7.56171 23.3257ZM11.25 10.25C11.25 8.17893 12.9289 6.5 15 6.5C17.071 6.5 18.75 8.17893 18.75 10.25C18.75 12.3211 17.071 14 15 14C12.9289 14 11.25 12.3211 11.25 10.25Z"
                            fill="currentColor"></path>
                    </svg>
                </template>
            </van-tabbar-item>
        </van-tabbar>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const active = ref(0);
</script>

<style>
/* .tab-bar {
    width: 100vw;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 12px;
}

.van-tabbar-item {
    display: flex;
    flex-direction: column;
    align-items: center;
} */
</style>