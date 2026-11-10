<template>
    <div class="u-p-20">
        <el-card shadow="never">
            <div slot="header">
                <b class="u-f-16">{{$t('角色列表')}}</b>
                <router-link :to="{name:'authRoleEdit'}" v-if="hasPerm('authRoleEdit')">
                    <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
                </router-link>
            </div>

            <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
                <el-form-item :label="$t('状态')">
                    <el-select v-model="queryForm.status" clearable>
                        <el-option :label="$t('启用')" value="1"></el-option>
                        <el-option :label="$t('禁用')" value="0"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item :label="$t('关键词')">
                    <el-input v-model="queryForm.keyword" :placeholder="$t('输入角色名称查询')"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
                </el-form-item>
            </el-form>

            <el-table :data="tableData" class="u-mt-10">
                <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
                <el-table-column :label="$t('角色名称')" prop="title"></el-table-column>
                <el-table-column :label="$t('角色状态')">
                    <template slot-scope="scope">
                        <el-switch v-model="scope.row.status"
                                   :active-value="1"
                                   :inactive-value="0"
                                   active-color="#13ce66"
                                   @change="handleStatus(scope.row.id,scope.row.status)">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column :label="$t('添加日期')" prop="create_time"></el-table-column>
                <el-table-column :label="$t('操作')" width="120">
                    <template slot-scope="scope">
                        <el-button size="mini" type="text" @click="handleRoute(scope.row.id,'authRoleEdit')" v-if="hasPerm('authRoleEdit')">{{$t('编辑')}}</el-button>
                        <el-button size="mini" type="text" @click="handleDelete(scope.row.id)" v-if="hasPerm('authRoleDelBtn')">{{$t('删除')}}</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="u-mt-20 u-text-center">
                <el-pagination
                        background
                        @size-change="handleSizeChange"
                        @current-change="handlePageChange"
                        :current-page="currentPage"
                        :page-sizes="[10, 20, 30, 40,50]"
                        :page-size="limit"
                        layout="total, sizes, prev, pager, next, jumper"
                        :total="total">
                </el-pagination>
            </div>
        </el-card>
    </div>
</template>

<script>
    import commonPage from '@/mixins/common-page'

    export default {
        mixins:[commonPage],
        data() {
            return {
                apiName:'authGroup',
                queryForm:{
                    status:'',
                    keyword:''
                }
            }
        }
    }
</script>