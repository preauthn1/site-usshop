import http from "../utils/request";
//successTips需要提示成功时使用
export class HotelService {
    /* 首页配置 */
    static async hotelList(params) {
        try {
            return await http.get(
                "/amadeus/search",{params}
            );
        } catch (error) {
            throw error;
        }
    }
}