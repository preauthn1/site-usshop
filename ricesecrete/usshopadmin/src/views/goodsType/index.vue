<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">{{ $t('商品') + $t('分类') + $t('列表') }}</b>
        <el-button type="danger" size="small" icon="el-icon-plus" @click="dialogHandleRoute('')"
                   v-if="hasPerm('goodsTypeEdit')" round class="u-right u-5mt">{{ $t('添加') }}
        </el-button>
      </div>
      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('分类')+$t('状态')" style="margin-left: 100px">
          <el-select @change="handleChange()" v-model="queryForm.status" clearable>
            <el-option :label="$t('全部')" value=""></el-option>
            <el-option :label="$t('启用')" value="1"></el-option>
            <el-option :label="$t('禁用')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getTreeDatas()">{{ $t('查询') }}</el-button>
        </el-form-item>
      </el-form>
      <el-button size="mini" type="primary" @click="handleStatusIds(1)">{{ $t('批量') + $t('启用') }}</el-button>
      <el-button size="mini" type="warning" @click="handleStatusIds(0)">{{ $t('批量') + $t('禁用') }}</el-button>
      <el-table :data="tableData" row-key="id" class="u-mt-6" @selection-change="handleSelectionChange">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column :label="$t('分类')+$t('名称')" prop="name"></el-table-column>
        <el-table-column :label="$t('图片')">
          <template slot-scope="scope">
            <el-image style="'width: 50px; height: 50px" :src="scope.row.img" :data-src="scope.row.img" fit="cover"
                      lazy></el-image>
          </template>
        </el-table-column>
        <el-table-column :label="$t('排序')" prop="sort"></el-table-column>
        <el-table-column :label="$t('显示')+$t('状态')">
          <template slot-scope="scope">
            <el-tag :type="scope.row.is_show == 1 ? 'success' : 'danger'">
              {{ scope.row.is_show == 1 ? $t('显示') : $t('不显示') }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
                       @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作')" width="200">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="dialogHandlePidRoute(scope.row.id)">{{ $t('添加子类') }}</el-button>
            <el-button size="mini" type="text" @click="dialogHandleRoute(scope.row.id)">{{ $t('编辑') }}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)">{{ $t('删除') }}</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>
    <el-dialog :title="dialogTitle" class="el-dialog-edit" :modal-append-to-body="false" width="80%"
               :visible.sync="dialogVisible">
      <edit :id="id" :pid="pid" @submitEnd="submitEnd" v-if="dialogVisible"></edit>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
import Edit from "./edit.vue";

export default {
  components: {Edit},
  mixins: [commonPage],
  data() {
    return {
      apiName: "goodsType",
      isTree: true,
      title: this.langt("商品分类"),
      queryForm: {
        status: '',
      },
      idArray: [],
    };
  },
  methods: {
    // 下拉事件
    handleChange() {
      this.getTreeDatas();
    },
    getTreeDatas() {
      this.$api[this.apiName]
        .tree(this.queryForm)
        .then((response) => {
          if (response.code == 1) {
            this.tableData = response.data.treeData;
          }
        })
        .catch(() => {
        });
    },
    handleSelectionChange(val) {
      val.map((item) => {
        this.idArray.push(item.id);
      });
    },
    handleStatusIds(val) {
      if (this.idArray == "") {
        this.$message({
          type: "error",
          message: this.langt("请选择要操作的数据"),
        });
      } else {
        if (val == 1) {
          this.$confirm(this.langt("确认启用吗"), this.langt("提示"), {
            confirmButtonText: this.langt("确定"),
            cancelButtonText: this.langt("取消"),
            type: "warning",
          })
            .then(() => {
              this.$api[this.apiName]
                .status(this.idArray, 0)
                .then((response) => {
                  if (response.code == 1) {
                    this.$message({
                      type: "success",
                      message: this.langt("启用成功"),
                    });
                    this.onLoad();
                  }
                });
            })
            .catch(() => {
            });
        } else {
          this.$confirm(this.langt("确认禁用吗"), this.langt("提示"), {
            confirmButtonText: this.langt("确定"),
            cancelButtonText: this.langt("取消"),
            type: "warning",
          })
            .then(() => {
              this.$api[this.apiName]
                .status(this.idArray, 1)
                .then((response) => {
                  if (response.code == 1) {
                    this.$message({
                      type: "success",
                      message: this.langt("禁用成功"),
                    });
                    this.onLoad();
                  }
                });
            })
            .catch(() => {
            });
        }
      }
    },
  },
};
</script>
