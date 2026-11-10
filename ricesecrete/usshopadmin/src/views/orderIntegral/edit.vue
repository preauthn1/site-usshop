<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">积分订单详情 </b>
        <div class="u-right u-5mt">
          <el-button size="small" icon="el-icon-back" @click="$router.back()"
            >返回</el-button
          >
        </div>
      </div>
      <el-form
        v-if="objForm.id"
        ref="objForm"
        label-width="100px"
        class="u-form"
      >
        <el-form-item label="订单状态" v-if="objForm.status == 0">
          <el-steps
            :space="220"
            finish-status="success"
            align-center
            style="line-height: 1"
          >
            <el-step
              title="订单关闭"
              :description="objForm.order_item.close_time"
              style="padding: 0"
            ></el-step>
          </el-steps>
        </el-form-item>
        <el-form-item
          label="订单状态"
          v-if="objForm.status >= 5 && objForm.status <= 6"
        >
          <el-steps
            :space="220"
            :active="objForm.status - 1"
            finish-status="success"
            align-center
            style="line-height: 1"
          >
            <el-step
              title="待确认"
              :description="objForm.order_item.create_time"
              style="padding: 0"
            ></el-step>
            <el-step
              title="已付款"
              :description="objForm.order_item.pay_time"
            ></el-step>
            <el-step
              title="退款中"
              :description="objForm.order_item.refund_apply_time"
            ></el-step>
            <el-step
              title="退款成功"
              :description="objForm.order_item.refund_time"
            ></el-step>
          </el-steps>
        </el-form-item>
        <el-form-item
          label="订单状态"
          v-if="
            objForm.status != 0 && objForm.status != 5 && objForm.status != 6
          "
        >
          <el-steps
            :space="220"
            :active="objForm.status"
            finish-status="success"
            align-center
            style="line-height: 1"
          >
            <el-step
              title="待确认"
              :description="objForm.order_item.create_time"
              style="padding: 0"
            ></el-step>
            <el-step
              title="已付款"
              :description="objForm.order_item.pay_time"
            ></el-step>
            <el-step
              title="已发货"
              :description="objForm.order_item.send_time"
            ></el-step>
            <el-step
              title="已签收"
              :description="objForm.order_item.ver_time"
            ></el-step>
            <el-step
              title="已评论"
              :description="objForm.order_item.evaluate_time"
            ></el-step>
          </el-steps>
        </el-form-item>
        <el-form-item label="订单信息">
          <el-tag class="u-left tag">总积分：{{ objForm.amount }}</el-tag>
          <el-tag class="u-left tag" type="success"
            >实际支付：{{ objForm.pay_amount }}</el-tag
          >
          <el-tag class="u-left tag" type="warning"
            >退款积分：{{ objForm.refund_amount }}</el-tag
          >
          <div class="u-left u-5mt" style="margin-left: 50px">
            <el-button
              type="danger"
              size="small"
              @click="cancelHandleRoute(objForm.id)"
              v-if="objForm.status == 1"
              >取消订单</el-button
            >
            <el-button
              type="primary"
              size="small"
              @click="sendHandleRoute(objForm.id)"
              v-if="objForm.status == 2"
              >发货</el-button
            >
            <el-button
              type="danger"
              size="small"
              @click="okRefundRoute(objForm)"
              v-if="objForm.status == 2"
              >退款</el-button
            >
            <el-button
              type="warning"
              size="small"
              @click="payOrder(objForm.id)"
              v-if="objForm.status == 1"
              >后台付款</el-button
            >
            <el-button
              type="primary"
              size="small"
              @click="setOrderPrice(objForm.id)"
              v-if="objForm.status == 1"
              >修改积分</el-button
            >
            <el-button
              type="success"
              size="small"
              @click="verHandleRoute(objForm.id)"
              v-if="objForm.status == 3"
              >确认收货</el-button
            >
            <el-button
              type="danger"
              size="small"
              @click="checkRefundRoute(objForm)"
              v-if="objForm.status == 5"
              >审核退款</el-button
            >
          </div>
        </el-form-item>
        <el-form-item label="联系人信息">
          <div
            class="u-left u-5mr"
            v-for="(item, index) in objForm.order_item.contacts"
            :key="index"
          >
            <div class="u-left tag">姓名：{{ item.name }}</div>
            <div class="u-left tag">电话：{{ item.mobile }}</div>
          </div>
        </el-form-item>
        <el-form-item label="套餐详情">
          <el-card class="box-card" style="width: 500px; float: left">
            <div slot="header" class="clearfix">
              <span>{{ objForm.product_name }}</span>
            </div>
            <div style="width: 60%; display: inline-block; vertical-align: top">
              <el-image :src="objForm.product_img" fit="cover"></el-image>
            </div>
            <div style="width: 30%; margin-left: 10%; display: inline-block">
              <div>规格：{{ objForm.order_item.spec_json.spec }}</div>
              <div>价格：{{ objForm.order_item.spec_json.price }}</div>
              <div>数量：{{ objForm.order_item.spec_json.number }}</div>
            </div>
          </el-card>
          <el-card
            class="box-card"
            style="width: 500px; float: left; margin-left: 20px"
            v-if="objForm.status >= 3&&objForm.order_item.logistics.name"
          >
            <div slot="header" class="clearfix">
              <span>快递物流信息</span>
            </div>
            <div style="margin-bottom: 30px" v-if="objForm.order_item.logistics.name">
              <div>发货物流信息</div>
              <div>快递公司：{{ objForm.order_item.logistics.name }}</div>
              <div>单号：{{ objForm.order_item.logistics.number }}</div>
            </div>
            <div style="margin-bottom: 30px" v-if="objForm.order_item.refund_logistics.name">
              <div>退货物流信息</div>
              <div>快递公司：{{ objForm.order_item.refund_logistics.name }}</div>
              <div>单号：{{ objForm.order_item.refund_logistics.number }}</div>
            </div>
          </el-card>
        </el-form-item>
        <el-form-item label="操作记录">
          <el-table :data="objForm.handle_log" class="u-mt-10" v-viewer>
            <el-table-column label="id" prop="id" width="50"></el-table-column>
            <el-table-column label="管理员id" prop="admin_id"></el-table-column>
            <el-table-column
              label="管理员账号"
              prop="admin_username"
            ></el-table-column>
            <el-table-column
              label="管理员姓名"
              prop="admin_realname"
            ></el-table-column>
            <el-table-column label="操作类型" prop="type_str"></el-table-column>
            <el-table-column
              label="操作时间"
              prop="create_time"
            ></el-table-column>
            <el-table-column label="操作内容" prop="content"></el-table-column>
          </el-table>
        </el-form-item>
      </el-form>
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
          <el-form :model="objCheForm" label-width="80px">
            <el-form-item label="状态">
              <el-radio v-model="objCheForm.check" :label="0">拒绝</el-radio>
              <el-radio v-model="objCheForm.check" :label="1">同意</el-radio>
            </el-form-item>
            <el-form-item label="集合时间" v-if="objCheForm.check == 1">
              <div>{{ objCheForm.start_day }}({{ objCheForm.days }}天)</div>
            </el-form-item>
            <el-form-item label="支付积分" v-if="objCheForm.check == 1">
              <div>{{ objCheForm.pay_amount }}</div>
            </el-form-item>
            <el-form-item label="退款积分" v-if="objCheForm.check == 1">
              <el-input
                v-model="objCheForm.amount"
                type="text"
                placeholder="请输入退款积分"
              >
                <template slot="append">元</template>
              </el-input>
            </el-form-item>
            <el-form-item label="退款原因">
              <el-input
                v-model="objCheForm.remark"
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
import commonEdit from "@/mixins/common-edit";
export default {
  mixins: [commonEdit],
  data() {
    return {
      apiName: "orderTourism",
      backRoute: "orderGoodsIndex",
      objCheForm: {
        check: 0,
        amount: "",
        start_day: "",
        days: "",
        order_damages: [],
        remark: "",
      },
      logisticsList: [], //物流公司
      sendObjForm: {
        id: "", //订单id
        logistics_id: "", //物流id
        number: "", //物流单号
      },
      sendShow: false,
      refundShow: false,
      objForm: {
        order_item: {
          spec_json: {
            start_day: "",
            start_day_week: "",
            end_day: "",
            end_day_week: "",
            days_str: "",
            number: "",
            kid_number: "",
            price: "",
            kid_price: "",
          },
          contacts: [
            {
              name: "",
              mobile: "",
            },
          ],
        },
        amount: "",
        pay_amount: "",
        refund_amount: "",
        status: 0,
        status_str: "",
        handle_log: [],
      },
    };
  },
  methods: {
    checkRefundRoute(row) {
      console.log(row);
      this.refundShow = true;
      this.objCheForm.id = row.id;
      this.objCheForm.start_day = row.start_day;
      this.objCheForm.days = row.days;
      this.objCheForm.pay_amount = row.pay_amount;
      this.objCheForm.amount = row.temp_refund_amount;
      this.objCheForm.order_damages = row.order_damages;
      this.objCheForm.remark = row.refund_remark;
    },
    checkRefundOrder() {
      let json = {
        id: this.objCheForm.id,
        amount: this.objCheForm.amount,
        check: this.objCheForm.check,
        operation: "refund",
      };
      this.$api[this.apiName].save(json).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: "退款审核成功!",
          });
          this.getInfo();
        }
      });
      this.refundShow = false;
    },
    checkHandleRoute(id) {
      //审核订单
      this.$confirm("确定要审核此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 2).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "审核成功!",
              });
              this.getInfo();
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
                message: "审核成功!",
              });
              this.getInfo();
            }
          });
        })
        .catch(() => {});
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
            this.getInfo();
          }
        });
      });
    },
    payOrder(id) {
      this.$confirm("确定要支付此订单吗？", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].status(id, 2).then((response) => {
            if (response.code == 1) {
              this.$message({
                type: "success",
                message: "支付成功!",
              });
              this.getInfo();
            }
          });
        })
        .catch(() => {});
    },
    setOrderPrice(id) {
      this.$prompt("请输修改积分", "积分修改提示", {
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
              this.getInfo();
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
              this.getInfo();
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
          this.getInfo();
        }
      });
      this.sendShow = false;
    },
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          this.logisticsList = response.data.logisticsList;
        }
      });
    },
    afterGetInfo() {},
  },
  mounted() {
    this.getLoadList();
  },
};
</script>

<style>
.el-step__title {
  font-size: 16px;
}

.el-step__description {
  font-size: 15px;
}

.el-step.is-center .el-step__description {
  padding: 0;
  text-align: center;
}

.el-step__description.is-success {
  color: #666;
}

.tag {
  margin-left: 20px;
}
</style>
