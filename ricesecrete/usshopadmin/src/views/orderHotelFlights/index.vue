<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">旅游订单列表</b>
        </span>
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
        <el-form-item label="下单时间">
          <el-date-picker
            v-model="queryForm.time_section"
            type="daterange"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            @change="handleChange"
          ></el-date-picker>
        </el-form-item>
        <el-form-item label="入住/登机时间">
          <el-date-picker
            v-model="queryForm.start_day"
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            type="date"
            placeholder="选择日期"
            @change="handleChange"
          >
          </el-date-picker>
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

      <el-table
        :data="tableData"
        class="u-mt-10"
        :row-class-name="tableRowClassName"
        v-viewer
      >
        <el-table-column label="ID" prop="id" width="80"></el-table-column>
        <el-table-column label="订单号" width="160">
          <template slot-scope="scope">
            {{ scope.row.order_no }}
          </template>
        </el-table-column>
        <el-table-column label="支付单号" width="160">
          <template slot-scope="scope">
            {{ scope.row.pay_no }}
          </template>
        </el-table-column>
        <el-table-column label="套餐信息">
          <template slot-scope="scope">
            <div class="title">{{ scope.row.product_name }}</div>
            <div class="img">
              <el-image
                style="max-width: 100px"
                :src="scope.row.product_img"
                :data-src="scope.row.product_img"
                fit="cover"
                lazy
              ></el-image>
            </div>
            <div class="title">{{ scope.row.spec_json.title }}</div>
          </template>
        </el-table-column>
        <el-table-column label="费用">
          <template slot-scope="scope">
            <div class="title">成人：￥{{ scope.row.spec_json.price }}</div>
            <div class="title">儿童：￥{{ scope.row.spec_json.kid_price }}</div>
            <div class="title">
              {{ scope.row.spec_json.number }}成人
              {{ scope.row.spec_json.kid_number }}儿童
            </div>
            <div class="title">总费用：{{ scope.row.amount }}</div>
          </template>
        </el-table-column>
        <el-table-column label="客户信息">
          <template slot-scope="scope">
            <div
              class="contacts"
              v-for="(item, index) in scope.row.contacts"
              :key="index"
            >
              <div class="title">客户姓名：{{ item.name }}</div>
              <div class="title">客户电话：{{ item.mobile }}</div>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="状态" prop="status_str"></el-table-column>
        <el-table-column label="时间" width="220">
          <template slot-scope="scope">
            <div class="title">更新时间：{{ scope.row.update_time }}</div>
            <div class="title">下单时间：{{ scope.row.create_time }}</div>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="text"
              @click="checkHandleRoute(scope.row.id, scope.row.merchant_id)"
              v-if="hasPerm('orderHotelFlightsStatus') && scope.row.status == 1"
              >审核</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="cancelHandleRoute(scope.row.id)"
              v-if="
                hasPerm('orderHotelFlightsEdit') &&
                scope.row.status < 3 &&
                scope.row.status > 0
              "
              >取消订单</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="setOrderPrice(scope.row.id)"
              v-if="
                hasPerm('orderHotelFlightsStatus') &&
                (scope.row.status == 1 || scope.row.status == 2)
              "
              >修改金额</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="checkRefundRoute(scope.row)"
              v-if="hasPerm('orderHotelFlightsStatus') && scope.row.status == 5"
              >审核退款</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="verHandleRoute(scope.row.id)"
              v-if="hasPerm('orderHotelFlightsStatus') && scope.row.status == 3"
              >核验</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="handleRoute(scope.row.id, 'orderHotelFlightsEdit')"
              v-if="hasPerm('orderHotelFlightsEdit')"
              >查看</el-button
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
    <el-dialog title="退款审核" :visible.sync="refundShow" width="900px">
      <el-row>
        <el-col :span="12">
          <el-table :data="objForm.order_damages" class="u-mt-10" v-viewer>
            <el-table-column label="名称" prop="name"></el-table-column>
            <el-table-column label="扣除比例(%)" prop="ratio"></el-table-column>
          </el-table>
        </el-col>
        <el-col :span="12">
          <el-form :model="objForm" label-width="80px">
            <el-form-item label="状态">
              <el-radio v-model="objForm.check" :label="0">拒绝</el-radio>
              <el-radio v-model="objForm.check" :label="1">同意</el-radio>
            </el-form-item>
            <el-form-item label="集合时间" v-if="objForm.check == 1">
              <div>{{ objForm.start_day }}({{ objForm.days }}天)</div>
            </el-form-item>
            <el-form-item label="支付金额" v-if="objForm.check == 1">
              <div>{{ objForm.pay_amount }}</div>
            </el-form-item>
            <el-form-item label="退款金额" v-if="objForm.check == 1">
              <el-input
                v-model="objForm.amount"
                type="text"
                placeholder="请输入退款金额"
              >
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="退款原因">
              <el-input
                v-model="objForm.remark"
                type="textarea"
                placeholder=""
              ></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button @click="refundShow = false">取 消</el-button>
        <el-button type="primary" @click="checkRefundOrder()">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog title="核验订单信息" :visible.sync="verShow" width="600px">
      <el-form :model="verData" label-width="130px">
      <el-form-item label="选择服务人员">
        
      </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="verShow = false">取 消</el-button>
        <el-button type="primary" @click="okCheckHandleRoute()">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";

export default {
  components: {
  
  },
  mixins: [commonPage],
  data() {
    return {
      apiName: "orderHotelFlights",
      statusList: [], //订单状态
      verShow: false, //验证窗口
      verData: {
        id: "", //id
        bureau_id: "", //旅行社id
        mobile: "", //订单负责人电话
      },
      queryForm: {
        type: 1, //订单类型 1旅游套餐 2正常商品 3积分商品
        start_day: "",
        time_section: [],
        status: "", //订单状态
        keyword: "",
      },
      objForm: {
        check: 0,
        amount: "",
        start_day: "",
        days: "",
        order_damages: [],
        remark: "",
      },
      refundShow: false,
    };
  },
  methods: {
    tableRowClassName({ row }) {
      if (row.status == 1) {
        return "warning-row";
      } else if (row.status > 2 && row.status != 5 && row.status != 6) {
        return "success-row";
      } else if (row.status == 5) {
        return "danger-row";
      } else if (row.status == 6) {
        return "";
      }
      return "";
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
    checkRefundRoute(row) {
      // console.log(row);
      this.refundShow = true;
      this.objForm.id = row.id;
      this.objForm.start_day = row.start_day;
      this.objForm.days = row.days;
      this.objForm.pay_amount = row.pay_amount;
      this.objForm.amount = row.temp_refund_amount;
      this.objForm.order_damages = row.order_damages;
      this.objForm.remark = row.refund_remark;
    },
    checkRefundOrder() {
      let json = {
        id: this.objForm.id,
        amount: this.objForm.amount,
        check: this.objForm.check,
        operation: "refund",
      };
      this.$api[this.apiName].save(json).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: "退款审核成功!",
          });
          this.getPageData();
        }
      });
      this.refundShow = false;
    },
    setOrderPrice(id) {
      this.$prompt("请输修改金额", "金额修改提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
      })
        .then(({ value }) => {
          let json = {
            id: id,
            amount: value,
            operation: "mod",
          };
          this.$api[this.apiName].save(json).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "修改成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    verHandleRoute(id) {
      this.$confirm("确定要核验此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          let json = {
            id: id,
            operation: "ver",
          };
          this.$api[this.apiName].save(json).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "核验成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    checkHandleRoute(id, bureau_id) {
      this.verData.id = id;
      this.verData.bureau_id = bureau_id;
      this.verShow = true;
    },
    okCheckHandleRoute() {
      let id = this.verData.id;
      let mobile = this.verData.mobile;
      //审核订单
      this.$api[this.apiName].status(id, 2, mobile)
        .then((response) => {
          if (response.code == 1) {
            this.$message({
              type: "success",
              message: "审核成功!",
            });
            this.getPageData();
          }
        });
        this.verShow = false;
    },
    cancelHandleRoute(id) {
      //审核订单
      this.$confirm("确定要取消此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName][this.apiStatus](id, 0).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "审核成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    changeMobileId(val) {
      this.verData.mobile = val;
    },
    handleChange() {
      this.getPageData(true);
    },
  },
  mounted() {
    this.getLoadList();
  },
};
</script>

<style>
.el-table .warning-row {
  background: oldlace;
}

.el-table .success-row {
  background: #f0f9eb;
}

.el-table .danger-row {
  background: #cccc99;
}
</style>
