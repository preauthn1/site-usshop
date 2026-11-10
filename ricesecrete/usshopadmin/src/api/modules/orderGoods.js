import { get, post } from "@/libs/axios";

const orderGoods = {
  // 分页列表
  page(queryData) {
    return get("/orderGoods/page", queryData);
  },
  // 单条信息
  info(id) {
    return get("/orderGoods/info", { id: id });
  },
  // 保存
  save(formData) {
    return post("/orderGoods/save", formData);
  },
  // 保存状态
  status(id, status, mobile = "") {
    return post("/orderGoods/status", {
      id: id,
      status: status,
      mobile: mobile,
    });
  },
  // 删除
  del(id) {
    return post("/orderGoods/delete", { id: id });
  },
};

export default orderGoods;
