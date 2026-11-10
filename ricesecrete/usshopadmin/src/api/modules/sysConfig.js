import { get, post } from "@/libs/axios";

const sysConfig = {
    // 分页列表
    page(queryData) {
        return get("/sys/config/page", queryData);
    },
    // 单条信息
    info(id) {
        return get("/sys/config/info", { id: id });
    },
    // 保存
    save(formData) {
        return post("/sys/config/save", formData);
    },
    // 删除
    del(id) {
        return post("/sys/config/delete", { id: id });
    },
};

export default sysConfig;
