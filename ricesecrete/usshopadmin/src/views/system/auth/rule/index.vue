<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">{{$t('权限列表')}}</b>
                <router-link :to="{name:'authRuleEdit'}" v-if="hasPerm('authRuleEdit')">
                    <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
                </router-link>
            </div>

            <el-table :data="tableData" row-key="id" class="u-mt-10">
                <el-table-column :label="$t('权限名称')" prop="title"></el-table-column>
                <el-table-column :label="$t('权限标识')" prop="name"></el-table-column>
                <el-table-column :label="$t('URL')" prop="url"></el-table-column>
                <el-table-column :label="$t('图标')">
                    <template slot-scope="scope">
                        <i :class="scope.row.icon"></i>
                    </template>
                </el-table-column>
                <el-table-column :label="$t('类型')">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.type==0" type="warning">{{$t('目录')}}</el-tag>
                        <el-tag v-else-if="scope.row.type==1" type="success">{{$t('菜单')}}</el-tag>
                        <el-tag v-else-if="scope.row.type==2" type="info">{{$t('页面')}}</el-tag>
                        <el-tag v-else-if="scope.row.type==3" type="danger">{{$t('按钮元素')}}</el-tag>
                        <el-tag v-else-if="scope.row.type==4">{{$t('接口')}}-{{$t(scope.row.api_method_text)}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column :label="$t('排序')" prop="rank"></el-table-column>
                <el-table-column :label="$t('操作')" width="120">
                    <template slot-scope="scope">
                        <el-button size="mini" type="text" @click="handleRoute(scope.row.id,'authRuleEdit')" v-if="hasPerm('authRuleEdit')">{{$t('编辑')}}</el-button>
                        <el-button size="mini" type="text" @click="handleDelete(scope.row.id)" v-if="hasPerm('authRuleDelBtn')">{{$t('删除')}}</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
    </div>
</template>

<script>
    import commonPage from '@/mixins/common-page'

    export default {
        mixins:[commonPage],
        data() {
            return {
                apiName:'authRule',
                isTree:true
            }
        }
    }
</script>
