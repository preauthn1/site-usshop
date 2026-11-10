<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('内容管理')}}</b>
        </span>
        <router-link :to="{ name: 'articleEdit' }" v-if="hasPerm('articleEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('启用')" value="1"></el-option>
            <el-option :label="$t('禁用')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('类型')">
          <el-select @change="handleChange()" v-model="queryForm.type" clearable>
            <el-option :label="$t(item)" :value="index" v-for="(item, index) in typeList" :key="index"></el-option>
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
        <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('标题')" prop="title"></el-table-column>
        <el-table-column :label="$t('语言')" prop="lang"></el-table-column>
        <el-table-column :label="$t('类型')" prop="type">
          <template slot-scope="scope">
            <el-tag >{{$t(typeList[scope.row.type])}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'articleEdit')"
              v-if="hasPerm('articleEdit')">{{$t('编辑')}}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)" v-if="hasPerm('articleDelBtn')">{{$t('删除')}}</el-button>
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
      apiName: "article",
      queryForm: {
        status: "",
        type: "",
        keyword: "",
      },
      typeList: [],
      langList: []
    };
  },
  methods: {
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
          this.typeList = response.data.typeList;
          this.langList = response.data.langList;
        }
      });
    },
    handleChange() {
      this.getPageData(true);
    },
  },
  mounted() {
    this.getLoadList();
  }
};
</script>
