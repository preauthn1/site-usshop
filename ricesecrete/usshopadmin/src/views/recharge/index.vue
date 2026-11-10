<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('充值列表') }}</b>
        </span>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('取消支付')" value="0"></el-option>
            <el-option :label="$t('待支付')" value="1"></el-option>
            <el-option :label="$t('支付成功')" value="2"></el-option>
            <el-option :label="$t('支付失败')" value="3"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('用户ID')">
          <el-input v-model="queryForm.user_id" :placeholder="$t('请输入用户ID')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('订单号/三方订单号')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('请输入订单号')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('申请时间')">
          <el-date-picker
            v-model="queryForm.create_time"
            type="datetimerange"
            range-separator="-"
            :start-placeholder="$t('开始日期')"
            :end-placeholder="$t('结束日期')"
            value-format="yyyy-MM-dd HH:mm:ss"
            :default-time="['00:00:00', '23:59:59']"
            clearable>
          </el-date-picker>
        </el-form-item>
        <el-form-item :label="$t('支付时间')">
          <el-date-picker
            v-model="queryForm.pay_time"
            type="datetimerange"
            range-separator="-"
            :start-placeholder="$t('开始日期')"
            :end-placeholder="$t('结束日期')"
            value-format="yyyy-MM-dd HH:mm:ss"
            :default-time="['00:00:00', '23:59:59']"
            clearable>
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{ $t('查询') }}</el-button>
          <el-button icon="el-icon-refresh" @click="resetForm">{{ $t('重置') }}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('用户id')" prop="user_id"></el-table-column>
        <el-table-column :label="$t('用户邮箱')" prop="user_info.email"></el-table-column>
        <el-table-column :label="$t('充值金额')" prop="amount"></el-table-column>
        <el-table-column :label="$t('到账金额')" prop="end_amount"></el-table-column>

        <el-table-column :label="$t('状态')" prop="status">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.status === 1" type="info">{{ $t('待支付') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 2" type="success">{{ $t('支付成功') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 3" type="danger">{{ $t('支付失败') }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('三方订单号')" prop="pay_no"></el-table-column>
        <el-table-column :label="$t('支付渠道')" prop="pay_type"></el-table-column>
        <el-table-column :label="$t('申请时间')" prop="create_time">
          <template slot-scope="scope">
            {{ scope.row.create_time ? scope.row.create_time : '--' }}
          </template>
        </el-table-column>
        <el-table-column :label="$t('支付时间')" prop="pay_time">
          <template slot-scope="scope">
            {{ scope.row.pay_time ? scope.row.pay_time : '--' }}
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
      apiName: "recharge",
      queryForm: {
        status: "",
        keyword: "",
        user_id: "",
        email: "",
        create_time: [],
        pay_time: []
      },
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
    resetForm() {
      this.queryForm = {
        status: "",
        keyword: "",
        user_id: "",
        email: "",
        create_time: [],
        pay_time: []
      };
      this.getPageData(true);
    }
  },
};
</script>