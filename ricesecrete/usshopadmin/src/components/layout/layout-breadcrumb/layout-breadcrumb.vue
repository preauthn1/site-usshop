<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <template v-for="item in breadList">
                <el-breadcrumb-item :to="{ name: item.name}" :key="item.name" v-if="item.type==1">{{item.title}}</el-breadcrumb-item>
                <el-breadcrumb-item :key="item.name" v-else>{{item.title}}</el-breadcrumb-item>
            </template>
        </el-breadcrumb>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {findTreeAllParent} from '@/libs/util'

    export default {
        name: "LayoutBreadcrumb",
        data(){
            return {
                breadList:[]
            }
        },
        computed: {
            ...mapGetters({
                menuTree:"menuTree"
            })
        },
        methods:{
            getBreadcrumb() {
                let matched = this.$route.matched.filter(item => item.name)
                const first = matched[0]
                let nodes = []
                if (first && first.name.trim().toLocaleLowerCase() !== 'home') {
                    if(first.meta.parent_id!=0){
                        let parents = findTreeAllParent({id:first.meta.id,parent_id:first.meta.parent_id},this.menuTree,[])
                        nodes = parents.reverse()
                    }
                    nodes.unshift({ name: 'home', title: this.$t('工作台'),type:1})
                    nodes.push({ name: first.name, title: first.meta.title})
                }else{
                    nodes.push({ name: 'home', title: this.$t('工作台'),type:1})
                }
                this.breadList = nodes
            }
        },
        created() {
            this.getBreadcrumb()
        },
        watch: {
            $route() {
                this.getBreadcrumb()
            }
        }
    }
</script>

<style lang="scss" scoped>
    .el-breadcrumb{
        line-height: 60px;
    }
</style>
