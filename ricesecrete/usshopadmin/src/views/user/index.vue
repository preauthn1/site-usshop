<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('用户列表')}}</b>
        </span>
        <router-link :to="{ name: 'userEdit' }" v-if="hasPerm('userEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('ID')">
          <el-input v-model="queryForm.id" :placeholder="$t('请输入ID')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('邮箱')">
          <el-input v-model="queryForm.email" :placeholder="$t('请输入邮箱')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('上级代理')">
          <el-input v-model="queryForm.top_user_email" :placeholder="$t('请输入上级代理邮箱')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('邀请码')">
          <el-input v-model="queryForm.popularize_code" :placeholder="$t('请输入邀请码')" clearable></el-input>
        </el-form-item>
        <el-form-item :label="$t('注册时间')">
          <el-date-picker
            v-model="queryForm.create_time"
            type="datetimerange"
            range-separator="-"
            :start-placeholder="$t('开始时间')"
            :end-placeholder="$t('结束时间')"
            value-format="yyyy-MM-dd HH:mm:ss"
            :default-time="['00:00:00', '23:59:59']">
          </el-date-picker>
        </el-form-item>
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('启用')" value="1"></el-option>
            <el-option :label="$t('禁用')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
          <el-button icon="el-icon-refresh" @click="resetForm">{{ $t('重置') }}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer @selection-change="handleSelectionChange">
        <el-table-column type="selection" width="55">
        </el-table-column>
        <el-table-column :label="$t('ID')" prop="id" width="80"></el-table-column>
        <el-table-column :label="$t('昵称')" prop="nick_name"></el-table-column>
        <el-table-column :label="$t('上级代理')">
          <template slot-scope="scope">
            <template v-if="scope.row.top_user">
              {{scope.row.top_user.id}} / {{scope.row.top_user.email}} {{scope.row.top_user.nick_name ? '(' + scope.row.top_user.nick_name + ')' : ''}}
            </template>
          </template>
        </el-table-column>
        <el-table-column :label="$t('邮箱')" prop="email"></el-table-column>
        <el-table-column :label="$t('邀请码')" prop="popularize_code"></el-table-column>
        <el-table-column :label="$t('余额')" prop="balance"></el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>

        <el-table-column :label="$t('注册时间')" prop="create_time" sortable></el-table-column>
        <el-table-column :label="$t('操作')" width="220">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'userEdit')"
              v-if="hasPerm('userEdit')">{{$t('编辑')}}</el-button>
            <el-button
              size="mini"
              type="text"
              @click="handleChangeTopUser(scope.row)"
              v-if="hasPerm('userEdit')"
            >
              {{$t('修改上级')}}
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

    <!-- 修改上级弹框 -->
    <el-dialog
      :title="$t('修改上级')"
      :visible.sync="topUserDialogVisible"
      width="500px"
      v-if="topUserDialogVisible"
    >
      <UserSearch
        :user_id="selectedTopUserId"
        :field="'id'"
        @changeUserId="changeTopUserId"
        :multiple="false"
      >
      </UserSearch>
      <span slot="footer" class="dialog-footer">
        <el-button @click="topUserDialogVisible = false">{{$t('取 消')}}</el-button>
        <el-button type="primary" @click="submitChangeTopUser">{{$t('确 定')}}</el-button>
      </span>
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
      apiName: "user",
      multipleSelection: [],
      queryForm: {
        status: "",
        keyword: "",
        id: "",
        email: "",
        popularize_code: "",
        top_user_email: "",
        create_time: [],
      },
      topUserDialogVisible: false,
      selectedTopUserId: '',
      currentUserId: ''
    };
  },
  methods: {
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },
    handleChange() {
      this.getPageData(true);
    },
    async handleChangeTopUser(row) {
      this.currentUserId = row.id
      try {
        this.selectedTopUserId = row.top_user_id || ''
        this.topUserDialogVisible = true
      } catch (e) {
        this.$message.error('获取用户信息失败')
      }
    },
    async submitChangeTopUser() {
      if (!this.selectedTopUserId) {
        this.$message.warning('请选择上级用户')
        return
      }
      this.$api.user.changeTopUser(this.currentUserId, this.selectedTopUserId).then(response => {
        if (response.code === 1) {
          this.$message.success('修改成功')
          this.getPageData()
        }
        this.topUserDialogVisible = false
      })
    },
    changeTopUserId(user_id) {
      this.selectedTopUserId = user_id
    },
    resetForm() {
      this.queryForm = {
        status: "",
        keyword: "",
        id: "",
        email: "",
        popularize_code: "",
        top_user_email: "",
        create_time: [],
      };
      this.getPageData(true);
    }
  },
};
</script>
