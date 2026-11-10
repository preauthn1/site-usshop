<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">APP版本管理</b>
        </span>
        <router-link :to="{ name: 'appVersionEdit' }" v-if="hasPerm('appVersionEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>添加</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item label="状态">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option label="全部" value></el-option>
            <el-option label="启用" value="1"></el-option>
            <el-option label="禁用" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="关键词">
          <el-input v-model="queryForm.keyword" placeholder="输入版本" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">查询</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="序号" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column label="版本号" prop="version_name"></el-table-column>
        <el-table-column label="版本号" prop="version_code"></el-table-column>
        <el-table-column label="公告" prop="bulletin">
          <template slot-scope="scope">
            <div v-html="scope.row.bulletin"></div>
          </template>
        </el-table-column>
        <el-table-column label="平台" prop="platform_type">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.platform_type == 1">Android</el-tag>
            <el-tag type="success" v-else-if="scope.row.platform_type == 2">iOS</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="是否强制升级" prop="is_force_update">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.is_force_update == 0">否</el-tag>
            <el-tag type="success" v-else-if="scope.row.is_force_update == 1">是</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="是否是wgt包" prop="is_wgt">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.is_wgt == 0">否</el-tag>
            <el-tag type="success" v-else-if="scope.row.is_wgt == 1">是</el-tag>
          </template>
        </el-table-column>
<!--        <el-table-column label="下载链接" prop="url">-->
<!--          <template slot-scope="scope">-->
<!--            <a target="_blank" :href="scope.row.url" type="success">{{ scope.row.url }}</a>-->
<!--          </template>-->
<!--        </el-table-column>-->

        <el-table-column label="状态">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'appVersionEdit')"
              v-if="hasPerm('appVersionEdit')">编辑</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('appVersionDelBtn')">删除</el-button>
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
      apiName: "appVersion",
      queryForm: {
        status: "",
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
