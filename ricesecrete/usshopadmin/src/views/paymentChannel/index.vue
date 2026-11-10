<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('支付渠道管理') }}</b>
        </span>
        <router-link :to="{ name: 'paymentChannelEdit' }" v-if="hasPerm('paymentChannelEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{ $t('添加') }}</el-button>
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
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入名称查询')" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{ $t('查询') }}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('名称')" prop="name"></el-table-column>
        <el-table-column :label="$t('标识')" prop="ident"></el-table-column>
        <el-table-column :label="$t('图标')">
          <template slot-scope="scope">
            <el-image :style="{ width: '150px', height: '80px' }" :src="scope.row.src +
          '?x-oss-process=image/auto-orient,1/resize,l_200/quality,q_100'"
                      :data-src="scope.row.src" fit="cover" lazy></el-image>
          </template>
        </el-table-column>
        <el-table-column :label="$t('最低提现金额')" prop="min_withdrawal_money"></el-table-column>
        <el-table-column :label="$t('最高提现金额')" prop="max_withdrawal_money"></el-table-column>
        <el-table-column :label="$t('支付状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.pay_status" :active-value="1" :inactive-value="2" active-color="#13ce66"
                       @change="handleStatus(scope.row.id, 'pay_status', scope.row.pay_status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('提现状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.withdrawal_status" :active-value="1" :inactive-value="2" active-color="#13ce66"
                       @change="handleStatus(scope.row.id, 'withdrawal_status', scope.row.withdrawal_status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
                       @change="handleStatus(scope.row.id, 'status', scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('支付类型')">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.type == 1">线上支付</el-tag>
            <el-tag type="success" v-if="scope.row.type == 2">线下支付</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'paymentChannelEdit')"
                       v-if="hasPerm('paymentChannelEdit')">{{ $t('编辑') }}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
                       v-if="hasPerm('paymentChannelDelBtn')">{{ $t('删除') }}</el-button>
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
      apiName: "paymentChannel",
      queryForm: {
        status: "",
        type: "",
        keyword: "",
      },
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
    handleStatus(id, column, status) {
      this.$api[this.apiName][this.apiStatus](id, column, status).then(response => {
        if (response.code == 1) {
          this.$message({
            type: 'success',
            message: this.$t('操作成功!')
          })
        }
      })
    },
  },
};
</script>