export default {
  components: {},
  data() {
    return {
      apiName: "",
      apiPage: "page",
      apiStatus: "status",
      isTree: false,
      tableData: [],
      tempTableData: [],
      tableDataCount: 0,
      currentPage: 1,
      limit: 10,
      total: 0,
      other: [], //其他数据
      queryForm: {},
      queryFormId: "id",
      deleteText: this.langt("确认删除该数据吗"),
      dialogVisible: false,
      title: "",
      dialogTitle: "",
      id: "",
      pid: "",
      maxHeight: 550,
    };
  },
  computed: {
    getIndexNo() {
      return this.limit * (this.currentPage - 1) + 1;
    },
  },
  methods: {
    beforeOnLoad() {
    },
    afterOnLoad() {
    },
    onLoad() {
      let that = this;
      this.beforeOnLoad();
      if (this.isTree) {
        this.getTreeData(that.queryForm).then(() => {
          this.afterOnLoad();
        });
      } else {
        this.getPageData().then(() => {
          this.afterOnLoad();
        });
      }
    },
    getTreeData(queryForm) {
      return new Promise((resolve, reject) => {
        this.$api[this.apiName]
          .tree(queryForm)
          .then((response) => {
            if (response.code == 1) {
              this.tableData = response.data.treeData;
            }
            resolve();
          })
          .catch((e) => {
            reject(e);
          });
      });
    },
    getPageData(refresh = false) {
      return new Promise((resolve, reject) => {
        if (refresh) {
          this.currentPage = 1;
          this.total = 0;
        }
        let query = this.queryForm;
        query.page = this.currentPage;
        query.limit = this.limit;
        // window.console.log(this.$api[this.apiName])
        // console.log(this.apiName);

        this.$api[this.apiName][this.apiPage](query)
          .then((response) => {
            if (response.code == 1) {
              let data = response.data;
              // console.log(data);
              this.tempTableData = data.pageList;
              this.currentPage = data.currentPage;
              this.total = data.totalCount;
              this.other = data.other;
            }
            resolve();
          })
          .catch((e) => {
            reject(e);
          });
      });
    },
    handleSizeChange(val) {
      this.limit = val;
      this.getPageData();
    },
    handlePageChange(val) {
      this.currentPage = val;
      this.getPageData();
    },
    dialogHandleRoute(id) {
      if (id) {
        this.id = id;
        this.dialogTitle = this.langt('编辑/查看') + this.title;
      } else {
        this.id = "";
        this.dialogTitle = this.langt('新增') + this.title;
      }
      this.dialogVisible = true;
    },
    dialogHandlePidRoute(pid) {
      if (pid) {
        this.pid = pid;
        this.dialogTitle = this.langt("新增") + this.title;
        this.dialogVisible = true;
      }
    },
    submitEnd(status) {
      this.dialogVisible = false;
      if (status) {
        if (this.isTree) {
          this.getTreeDatas();
        } else {
          this.getPageData();
        }
      }
    },
    handleRoute(id, routeName) {
      this.$router
        .push({
          name: routeName,
          params: {id: id, page: this.currentPage},
        })
        .catch(() => {
        });
    },
    handlePidRoute(pid, routeName) {
      this.$router
        .push({
          name: routeName,
          params: {pid: pid, page: this.currentPage},
        })
        .catch(() => {
        });
    },
    handleStatus(id, status) {
      this.$api[this.apiName][this.apiStatus](id, status).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: this.langt("操作成功"),
          });
        }
      });
    },
    handleDelete(id) {
      this.$confirm(this.deleteText, this.langt("提示"), {
        confirmButtonText: this.langt("确定"),
        cancelButtonText: this.langt("取消"),
        type: "warning",
      })
        .then(() => {
          this.$api[this.apiName].del(id).then((response) => {
            if (response.code == 1) {
              let msg =
                this.deleteText.indexOf("删除") > -1
                  ? this.langt("删除成功")
                  : this.langt("移除成功");
              this.$message({
                type: "success",
                message: msg,
              });
              this.onLoad();
            }
          });
        })
        .catch(() => {
        });
    },
    // 多语言
    langt(text) {
      if (!text) {
        return text;
      }
      return this.$t(text);
    }
  },
  watch: {
    tempTableData(val, oldVal) {
      let that = this;
      if (val != oldVal) {
        that.tableData = [];
        that.tableDataCount = 0;
        if (val.length <= 20) {
          that.tableData = val;
        } else {
          for (let i = 0; i < Math.ceil(val.length / 10); i++) {
            setTimeout(function () {
              let j = that.tableDataCount;
              for (; j < 10 + that.tableDataCount; j++) {
                that.tableData.push(val[j]);
              }
              that.tableDataCount = j;
            }, i * 800);
          }
        }
      }
    },
  },
  created() {
    if (this.$route.params.page) {
      this.currentPage = this.$route.params.page;
    }
    if (this.$route.params.id) {
      this.queryForm[this.queryFormId] = this.$route.params.id;
    }
    this.onLoad();
  },
};
