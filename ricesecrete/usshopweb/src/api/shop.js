import http from "@/utils/request";
//successTips需要提示成功时使用
export class ShopService {
    //下单前规格详情
    static async order_info(data) {
        try {
            return await http.post(
                "/goods/order_info",data
            );
        } catch (error) {
            throw error;
        }
    }
    //下单/order/balance_pay
    static async create_order(data) {
        try {
            return await http.post(
                "/goods/create_order",data
            );
        } catch (error) {
            throw error;
        }
    }
    //使用余额支付
    static async balance_pay(params) {
        try {
            return await http.get(
                "/order/balance_pay",{params}
            );
        } catch (error) {
            throw error;
        }
    }
    //使用USDT支付
    static async pay_info(params) {
        try {
            return await http.get(
                "/order/pay_info",{params}
            );
        } catch (error) {
            throw error;
        }
    }
     //商品订单分享详情
     static async order_share_details(params) {
        try {
            return await http.get(
                "/goods/order_share_details",{params}
            );
        } catch (error) {
            throw error;
        }
    }
     //上传支付凭证
     static async update_pay_img(data) {
        try {
            return await http.post(
                "/order/update_pay_img",data
            );
        } catch (error) {
            throw error;
        }
    }
    
}