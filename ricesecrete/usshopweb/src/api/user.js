import http from "@/utils/request";
//successTips需要提示成功时使用
export class UserService {
    /* 登录 */
    static async login(data) {
        try {
            return await http.post(
                "/user/login", data
            );
        } catch (error) {
            throw error;
        }
    }
    //注册
    static async register(data) {
        try {
            return await http.post(
                "/user/register", data
            );
        } catch (error) {
            throw error;
        }
    }
    //用户消息/user/userInfo
    static async userInfo(params) {
        try {
            return await http.get(
                "/user/userInfo", { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //用户消息/user/userInfo
    static async up_userInfo(data) {
        try {
            return await http.post(
                "/user/userInfo", data
            );
        } catch (error) {
            throw error;
        }
    }
    //收藏
    static async collect(params) {
        try {
            return await http.get(
                "/user/collect_product", { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //收藏列表
    static async collectlist(params) {
        try {
            return await http.get(
                "/user/collect_list", { params }
            );
        } catch (error) {
            throw error;
        }
    }

    //收货地址列表
    static async addressPage(params) {
        try {
            return await http.get(
                "/address/page", { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //添加收货地址
    static async addressSave(params) {
        try {
            return await http.post(
                "/address/save", params
            );
        } catch (error) {
            throw error;
        }
    }

    //添加收货地址 
    static async addressDelete(params) {
        try {
            return await http.post(
                "/address/delete", params
            );
        } catch (error) {
            throw error;
        }
    }


    //修改密码
    static async changePassword(params) {
        console.log(params);
        try {
            return await http.post(
                "/change_password", params
            );
        } catch (error) {
            throw error;
        }
    }

    static async view_list(params) {
        try {
            return await http.get(
                "/user/view_list", { params }
            );
        } catch (error) {
            throw error;
        }
    }

    static async orderList(params) {
        try {
            return await http.get(
                "/order/order_list", { params }
            );
        } catch (error) {
            throw error;
        }
    }
    static async goodsOrderDetails(params) {
        try {
            return await http.get(
                "/goods/order_details", { params }
            );
        } catch (error) {
            throw error;
        }
    }

    //用户余额日志
    static async money_log(params) {
        try {
            return await http.get(
                "/user/money_log", { params }

            );
        } catch (error) {
            throw error;
        }
    }
    static async orderCancel(params) {
        try {
            return await http.post(
                "order/cancel_order", params

            );
        } catch (error) {
            throw error;
        }
    }
    //支付渠道
    static async payment_channel(params) {
        try {
            return await http.get(
                "/payment_channel", {params}

            );
        } catch (error) {
            throw error;
        }
    }
    //充值
    static async recharge(data) {
        try {
            return await http.post(
                "/recharge", data

            );
        } catch (error) {
            throw error;
        }
    }
    //hobbyList
    static async hobbyList(params) {
        try {
            return await http.get(
                "/hobbyList", {params}

            );
        } catch (error) {
            throw error;
        }
    }
    //修改兴趣爱好/hobby
    static async hobby(data) {
        try {
            return await http.post(
                "/hobby", data

            );
        } catch (error) {
            throw error;
        }
    }

    //提现
    static async withdrawal(data) {
        try {
            return await http.post(
                "/withdrawal", data

            );
        } catch (error) {
            throw error;
        }
    }
     //系统配置
     static async systemConfig (params) {
        try {
            return await http.get(
                "/common/config", {params}

            );
        } catch (error) {
            throw error;
        }
    }
}