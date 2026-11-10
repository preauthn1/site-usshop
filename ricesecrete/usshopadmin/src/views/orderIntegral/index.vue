<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">积分订单列表</b>
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
            range-separator="-"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            @change="handleChange"
          ></el-date-picker>
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
        <el-table-column label="订单编号" width="260">
          <template slot-scope="scope">
            <div class="title">订单号：{{ scope.row.order_no }}</div>
            <div class="title" v-if="scope.row.pay_no">
              支付单号：{{ scope.row.pay_no }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="商品信息">
          <template slot-scope="scope">
            <div class="img">
              <el-image
                style="max-width: 100px"
                :src="scope.row.product_img"
                :data-src="scope.row.product_img"
                fit="cover"
                lazy
              ></el-image>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="规格详情">
          <template slot-scope="scope">
            <div class="title multi-line-ellipsis">
              <span style="color: forestgreen">名称：</span
              >{{ scope.row.product_name }}
            </div>
            <div class="title">
              <span style="color: forestgreen">规格：</span
              >{{ scope.row.spec_json.spec }}
            </div>
            <div class="title">
              <span style="color: forestgreen">单价：</span>${{
                scope.row.spec_json.price
              }}
            </div>
            <div class="title">
              <span style="color: forestgreen">数量：</span
              >{{ scope.row.spec_json.number }}
            </div>
            <div class="title">
              <span style="color: forestgreen">订单总金额：</span
              >{{ scope.row.amount }}
            </div>
          </template>
        </el-table-column>
        <el-table-column label="收货地址">
          <template slot-scope="scope">
            <div v-if="scope.row.express_type == 1">
              <div
                class="contacts"
                v-for="(item, index) in scope.row.contacts"
                :key="index"
              >
                <div class="title">联系人：{{ item.name }}</div>
                <div class="title">联系电话：{{ item.mobile }}</div>
                <div class="title">
                  地址：{{ item.province }} {{ item.city }} {{ item.area }}
                  {{ item.address }}
                </div>
              </div>
            </div>
            <div v-else>
              <div class="title"><el-tag>到店自提</el-tag></div>
              <div class="title">自提时间：{{scope.row.take_day}}</div>
              <div class="title">
                地址：{{ scope.row.goods_address.province }}
                {{ scope.row.goods_address.city }}
                {{ scope.row.goods_address.area }}
                {{ scope.row.goods_address.address }}
              </div>
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
              @click="cancelHandleRoute(scope.row.id)"
              v-if="
                hasPerm('orderIntegralEdit') &&
                scope.row.status < 2 &&
                scope.row.status > 0
              "
              >取消订单</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="sendHandleRoute(scope.row.id)"
              v-if="hasPerm('orderIntegralEdit') && scope.row.status == 2"
              >发货</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="okRefundRoute(scope.row)"
              v-if="hasPerm('orderIntegralEdit') && scope.row.status == 2"
              >退积分</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="setOrderPrice(scope.row.id)"
              v-if="hasPerm('orderIntegralStatus') && scope.row.status == 1"
              >修改积分</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="checkRefundRoute(scope.row)"
              v-if="hasPerm('orderIntegralStatus') && scope.row.status == 5"
              >审核退款</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="verHandleRoute(scope.row.id)"
              v-if="hasPerm('orderIntegralStatus') && scope.row.status == 3"
              >确认收货</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="handleRoute(scope.row.id, 'orderIntegralEdit')"
              v-if="hasPerm('orderIntegralEdit')"
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
    <el-dialog title="退款审核" :visible.sync="refundShow" width="600px">
      <el-form :model="objForm" label-width="80px">
        <el-form-item label="状态">
          <el-radio v-model="objForm.check" :label="0">拒绝</el-radio>
          <el-radio v-model="objForm.check" :label="1">同意</el-radio>
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
      <div slot="footer" class="dialog-footer">
        <el-button @click="refundShow = false">取 消</el-button>
        <el-button type="primary" @click="checkRefundOrder()">确 定</el-button>
      </div>
    </el-dialog>
    <el-dialog title="发货" :visible.sync="sendShow" width="600px">
      <el-form :model="sendObjForm" label-width="80px">
        <el-form-item label="物流公司">
          <el-select v-model="sendObjForm.logistics_id" clearable>
            <el-option
              :label="item.name"
              :value="item.id"
              v-for="(item, index) in logisticsList"
              :key="index"
            ></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="运单号">
          <el-input
            v-model="sendObjForm.number"
            type="textarea"
            :rows="5"
            placeholder="多运单号请换行"
          ></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="sendShow = false">取 消</el-button>
        <el-button type="primary" @click="okSendHandleRoute()">确 定</el-button>
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
      apiName: "orderTourism",
      statusList: [], //订单状态
      logisticsList: [], //物流公司
      queryForm: {
        type: 3, //订单类型 1旅游套餐 2正常商品 3积分商品
        time_section: [],
        status: "", //订单状态
        keyword: "",
      },
      objForm: {
        check: 0,
        pay_amount: "",
        amount: "",
        remark: "",
      },
      sendObjForm: {
        id: "", //订单id
        logistics_id: "", //物流id
        number: "", //物流单号
      },
      refundShow: false,
      sendShow: false,
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
          this.logisticsList = response.data.logisticsList;
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
      this.objForm.amount = row.pay_amount;
      this.objForm.order_damages = row.order_damages;
      this.objForm.remark = row.refund_remark;
    },
    checkRefundOrder() {
      let json = {
        id: this.objForm.id,
        amount: this.objForm.amount,
        check: this.objForm.check,
        operation: "refund_goods",
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
    okRefundRoute(row) {
      this.$confirm("确定要退款吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        let json = {
          id: row.id,
          amount: row.pay_amount,
          check: 1,
          operation: "refund_goods",
        };
        this.$api[this.apiName].save(json).then((response) => {
          if (response.code == 1) {
            this.$message({
              type: "success",
              message: "退款成功!",
            });
            this.getPageData();
          }
        });
      });
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
      this.$confirm("确定要签收此订单吗？", "提示", {
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
                message: "签收成功!",
              });
              this.getPageData();
            }
          });
        })
        .catch(() => {});
    },
    checkHandleRoute(id) {
      //审核订单
      this.$confirm("确定要审核此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName][this.apiStatus](id, 2).then((response) => {
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
    cancelHandleRoute(id) {
      //审核订单
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
    sendHandleRoute(id) {
      this.sendObjForm.id = id;
      this.sendShow = true;
    },
    okSendHandleRoute() {
      this.sendObjForm.operation = "send";
      this.$api[this.apiName].save(this.sendObjForm).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: "发货成功!",
          });
          this.getPageData();
        }
      });
      this.sendShow = false;
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
.multi-line-ellipsis {
  display: -webkit-box;
  -webkit-line-clamp: 2; /* 显示的行数 */
  -webkit-box-orient: vertical;
  overflow: hidden;
}

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
