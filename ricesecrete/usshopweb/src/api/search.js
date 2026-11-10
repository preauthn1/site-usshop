import http from "@/utils/request";
//successTips需要提示成功时使用
export class SearchService {
    //搜索关键词列表
    static async searchlist(data) {
        try {
            return await http.get(
                "/goods/search_recommend", data
            );
        } catch (error) {
            throw error;
        }
    }
    //清空关键词/tourism/del_keyword
    static async clear_keyword(params) {
        try {
            return await http.get(
                "/goods/clear_keyword", {params}
            );
        } catch (error) {
            throw error;
        }
    }
    //删除关键词
    static async del_keyword(params) {
        try {
            return await http.get(
                "/goods/del_keyword", {params}
            );
        } catch (error) {
            throw error;
        }
    }
}