<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('提现列表') }}</b>
        </span>
        <!--        <router-link :to="{ name: 'withdrawalEdit' }" v-if="hasPerm('withdrawalEdit')">-->
        <!--          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{ $t('添加') }}</el-button>-->
        <!--        </router-link>-->
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('待审核')" value="1"></el-option>
            <el-option :label="$t('审核成功')" value="2"></el-option>
            <el-option :label="$t('审核失败')" value="3"></el-option>
            <el-option :label="$t('支付成功')" value="4"></el-option>
            <el-option :label="$t('支付失败')" value="5"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('用户ID')">
          <el-input v-model="queryForm.user_id" :placeholder="$t('请输入用户ID')" clearable></el-input>
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
        <el-form-item :label="$t('审核时间')">
          <el-date-picker
            v-model="queryForm.check_time"
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
        <el-table-column :label="$t('申请提现金额')" prop="original_amount"></el-table-column>
        <el-table-column :label="$t('手续费')" prop="fee"></el-table-column>

        <el-table-column :label="$t('状态')" prop="status">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.status === 1" type="info">{{ $t('待审核') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 2">{{ $t('审核成功') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 3" type="danger">{{ $t('审核失败') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 4" type="success">{{ $t('支付成功') }}</el-tag>
            <el-tag v-else-if="scope.row.status === 5" type="warning">{{ $t('支付失败') }}</el-tag>
          </template>
        </el-table-column>
        <!--        <el-table-column :label="$t('提现渠道')" prop="channel_id"></el-table-column>-->
        <!--        <el-table-column :label="$t('提现方式')" prop="channel_indent"></el-table-column>-->
        <el-table-column :label="$t('提现地址')" prop="card_no"></el-table-column>
        <el-table-column :label="$t('申请时间')" prop="create_time">
          <template slot-scope="scope">
            {{ scope.row.create_time ? scope.row.create_time : '--' }}
          </template>
        </el-table-column>
        <el-table-column :label="$t('审核时间')" prop="check_time">
          <template slot-scope="scope">
            {{ scope.row.check_time ? scope.row.check_time : '--' }}
          </template>
        </el-table-column>
        <!-- <el-table-column :label="$t('支付时间')" prop="pay_time">
          <template slot-scope="scope">
            {{ scope.row.pay_time ? scope.row.pay_time : '--' }}
          </template>
        </el-table-column> -->

        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleStatus(scope.row.id, 2)"
                       v-if="hasPerm('withdrawalStatus') && scope.row.status === 1">{{ $t('通过') }}
            </el-button>
            <el-button size="mini" type="text" @click="handleStatus(scope.row.id, 3)"
                       v-if="hasPerm('withdrawalStatus') && scope.row.status === 1">{{ $t('拒绝') }}
            </el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
                       v-if="hasPerm('withdrawalDelBtn') && scope.row.status === 3">{{ $t('删除') }}
            </el-button>
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
      apiName: "withdrawal",
      queryForm: {
        status: "",
        user_id: "",
        create_time: [],
        check_time: []
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
        user_id: "",
        create_time: [],
        check_time: []
      };
      this.getPageData(true);
    }
  },
};
</script>
