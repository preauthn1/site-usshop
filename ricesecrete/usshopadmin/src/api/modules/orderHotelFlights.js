import { get, post } from "@/libs/axios";

const orderHotelFlights = {
  // 分页列表
  page(queryData) {
    return get("/orderHotelFlights/page", queryData);
  },
  // 单条信息
  info(id) {
    return get("/orderHotelFlights/info", { id: id });
  },
  // 保存
  save(formData) {
    return post("/orderHotelFlights/save", formData);
  },
  // 保存状态
  status(id, status, mobile = "") {
    return post("/orderHotelFlights/status", {
      id: id,
      status: status,
      mobile: mobile,
    });
  },
  // 删除
  del(id) {
    return post("/orderHotelFlights/delete", { id: id });
  },
};

export default orderHotelFlights;
