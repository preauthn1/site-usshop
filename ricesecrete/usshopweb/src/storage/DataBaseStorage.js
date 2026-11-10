import { dataBase } from "@/utils/databaseBusiness";

class DataBaseStorage {
  constructor(name) {
    this.name = name;
    this.tableName = "storage";
  }
  static data = [];
    // quick 快速查询表，会吧所有的存储取出来放到内存中，注意使用，不要存放过多的数据
  static tableName = "quick";

  setTableName(name) {
    this.tableName = name;
  }

  async setData(data) {
    if (this.tableName === DataBaseStorage.tableName) {
      DataBaseStorage.setDataBaseStorageData({ id: this.name, data });
    }
    await dataBase.insert(this.tableName, { id: this.name, data });
  }

  async getData() {
    if (this.tableName === DataBaseStorage.tableName) {
      const data = DataBaseStorage.data.find(item => item.id === this.name)?.data;
      return data;
    }
    return await dataBase.select("storage", this.name);
  }

  static setDataBaseStorageData(data) {
    const item = DataBaseStorage.data.find(item => item.id === data.id);
    if (item) {
      item.data = data.data;
    } else {
      DataBaseStorage.data.push(data);
    }
  }

  async removeData() {
    await dataBase.delete(this.tableName, this.name);
  }

  static async init() {
    this.data = await dataBase.selectAll(DataBaseStorage.tableName);
  }
}

export default DataBaseStorage;