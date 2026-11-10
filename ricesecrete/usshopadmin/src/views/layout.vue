<template>
    <div style="height: 100%;">
        <layout>
            <router-view></router-view>
        </layout>
    </div>
</template>

<script>
import Layout from "@/components/layout/layout"
import { mapActions } from 'vuex'

export default {
    components: {
        Layout
    },
    methods: {
        ...mapActions([
            'getConfig'
        ]),
    },
    created() {
        this.getConfig()
    }
}
</script>