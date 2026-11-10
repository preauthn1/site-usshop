<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">定制旅游订单列表</b>
        </span>
        <router-link
          :to="{ name: 'orderCustomEdit' }"
          v-if="hasPerm('orderCustomEdit')"
        >
          <el-button
            type="danger"
            size="small"
            icon="el-icon-plus"
            class="u-right u-5mt"
            round
            >添加</el-button
          >
        </router-link>
      </div>

      <el-form
        :inline="true"
        :model="queryForm"
        size="small"
        class="u-text-center"
      >
        <el-form-item label="状态">
          <el-select
            v-model="queryForm.status"
            clearable
            @change="handleChange"
          >
            <el-option label="全部" value></el-option>
            <el-option
              :label="item"
              :value="index"
              v-for="(item, index) in statusList"
              :key="index"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="关键词">
          <el-input
            v-model="queryForm.keyword"
            placeholder="输入关键词查询"
            clearable
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            icon="el-icon-search"
            @click="getPageData(true)"
            >查询</el-button
          >
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="ID" prop="id" width="80"></el-table-column>
        <el-table-column label="用户" width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.user_info.nick_name }}</div>
            <el-avatar
              :size="50"
              :src="scope.row.user_info.head_img"
            ></el-avatar>
          </template>
        </el-table-column>
        <el-table-column label="联系人" width="250">
          <template slot-scope="scope">
            <div class="title">姓名：{{ scope.row.name }}</div>
            <div class="title">电话：{{ scope.row.phone }}</div>
          </template>
        </el-table-column>
        <el-table-column label="定制类型" prop="type_str"></el-table-column>
        <el-table-column label="企业名称" prop="enterprise"></el-table-column>
        <el-table-column label="定制信息">
          <template slot-scope="scope">
            <div class="title">出发地：{{ scope.row.address }}</div>
            <div class="title">出发时间：{{ scope.row.start_day }}</div>
            <div class="title">目的地：{{ scope.row.destination }}</div>
            <div class="title">人数：{{ scope.row.number }}</div>
            <div class="title">预算：{{ scope.row.budget }}</div>
            <div class="title">备注：{{ scope.row.remark }}</div>
          </template>
        </el-table-column>
        <el-table-column label="状态">
          <template slot-scope="scope">
            <!-- 状态：0取消 1申请中  2接待中 3接待成功 4拒绝接待 -->
            <el-tag v-if="scope.row.status == 0" type="info">取消</el-tag>
            <el-tag v-if="scope.row.status == 1" type="primary">申请中</el-tag>
            <el-tag v-if="scope.row.status == 2" type="warning">接待中</el-tag>
            <el-tag v-if="scope.row.status == 3" type="success"
              >接待成功</el-tag
            >
            <el-tag v-if="scope.row.status == 4" type="danger">拒绝接待</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="创建时间"
          prop="create_time"
          sortable
        ></el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="text"
              @click="cancelHandleRoute(scope.row.id)"
              v-if="hasPerm('orderCustomEdit') && scope.row.status == 1"
              >取消</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="verificationHandleRoute(scope.row.id)"
              v-if="hasPerm('orderCustomEdit') && scope.row.status == 1"
              >核验信息</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="receiveHandleRoute(scope.row.id)"
              v-if="
                (hasPerm('orderCustomEdit') && scope.row.status == 2)"
              >确认接待</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="refuseHandleRoute(scope.row.id)"
              v-if="hasPerm('orderCustomEdit') && scope.row.status == 1||scope.row.status == 2"
              >拒绝接待</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="handleRoute(scope.row.id, 'orderCustomEdit')"
              v-if="hasPerm('orderCustomEdit')"
              >编辑/查看</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="handleDelete(scope.row.id)"
              v-if="hasPerm('orderCustomDelBtn')"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination
          background
          @size-change="handleSizeChange"
          @current-change="handlePageChange"
          :current-page="currentPage"
          :page-sizes="[10, 20, 30, 40, 50]"
          :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
        ></el-pagination>
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
      apiName: "orderCustom",
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
    cancelHandleRoute(id) {
      //取消订单
      this.$confirm("确定要取消此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 0).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "取消成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    verificationHandleRoute(id) {
      //核验订单
      this.$confirm("确定要核验此订单信息吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 2).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "操作成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    receiveHandleRoute(id) {
      //接待订单
      this.$confirm("确定要接待此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 3).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "操作成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    refuseHandleRoute(id) {
      //接待订单
      this.$confirm("确定要拒绝接待此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 4).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "操作成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
          this.statusList = response.data.statusList;
        }
      });
    },
  },
  mounted() {
    this.getLoadList();
  },
};
</script>
