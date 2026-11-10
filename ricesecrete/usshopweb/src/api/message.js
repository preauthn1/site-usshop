import http from "@/utils/request";
//successTips需要提示成功时使用
export class MessageService {
    /* 登录 */
    static async notification(params) {
        try {
            return await http.get(
                "/notification/page",{params}
            );
        } catch (error) {
            throw error;
        }
    }
    static async detail(params) {
        try {
            return await http.get(
                "/notification/detail",{params}
            );
        } catch (error) {
            throw error;
        }
    }
    //单条
    static async read(data) {
        try {
            return await http.post(
                "/notification/read",data
            );
        } catch (error) {
            throw error;
        }
    }
    //notification/readAll
    static async readAll(data) {
        try {
            return await http.post(
                "/notification/readAll",data
            );
        } catch (error) {
            throw error;
        }
    }
}