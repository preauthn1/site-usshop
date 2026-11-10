import Storage from "./DataBaseStorage";
import md5 from "md5";

class ApiStorage {
  constructor({ api, success, key, isNotFetchUpdateAgain }) {
    this.api = api;
    this.success = success; //成功的回调;
    this.isNotFetchUpdateAgain = isNotFetchUpdateAgain; //是否不要再次更新，否，表示二次拉取接口更新数据，true ，表示不更新
    this.storage = new Storage(md5(this.api.toString() + localStorage.setItem("locale",'en') + key));
    this.storage.setTableName("quick");
  }

  async getData() {
    const storageData = await this.storage.getData();
    console.log(this.storage,this.isNotFetchUpdateAgain,'===v');
    
    if (storageData) {
      this.success(storageData);
      this.getApiData(this.isNotFetchUpdateAgain, true, storageData);
    } else {
      await this.getApiData(false, true);
    }
  }

  async getApiData(isNotFetchUpdateAgain = false, isStorage = false, storageData) {
    const res = await this.api();
    if (res.code === 1) {
      if (isStorage) this.storage.setData(res.data);
       // 判断数据变化，才进行二次更新
      if (!isNotFetchUpdateAgain && JSON.stringify(storageData) !== JSON.stringify(res.data)) {
        this.success(res.data);
      }
    }
  }
}

export default ApiStorage;
