<template>
    <el-container class="layout">
        <el-aside :width="asideWidth">
            <layout-logo></layout-logo>
            <layout-sider></layout-sider>
        </el-aside>
        <el-container>
            <el-header>
                <layout-header>
                    <layout-breadcrumb class="u-left"></layout-breadcrumb>
                </layout-header>
            </el-header>
            <el-main>
                <slot></slot>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import LayoutHeader from './layout-header/layout-header'
    import LayoutSider from './layout-sider/layout-sider'
    import LayoutLogo from './layout-logo/layout-logo'
    import LayoutBreadcrumb from './layout-breadcrumb/layout-breadcrumb'
    import {mapGetters} from 'vuex'

    export default {
        name: "Layout",
        components:{
            LayoutHeader,
            LayoutSider,
            LayoutLogo,
            LayoutBreadcrumb
        },
        computed: {
            ...mapGetters({
                isCollapse:"siderCollapsed",
            }),
            asideWidth() {
                if(this.isCollapse){
                    return '64px';
                }else{
                    return '240px';
                }
            }
        }
    }
</script>

<style lang="scss">
    @import './layout.scss';
</style>
