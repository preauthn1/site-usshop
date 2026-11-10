import http from "../utils/request";
//successTips需要提示成功时使用
export class AuthService {
  /* 首页配置 */
  static async banner() {
    try {
      return await http.get("/index/banner");
    } catch (error) {
      throw error;
    }
  }
  //首页推荐
  static async prodect_list(params) {
    try {
      return await http.get("/goods/product_list", { params });
    } catch (error) {
      throw error;
    }
  }
  //一级、二级分类
  static async classification(params) {
    try {
      return await http.get("/goods/type", { params });
    } catch (error) {
      throw error;
    }
  }
  //商品详情
  static async goods_detail(params) {
    try {
      return await http.get("/goods/product_details", { params });
    } catch (error) {
      throw error;
    }
  }
  //购物车
  static async usercart(data) {
    try {
      return await http.post("/goods/add_cart", data);
    } catch (error) {
      throw error;
    }
  }
  //购物车列表
  static async usercartlist(params) {
    try {
      return await http.get("/goods/cart_list", { params });
    } catch (error) {
      throw error;
    }
  }

  static async goodsTypeWithGoods(params) {
    try {
      return await http.get("/goods/type_with_goods", { params });
    } catch (error) {
      throw error;
    }
  }
  //批量删除购物车
  static async clear_cart(data) {
    try {
      return await http.post("goods/clear_cart", data);
    } catch (error) {
      throw error;
    }
  }

  // 文件上传
  static async uploadfile(data) {
    try {
      const headers = {
        "Content-Type": "multipart/form-data",
      };
      return await http.post("/common/upload", data, { headers });
    } catch (error) {
      throw error;
    }
  }
}
