<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('支付信息列表')}}</b>
        </span>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="item" :value="index" v-for="(item, index) in statusList" :key="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('订单号/支付单号')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入订单号查询')" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('ID')" prop="id" width="80"></el-table-column>
        <el-table-column :label="$t('用户')" width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.user_info.nick_name }}</div>
<!--            <el-avatar :size="50" :src="scope.row.user_info.head_img"></el-avatar>-->
          </template>
        </el-table-column>
        <el-table-column :label="$t('单号')" width="250">
          <template slot-scope="scope">
            <div class="title">{{$t('订单号')+":"+scope.row.order_no}}</div>
            <div class="title">{{$t('支付单号')+":"+scope.row.pay_no}}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('支付类型')" prop="pay_type_str"></el-table-column>
        <el-table-column :label="$t('支付金额')" prop="pay_amount"></el-table-column>
        <el-table-column :label="$t('支付时间')" prop="pay_time_str"></el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <div class="title">{{ $t(scope.row.status_str) }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('创建时间')" prop="create_time" sortable></el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('orderPayDelBtn')">{{$t('删除')}}</el-button>
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
      apiName: "orderPay",
      statusList: [],
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
    getLoadList() {
      let params = {
        init: 'init_params'
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
          this.statusList = response.data.statusList;
        }
      });
    }
  },
  mounted() {
    this.getLoadList();
  },
};
</script>
