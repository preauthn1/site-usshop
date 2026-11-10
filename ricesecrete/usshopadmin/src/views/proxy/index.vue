<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('代理列表')}}</b>
        </span>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入邮箱或邀请码')" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('ID')" prop="id" width="80"></el-table-column>
        <el-table-column :label="$t('昵称')" prop="nick_name"></el-table-column>
        <el-table-column :label="$t('邮箱')" prop="email"></el-table-column>
        <el-table-column :label="$t('邀请码')" prop="popularize_code"></el-table-column>
        <el-table-column :label="$t('下级人数')" prop="subordinate_count"></el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleSubordinates(scope.row.id)"
              v-if="hasPerm('proxySubordinates')">{{$t('查看下级')}}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
          :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
      </div>
    </el-card>

    <!-- Add dialog for subordinates -->
    <el-dialog :title="$t('下级列表')" :visible.sync="dialogVisible" width="800px">
      <el-table :data="subordinatesData">
        <el-table-column :label="$t('ID')" prop="id" width="80"></el-table-column>
        <el-table-column :label="$t('昵称')" prop="nick_name"></el-table-column>
        <el-table-column :label="$t('邮箱')" prop="email"></el-table-column>
        <el-table-column :label="$t('邀请码')" prop="popularize_code"></el-table-column>
        <el-table-column :label="$t('注册时间')" prop="create_time"></el-table-column>
      </el-table>
      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSubSizeChange" @current-change="handleSubPageChange"
          :current-page="subCurrentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="subLimit"
          layout="total, sizes, prev, pager, next, jumper" :total="subTotal">
        </el-pagination>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";

export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: "proxy",
      queryForm: {
        keyword: "",
      },
      dialogVisible: false,
      subordinatesData: [],
      currentProxyId: null,
      subCurrentPage: 1,
      subLimit: 10,
      subTotal: 0,
    };
  },
  methods: {
    async handleSubordinates(id) {
      this.currentProxyId = id;
      this.subCurrentPage = 1;
      this.getSubordinatesData();
    },

    async getSubordinatesData() {
      try {
        const res = await this.$api.proxy.subordinates(this.currentProxyId, {
          page: this.subCurrentPage,
          limit: this.subLimit
        });
        this.subordinatesData = res.data.pageList;
        this.subTotal = res.data.totalCount;
        this.dialogVisible = true;
      } catch (error) {
        this.$message.error(this.$t('获取下级列表失败'));
      }
    },

    handleSubSizeChange(val) {
      this.subLimit = val;
      this.getSubordinatesData();
    },

    handleSubPageChange(val) {
      this.subCurrentPage = val;
      this.getSubordinatesData();
    },
  },
};
</script>