import http from "@/utils/request";
//successTips需要提示成功时使用
export class LocalesService {
    /* 翻译文件 */
    static async translate(params) {
        try {
            return await http.get(
                "/translate",params
            );
        } catch (error) {
            throw error;
        }
    }
    /* 语言列表/language */
    static async language(params) {
        try {
            return await http.get(
                "/language",params
            );
        } catch (error) {
            throw error;
        }
    }
    /* 货币列表 user/currency/change*/
    static async currency(params) {
        try {
            return await http.get(
                "/currency",params
            );
        } catch (error) {
            throw error;
        }
    }
     /* 更改货币 */
     static async change_currency(data) {
        try {
            return await http.post(
                "/user/currency/change",data
            );
        } catch (error) {
            throw error;
        }
    }
     /* 内容 */
     static async content(params) {
        try {
            return await http.get(
                "/article/detail",{params}
            );
        } catch (error) {
            throw error;
        }
    }
    //
    static async updateLang(params) {
        try {
            return await http.get(
                "/common/updateLang",{params}
            );
        } catch (error) {
            throw error;
        }
    }
}