<template>
    <div class="layout-logo">
        <img :src="logoAside" height="40" v-show="!isCollapsed">
        <img :src="logoSample" height="40" v-show="isCollapsed">
    </div>
</template>

<script>
    import config from '@/config'
    import { mapGetters } from 'vuex'

    export default {
        name: "LayoutLogo",
        data(){
            return {
                logoAside:config.logoAside,
                logoSample:config.logoSample
            }
        },
        computed:{
            ...mapGetters({
                isCollapsed: "siderCollapsed"
            })
        }
    }
</script>
