<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('站内信列表')}}</b>
        </span>
        <router-link :to="{ name: 'notificationEdit' }" v-if="hasPerm('notificationEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.user_id" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('启用')" value="1"></el-option>
            <el-option :label="$t('禁用')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入标题查询')" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('ID')" prop="id"></el-table-column>
        <el-table-column :label="$t('用户id')" prop="user_id"></el-table-column>
        <el-table-column :label="$t('标题')" prop="title"></el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.read === 1">{{$t('已读')}}</el-tag>
            <el-tag v-else-if="scope.row.read === 2">{{$t('未读')}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('创建时间')" prop="create_time" sortable></el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'notificationEdit')"
              v-if="hasPerm('notificationEdit')">{{$t('查看')}}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('notificationDelBtn')">{{$t('移除')}}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
          :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";

export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: "notification",
      queryForm: {
        user_id: "",
        keyword: "",
      },
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
  },
};
</script>