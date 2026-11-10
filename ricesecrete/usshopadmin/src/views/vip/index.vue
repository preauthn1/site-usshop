<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">会员等级列表</b>
        </span>
        <router-link :to="{ name: 'vipEdit' }" v-if="hasPerm('vipEdit')">
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
<!--        <el-form-item label="关键词">-->
<!--          <el-input v-model="queryForm.keyword" placeholder="输入等级名称查询" clearable></el-input>-->
<!--        </el-form-item>-->
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">查询</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="序号" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column label="图片">
          <template slot-scope="scope">
            <el-image style="'width: 150px; height: 80px" :src="scope.row.src +
          '?x-oss-process=image/auto-orient,1/resize,l_200/quality,q_100'
          " :data-src="scope.row.src" fit="cover" lazy></el-image>
          </template>
        </el-table-column>
        <el-table-column label="等级名称" prop="name"></el-table-column>
        <el-table-column label="等级" prop="level"></el-table-column>
        <el-table-column label="消费值" prop="money"></el-table-column>
        <el-table-column label="有效下级人数" prop="invite_num"></el-table-column>

        <el-table-column label="状态">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'vipEdit')"
              v-if="hasPerm('vipEdit')">编辑</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('vipDelBtn')">删除</el-button>
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
      apiName: "vip",
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
