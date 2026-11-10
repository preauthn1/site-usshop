class DataBase {
    constructor({ dbName, version, tableNames }) {
      this.dbName = dbName;
      this.version = version;
      this.tableNames = tableNames;
      this.indexedDB = null;
    }
  
    // 创建表
    createTable() {
      this.tableNames.forEach((name) => {
        if (!this.indexedDB) return;
        if (!this.indexedDB.objectStoreNames.contains(name)) {
          this.indexedDB.createObjectStore(name, { keyPath: "id" });
        }
      });
    }
  
    // 启动数据库
    open() {
      return new Promise((resolve, reject) => {
        const request = window.indexedDB.open(this.dbName, this.version);
        request.onsuccess = (event) => {
          this.indexedDB = event.target.result;
          resolve();
        };
        request.onerror = () => {
          reject("数据库打开报错");
        };
        request.onupgradeneeded = (event) => {
          this.indexedDB = event.target.result;
          this.createTable();
        };
      });
    }
  
    // 添加数据
    insert(storeName, data) {
      if (!this.indexedDB) throw new Error("数据库未初始化");
      return new Promise((resolve, reject) => {
        const request = this.indexedDB
          .transaction([storeName], "readwrite")
          .objectStore(storeName)
          .put(data);
        request.onsuccess = () => resolve();
        request.onerror = () => reject("数据写入失败");
      });
    }
  
    // 根据id获取数据
    select(storeName, key) {
      if (!this.indexedDB) throw new Error("数据库未初始化");
      return new Promise((resolve, reject) => {
        const transaction = this.indexedDB.transaction([storeName]);
        const objectStore = transaction.objectStore(storeName);
        const request = objectStore.get(key);
        request.onsuccess = () => resolve(request.result?.data);
        request.onerror = () => reject("事务失败");
      });
    }
  
    // 获取所有数据
    selectAll(storeName) {
      if (!this.indexedDB) throw new Error("数据库未初始化");
      return new Promise((resolve, reject) => {
        const transaction = this.indexedDB.transaction([storeName]);
        const objectStore = transaction.objectStore(storeName);
        const request = objectStore.getAll();
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject("事务失败");
      });
    }
  
    // 根据id删除数据
    delete(storeName, id) {
      if (!this.indexedDB) throw new Error("数据库未初始化");
      return new Promise((resolve, reject) => {
        const request = this.indexedDB
          .transaction([storeName], "readwrite")
          .objectStore(storeName)
          .delete(id);
        request.onsuccess = () => resolve();
        request.onerror = () => reject("数据删除失败");
      });
    }
  }
  
  export default DataBase;
  