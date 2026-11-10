<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('用户余额记录') }}</b>
        </span>
        <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" @click="dialogVisible = true"
          round>{{ $t('调整') }}</el-button>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('改变状态')">
          <el-select v-model="queryForm.change_type" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('减少')" value="-1"></el-option>
            <el-option :label="$t('增加')" value="1"></el-option>
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
        <el-table-column :label="$t('用户信息')">
          <template slot-scope="scope">
            <div class="u-f-16 u-mb-10">{{ $t('ID') }}:{{ scope.row.user_id }}</div>
            <!-- <div class="u-f-14 u-mb-10">{{ $t('用户名') }}:{{ scope.row.user_info.username }}</div> -->
            <div class="u-f-14 u-mb-10">{{ $t('邮箱') }}:{{ scope.row.user_info.email }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('类型')" prop="status">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.balance_type === 1" type="danger">{{ $t('佣金余额') }}</el-tag>
            <el-tag v-else-if="scope.row.balance_type === 2" type="success">{{ $t('积分余额') }}</el-tag>
            <el-tag v-else-if="scope.row.balance_type === 3" type="danger">{{ $t('冻结余额') }}</el-tag>
            <el-tag v-else-if="scope.row.balance_type === 4" type="success">{{ $t('账户余额') }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('标题')">
          <template slot-scope="scope">
            {{ $t(scope.row.title) }}
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作金额')" prop="amount"></el-table-column>
        <el-table-column :label="$t('操作前')" prop="balance_before"></el-table-column>
        <el-table-column :label="$t('操作后')" prop="balance_after"></el-table-column>
        <el-table-column :label="$t('类型')" prop="status">
          <template slot-scope="scope">
            <!-- //1-佣金到账 2-积分到账 3-佣金提现 4-积分转佣金 5-用户充值 6-分享佣金收入 7-活动佣金收入 8-充值入账 9-余额支付 10-平台增加 11-平台减少 -->
            <el-tag v-if="scope.row.from_type === 1" type="info">{{ $t('佣金到账') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 2" type="success">{{ $t('积分到账') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 3" type="danger">{{ $t('佣金提现') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 4" type="success">{{ $t('积分转佣金') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 5" type="warning">{{ $t('用户充值') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 6" type="info">{{ $t('分享佣金收入') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 7" type="danger">{{ $t('活动佣金收入') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 8" type="success">{{ $t('充值入账') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 9" type="warning">{{ $t('余额支付') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 10" type="warning">{{ $t('平台增加') }}</el-tag>
            <el-tag v-else-if="scope.row.from_type === 11" type="warning">{{ $t('平台减少') }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('备注')" prop="remarks"></el-table-column>
        <el-table-column :label="$t('操作时间')" prop="create_time">
          <template slot-scope="scope">
            {{ scope.row.create_time ? scope.row.create_time : '--' }}
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)">{{ $t('删除') }}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
          :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
      </div>
    </el-card>
    <el-dialog :title="$t('余额/积分调整')" :visible.sync="dialogVisible" width="600px">
      <el-form :model="formData" ref="formRef" label-width="80px">
        <el-form-item :label="$t('调整对象')">
          <UserSearch :user_id="formData.user_id" :field="'id'" @changeUserId="changeUserId" :multiple="false">
          </UserSearch>
        </el-form-item>
        <el-form-item :label="$t('增加/减少')">
          <el-select v-model="formData.change_type">
            <el-option :label="$t('减少')" :value="-1"></el-option>
            <el-option :label="$t('增加')" :value="1"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('余额/积分')">
          <el-select v-model="formData.balance_type">
            <el-option :label="$t('余额')" :value="4"></el-option>
            <el-option :label="$t('积分')" :value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('调整金额')" prop="amount">
          <el-input v-model="formData.amount" :placeholder="$t('请输入调整金额')"></el-input>
        </el-form-item>
        <el-form-item :label="$t('备注')" prop="remarks">
          <el-input v-model="formData.remarks" :placeholder="$t('备注')"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">{{ $t('取 消') }}</el-button>
        <el-button type="primary" @click="submitForm">{{ $t('确 定') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
import UserSearch from '@/components/forms/usersearch.vue'
export default {
  components: {
    UserSearch
  },
  mixins: [commonPage],
  data() {
    return {
      apiName: "moneyLog",
      dialogVisible: false,
      queryForm: {
        change_type: "",
        keyword: "",
      },
      formData: {
        user_id: '',
        change_type: -1,
        balance_type: 4,
        amount: 0,
        remarks: '',
      }
    };
  },
  methods: {
    submitForm() {
      this.$api[this.apiName].save(this.formData).then(response => {
        if (response.code === 1) {
          this.$message.success(this.$t('操作成功'));
          this.dialogVisible = false;
          this.getPageData(true);
        }
      });
    },
    handleChange() {
      this.getPageData(true);
    },
    changeUserId(user_id) {
      // console.log(user_id);
      this.formData.user_id = user_id
    },
  },
};
</script>