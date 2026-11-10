<template>
  <div class="home">
    <div class="home-block">
      
    </div>
    
  </div>
</template>

<script>
import icon_all from "@/assets/imgs/order-stat/icon-all.png";
import icon_pay from "@/assets/imgs/order-stat/icon-pay.png";
import icon_delivery from "@/assets/imgs/order-stat/icon-delivery.png";
import icon_refund from "@/assets/imgs/order-stat/icon-refund.png";
import G2 from "@antv/g2";
import { hideLoading } from "@/libs/loading";

export default {
  data() {
    return {
      icon_all: icon_all,
      icon_pay: icon_pay,
      icon_delivery: icon_delivery,
      icon_refund: icon_refund,
      shop: {},
      order: {},
      weapp: {},
      tradeOrder: null,
      timestr: "",
      ordertimestrInfo: "",
      usertimestr: "",
      ordertimestr: "",
      agencytimestr: "",
      agencyRankList: [],
      numWarn: 10,
      storeWarnList: [],
      orderStati: [],
      statistics: {
        cat: {
          cat_sum: 0, //猫数量
          cat_type: 0, //猫种类
        },
        user:{
          user_sum:0,//用户数量
          member_sum:0,//会员数量
        },
        article: {
          article_sum: 0, //文章数量
          video_sum: 0, //视频数量
          special_sum: 0, //专栏数量
        },
        benefit: {
          time: 0, //救助次数
          capital_add: 0, //基金收入
          capital_sub: 0, //基金支出
          capital_dif: 0, //基金剩余
        },
      },
    };
  },
  methods: {
    times() {
      this.getOrder();
    },
    //小程序实时统计
    xcx_times() {
      this.getWeappRealtime();
    },
    agency_times() {
      this.getAgencyRank();
    },
    order_times() {
      this.getInitData_order();
    },
    onNumWarn(num) {
      this.numWarn = num;
      this.storeWarn(this.numWarn);
    },
    initChartTrade() {
      this.tradeOrder = new G2.Chart({
        container: "tradeOrder",
        forceFit: true,
        height: 300,
        padding: "auto",
      });
      this.tradeOrder.source([], {
        order_total: {
          min: 0,
          type: "linear",
          tickCount: 5,
          nice: true,
          alias: "销售额",
        },
      });
      this.tradeOrder.line().position("create_date*order_total");
      this.tradeOrder.render();
    },
    // 商场统计
    getInitData() {
      return new Promise((resolve, reject) => {
        this.$api.dashboard
          .statistics({
            order_time: this.ordertimestr,
            shop_time: this.timestr,
          })
          .then((res) => {
            if (res.code == 1) {
              this.shop = res.data.shop;
              this.order = res.data.order;
              this.tradeOrder.changeData(res.data.trade_order);
              this.statistics = res.data.statistics;
            }
            resolve();
          })
          .catch((e) => {
            reject(e);
          });
      });
    },
    // 订单统计
    getInitData_order() {
      return new Promise((resolve, reject) => {
        this.$api.dashboard
          .statistics({
            order_time: this.ordertimestr,
            shop_time: this.timestr,
          })
          .then((res) => {
            if (res.code == 1) {
              this.shop = res.data.shop;
              this.order = res.data.order;
              this.tradeOrder.changeData(res.data.trade_order);
              this.statistics = res.data.statistics;
            }
            resolve();
          })
          .catch((e) => {
            reject(e);
          });
      });
    },
    getWeappRealtime() {
      this.$api.analysis
        .userRealtime({
          start_time: this.usertimestr[0],
          end_time: this.usertimestr[1],
        })
        .then((res) => {
          if (res.code == 1) {
            this.weapp = res.data;
          }
        });
      setTimeout(function () {
        hideLoading();
      }, 1500);
    },
    //群主收益排行
    getAgencyRank() {
      this.$api.dashboard
        .agencyRank({
          start_time: this.agencytimestr[0],
          end_time: this.agencytimestr[1],
        })
        .then((res) => {
          if (res.code == 1) {
            this.agencyRankList = res.data;
          }
        });
    },
    getOrder() {
      this.$api.dashboard
        .getOrder({
          start_time: this.ordertimestrInfo[0],
          end_time: this.ordertimestrInfo[1],
        })
        .then((res) => {
          if (res.code == 1) {
            this.orderStati = res.data;
          }
        });
    },
    //商品库存预警
    storeWarn(num) {
      this.$api.dashboard
        .storeWarn({
          num: num,
        })
        .then((res) => {
          // console.log(res);
          if (res.code == 1) {
            this.storeWarnList = res.data;
          }
        });
    },
    formatRatio(value) {
      return (value * 100).toFixed(2) + "%";
    },
    // 获取当天
    getDayTime() {
      var now = new Date();
      var year = now.getFullYear(); //得到年份
      var month = now.getMonth(); //得到月份
      var date = now.getDate(); //得到日期
      month = month + 1;
      month = month.toString().padStart(2, "0");
      date = date.toString().padStart(2, "0");
      var defaultDate = `${year}-${month}-${date}`;
      this.usertimestr = [defaultDate, defaultDate];
    },
    // 获取最近7天
    getWeekTime() {
      const end = new Date();
      const start = new Date();
      start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
      this.ordertimestr = [start, end];
    },
    // 获取当月
    getMothTime() {
      let date = new Date();
      let month = (date.getMonth() + 1).toString().padStart(2, "0");
      let year = date.getFullYear();
      let startTime = year + "-" + month + "-01";
      let day = new Date(year, month, 0);
      let endTime = year + "-" + month + "-" + day.getDate();
      this.timestr = [startTime, endTime];
      this.agencytimestr = [startTime, endTime];
      this.ordertimestrInfo = [startTime, endTime];
    },
  },
  created() {
    
  },
  mounted() {
    this.$nextTick(() => {
      
    });
  },
};
</script>

<style lang="scss" scoped>
.item-line {
  width: 20rpx;
  height: 30rpx;
}
.platform-total {
  margin-bottom: 12px;
  .item {
    margin-top: 20px;
    text-align: center;
    background: rgb(211, 113, 104);
    color: #ffffff;
    width: 40%;
    margin: 0 2%;
    display: inline-block;
    padding: 8px 0;
    border-radius: 10px;
    .item-text {
      font-size: 15px;
    }
  }
}
.line {
  width: 1px;
}
.home {
  ::v-deep .el-input__inner {
    width: 220px;
  }
  .home-block {
    display: flex;
    justify-content: space-between;
    .panel-l {
      flex: 6;
      // background: #ffffff;
      margin-right: 20px;
    }
    .panel-more {
      display: flex;
      justify-content: space-between;
    }
    .center-panel {
      margin-left: 10px;
      margin-right: 10px;
    }
    .panel-r {
      flex: 4;
      // background: #ffffff;
    }
    .panel-width {
      width: 100%;
    }
    .bg-white {
      background-color: #fff;
      margin-bottom: 20px;
      .panel {
        margin-bottom: 0;
      }
    }
    .fund {
      flex: 4;
      padding: 18px;
      margin-right: 10px;
      background: #ffffff;
    }
    .agency {
      flex: 6;
      padding: 20px;
      background: #ffffff;
    }
    .info-item {
      margin-top: 10px;
      .tit-l {
        display: inline-block;
        margin-right: 15px;
      }
    }
    .agency-list {
      height: 103px;
      overflow-y: auto;
    }
    .agency-item {
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
      padding-right: 10px;
    }
    .stock-list {
      height: 325px;
      overflow-y: auto;
    }
    .stock-item {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
      padding-right: 20px;
    }
    .stock-item .stock-name {
      width: 50%;
    }
    .stock-item .stock-weight {
      width: 40%;
    }
    .stock-item .stock-num {
      width: 10%;
      text-align: right;
    }
    .tu-chart {
      width: 98%;
    }
  }
  .common-t {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .common-titles {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    &.tit-flex {
      justify-content: space-between;
      .left-tit {
        display: flex;
        justify-content: flex-start;
        align-items: center;
      }
    }
    .line {
      width: 1px;
      margin-right: 8px;
      height: 16px;
      border-left: 3px solid #3788ee;
    }
    .block {
      height: 30px;
    }
  }

  padding: 20px;

  .panel {
    background: #ffffff;
    padding: 6px;
    margin-bottom: 20px;
    box-sizing: border-box;
    &.weixin {
      width: 50%;
    }
    &.cat-info {
      width: 25%;
    }
    &.all-content {
      width: 25%;
    }
    .content {
      &.wx-body {
        display: flex;
        .left {
          margin-right: 30px;
        }
        .xin-tit {
          margin-right: 10px;
        }
        .wx-item {
          display: flex;
          align-items: center;
          margin-top: 15px;
        }
      }
      margin-top: 10px;
      .total-wrapper {
        display: flex;
        .item {
          margin-top: 20px;
          .inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 15px 20px;
            color: #ffffff;

            &.bg-1 {
              background: linear-gradient(-125deg, #57bdbf, #2f9de2);
            }

            &.bg-2 {
              background: linear-gradient(-125deg, #ff7d7d, #fb2c95);
            }

            &.bg-3 {
              background: linear-gradient(-113deg, #c543d8, #925cc3);
            }

            &.bg-4 {
              background: linear-gradient(-141deg, #ecca1b, #f39526);
            }
            &.bg-5 {
              background: linear-gradient(-141deg, #04ec1f, #058d0e);
            }

            .info {
              .tit {
                font-size: 16px;
              }

              .num {
                font-size: 22px;
                margin-top: 20px;
              }
            }

            .iconfont {
              font-size: 64px;
            }
          }
        }
      }

      .total-order {
        padding-bottom: 10px;

        .item {
          margin-top: 20px;

          .inner {
            display: flex;
            justify-content: center;
            align-items: center;

            .icon {
              width: 32px;
            }

            .info {
              min-width: 70px;
              margin-left: 20px;

              .tit {
                font-size: 16px;
              }

              .num {
                margin-top: 5px;
                font-size: 24px;
                font-weight: bold;
              }
            }
          }
        }
      }

      .total-weapp {
        .item {
          margin-top: 20px;

          .inner {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            .tit {
              font-size: 14px;
            }

            .num {
              margin-top: 10px;
              font-size: 25px;
              font-weight: bold;
            }

            .sub {
              margin-top: 10px;
              color: #666666;
            }
          }
        }
      }
    }
  }
}
</style>
