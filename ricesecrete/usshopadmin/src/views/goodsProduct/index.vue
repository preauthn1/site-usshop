<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('商品') + $t('列表') }}</b>
        </span>
        <el-button type="danger" size="small" icon="el-icon-plus" @click="dialogHandleRoute('')"
          v-if="hasPerm('goodsProductEdit')" round class="u-right u-5mt">{{ $t('添加') }}
        </el-button>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('商品') + $t('分类')">
          <el-cascader expand-trigger="hover" :options="levelTree" @change="handleLevelSelect" :placeholder="$t('分类')"
            clearable change-on-select :props="levelProps" v-model="level_ids" class="u-w-block"></el-cascader>
        </el-form-item>
        <el-form-item :label="$t('分类')">
          <el-select v-model="queryForm.status" clearable @change="getPageData(true)">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('上架')" value="1"></el-option>
            <el-option :label="$t('下架')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('商品') + $t('名称')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('请输入') + $t('商品') + $t('标题') + $t('查询')"
            clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{ $t('查询') }}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer @selection-change="handleSelectionChange"
        :header-cell-style="{ textAlign: 'center' }" :cell-style="{ textAlign: 'center' }">
        <el-table-column type="selection" width="55"></el-table-column>
        <el-table-column label="id" prop="id" width="50"></el-table-column>
        <el-table-column :label="$t('图片')" width="100">
          <template slot-scope="scope">
            <el-image style="max-height: 90px; max-width: 90px" :src="scope.row.img" :data-src="scope.row.img"
              fit="cover" lazy></el-image>
          </template>
        </el-table-column>

        <el-table-column :label="$t('商品') + $t('分类')" width="120">
          <template slot-scope="scope">
            <div>{{ scope.row.type_name }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('标题')">
          <template slot-scope="scope">
            <div >{{ substrlen(scope.row.title) }}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('规格')" width="650">
          <template slot-scope="scope">
            <el-table :data="scope.row.item_list" class="item-list">
              <el-table-column prop="name" :label="$t('名称')"></el-table-column>
              <el-table-column prop="cost_price" :label="$t('进价')"></el-table-column>
              <el-table-column prop="price" :label="$t('售价')"></el-table-column>
              <el-table-column prop="store" :label="$t('库存')"></el-table-column>
              <el-table-column prop="buy" :label="$t('销量')"></el-table-column>
              <el-table-column :label="$t('操作')" width="150">
                <template slot-scope="scopex">
                  <el-button size="mini" type="text" @click.native="storeGoodsProduct(scopex.row, scope.row.title)"
                    v-if="hasPerm('goodsProductEdit')">{{ $t('库存管理') }}
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
            <el-button size="mini" type="text" v-if="scope.row.item_sum > 3"
              @click.native="specDialogOpen(scope.row.id)">{{
                $t('查看更多') }}
            </el-button>
          </template>
        </el-table-column>
        <el-table-column :label="$t('销量')" prop="buy"></el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('排序')" prop="sort" width="80"></el-table-column>
        <el-table-column :label="$t('更新日期')" prop="update_time"></el-table-column>
        <el-table-column :label="$t('操作')" width="150" fixed="right">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click.native="storeDialogOpen(scope.row.id)"
              v-if="hasPerm('goodsProductEdit')">{{ $t('库存日志') }}
            </el-button>
            <el-button size="mini" type="text" @click.native="dialogHandleRoute(scope.row.id)"
              v-if="hasPerm('goodsProductEdit')">{{ $t('编辑') }}
            </el-button>
            <el-button size="mini" type="text" @click.native="handleDelete(scope.row.id)"
              v-if="hasPerm('goodsProductDelBtn')">{{ $t('删除') }}
            </el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
          :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
      </div>
    </el-card>
    <el-dialog :title="$t('商品规格')" :visible.sync="specDialogVisible" width="80%" center>
      <SpecList :product_id="product_id + ''"></SpecList>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="specDialogVisible = false">{{ $t('确定') }}</el-button>
      </span>
    </el-dialog>
    <el-dialog :title="$t('库存日志')" :visible.sync="storeDialogVisible" width="80%" center>
      <StoreList :product_id="product_id + ''"></StoreList>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="storeDialogVisible = false">{{ $t('确定') }}</el-button>
      </span>
    </el-dialog>
    <el-dialog :title="$t('库存管理')" :visible.sync="is_store_show" width="35%" append-to-body>
      <el-form :model="objForm" ref="objForm" label-width="100px" class="u-form" style="max-width: 90%">
        <el-form-item :label="$t('商品名称')">
          <div>{{ objForm.product_name }}</div>
        </el-form-item>
        <el-form-item :label="$t('规格名称')">
          <div>{{ objForm.spec }}</div>
        </el-form-item>
        <el-form-item :label="$t('库存')">
          <div>{{ objForm.store }}</div>
        </el-form-item>
        <el-form-item :label="$t('入库/出库')">
          <el-radio v-model="objForm.change" :label="3">{{ $t('入库') }}</el-radio>
          <el-radio v-model="objForm.change" :label="4">{{ $t('出库') }}</el-radio>
        </el-form-item>
        <el-form-item :label="$t('库存变动数量')">
          <el-input type="number" min="0" max="1000000" v-model="objForm.number" :placeholder="$t('库存变动数量')"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="is_store_show = false">{{ $t('取消') }}</el-button>
        <el-button type="primary" @click="okStore">{{ $t('确定') }}</el-button>
      </span>
    </el-dialog>
    <el-dialog :title="dialogTitle" class="el-dialog-edit" :modal-append-to-body="false" width="90%"
      :visible.sync="dialogVisible">
      <edit :id="id" @submitEnd="submitEnd" v-if="dialogVisible"></edit>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
import SpecList from "@/views/goodsProduct/spec";
import StoreList from "@/views/goodsProduct/store";
import Edit from "./edit.vue";

export default {
  components: {
    SpecList,
    StoreList,
    Edit,
  },
  mixins: [commonPage],
  props: {},
  data() {
    return {
      apiName: "goodsProduct",
      title: this.langt("商品"),
      levelTree: [],
      level_ids: [],
      levelProps: {
        label: "name",
        value: "id",
        children: "children",
      },
      objForm: {
        id: "", //规格是规格id
        store: "", //库存
        number: 0, //变动数量
        change: 3, //变化类型 3入库 4出库
      },
      queryForm: {
        classify: "",//模块分类
        status: "",
        type_id: "", //分类id
        keyword: "",
      },
      product_id: "", //
      specDialogVisible: false,
      storeDialogVisible: false,
      is_store_show: false,
    };
  },
  methods: {
    okStore() {
      let store_data = {
        type: "store",
        id: this.objForm.id,
        product_id: this.objForm.product_id,
        number: this.objForm.number,
        change: this.objForm.change,
      };
      this.$api[this.apiName].save(store_data).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: this.langt("操作成功")
          });
          this.onLoad();
        }
      });
      this.is_store_show = false;
    },
    storeGoodsProduct(row, product_name) {
      // this.objForm = row;
      this.objForm.id = row.id;
      this.objForm.product_name = product_name;
      this.objForm.product_id = row.product_id;
      this.objForm.spec = row.spec;
      this.objForm.store = row.store;
      this.objForm.change = 3;
      this.is_store_show = true;
    },
    specDialogOpen(id) {
      //查看更多规格
      this.specDialogVisible = true;
      this.product_id = id;
    },
    storeDialogOpen(id) {
      //查看库存日志
      this.storeDialogVisible = true;
      this.product_id = id;
    },
    handleLevelSelect() {
      // console.log(this.level_ids);
      this.queryForm.type_id = this.level_ids[this.level_ids.length - 1];
      this.onLoad();
    },
    dialogListOpen(id) {
      this.$router.push({ name: "goodsSpecIndex", params: { id: id } });
    },
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
          this.levelTree = response.data.levelTree;
          // this.$message({
          //   type: "error",
          //   message: this.langt("全部"),
          // });
        }
      });
    },
    handleSelectionChange(val) {
      val.map((item) => {
        this.idArray.push(item.id);
      });
    },
    handleStatusIds() {
      if (this.idArray == "") {
        this.$message({
          type: "error",
          message: this.langt("请选择要操作的数据"),
        });
      } else {
        this.$confirm(this.langt("确认关闭吗"), this.langt("提示"), {
          confirmButtonText: this.langt("确定"),
          cancelButtonText: this.langt("取消"),
          type: "warning",
        })
          .then(() => {
            this.$api[this.apiName].status(this.idArray, 2).then((response) => {
              if (response.code == 1) {
                this.$message({
                  type: "success",
                  message: this.langt("关闭成功"),
                });
                this.onLoad();
              }
            });
          })
          .catch(() => {
          });
      }
    },
    substrlen(str, len = 100) {
      if (str.length > len) {
        return str.substr(0, len) + '...';
      } else {
        return str;
      }
    }
  },
  mounted() {
    this.getLoadList();
  },
};
</script>
<style>
.goods-sales .goods-sales-item {
  /* display: inline-block; */
  margin-left: 5px;
}

.goods-item-list>view {
  display: inline-block;
}

.price-label {
  display: inline-block;
  width: 90px;
}

.price-price {
  display: inline-block;
}
</style>
