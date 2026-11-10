import { get, post,upload } from "@/libs/axios";

const common = {
  // 统计
  config() {
    return get("/common/config", {});
  },
  //更新语言条目
  updateLang(title) {
    return post("/common/updateLang", { title: title });
  },
  // 上传文件
  upload(formatData) {
    return upload("/common/upload", formatData);
  },
  // 删除文件
  delfile(url) {
    return get("/common/delfile", { url: url });
  },
};

export default common;
