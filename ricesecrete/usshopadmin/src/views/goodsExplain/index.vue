<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">商品说明文档</b>
        </span>
        <router-link :to="{ name: 'goodsExplainEdit' }" v-if="hasPerm('goodsExplainEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>添加</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item label="类型" prop="type">
          <el-select v-model="queryForm.type" placeholder="请选择类型">
            <el-option v-for="(item,key) in typeList" :key="key" :label="item" :value="key"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="queryForm.user_id" clearable @change="handleChange">
            <el-option label="全部" value></el-option>
            <el-option label="启用" value="1"></el-option>
            <el-option label="禁用" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="关键词">
          <el-input v-model="queryForm.keyword" placeholder="输入标题查询" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">查询</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="ID" prop="id"></el-table-column>
        <el-table-column label="标题" prop="title"></el-table-column>
        <el-table-column label="类型" prop="type_name"></el-table-column>
        <el-table-column label="状态">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column label="创建时间" prop="create_time" sortable></el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'goodsExplainEdit')"
              v-if="hasPerm('goodsExplainEdit')">编辑</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('goodsExplainDelBtn')">删除</el-button>
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
      apiName: "goodsExplain",
      typeList:[],
      queryForm: {
        type: "",
        keyword: "",
      },
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
    getLoadList() {
      let params = {
        init: 'init_params'
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          this.typeList = response.data.typeList;

        }
      });
    },
  },
  mounted() {
    this.getLoadList();
  },
};
</script>
