<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column
          :label="$t('序号')"
          prop="row_num"
          width="100"
        ></el-table-column>
        <el-table-column :label="$t('名称')" prop="name" width="120"></el-table-column>
        <el-table-column :label="$t('图片')">
          <template slot-scope="scope">
            <el-image
              style="'width: 100px; height: 100px'"
              :src="scope.row.img"
              :data-src="scope.row.img"
              fit="fit"
              lazy
            ></el-image>
          </template>
        </el-table-column>
        <el-table-column :label="$t('库存')" prop="store"></el-table-column>
        <el-table-column :label="$t('销量')" prop="buy"></el-table-column>
        <el-table-column :label="$t('进价')" prop="cost_price"></el-table-column>
        <el-table-column :label="$t('售价')" prop="price"></el-table-column>
        <el-table-column :label="$t('限购数量')" prop="limit"></el-table-column>
        <el-table-column :label="$t('更新日期')" prop="update_time"></el-table-column>
        <el-table-column :label="$t('操作')" width="150" fixed="right">
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="text"
              @click.native="storeGoodsProduct(scope.row)"
              v-if="hasPerm('goodsProductEdit')"
              >{{$t('库存管理')}}</el-button
            >
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination
          background
          @size-change="handleSizeChange"
          @current-change="handlePageChange"
          :current-page="currentPage"
          :page-sizes="[10, 20, 30, 40, 50]"
          :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper"
          :total="total"
        ></el-pagination>
      </div>
    </el-card>
    <el-dialog
      :title="$t('库存管理')"
      :visible.sync="is_store_show"
      width="35%"
      append-to-body
    >
      <el-form
        :model="objForm"
        ref="objForm"
        label-width="100px"
        class="u-form"
        style="max-width: 90%"
      >
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
          <el-radio v-model="objForm.change" :label="3">{{$t('入库')}}</el-radio>
          <el-radio v-model="objForm.change" :label="4">{{$t('出库')}}</el-radio>
        </el-form-item>
        <el-form-item :label="$t('库存变动数量')">
          <el-input
            type="number"
            min="0"
            max="1000000"
            v-model="objForm.number"
            :placeholder="$t('库存变动数量')"
          ></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="is_store_show = false">{{$t('取 消')}}</el-button>
        <el-button type="primary" @click="okStore">{{$t('确 定')}}</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
export default {
  name: "SpecList",
  props: {
    product_id: {
      type: String,
    },
  },
  mixins: [commonPage],
  data() {
    return {
      apiName: "goodsProduct",
      is_store_show: false, //库存管理弹窗
      objForm: {
        id: "", //规格是规格id
        store: "", //库存
        number: 0, //变动数量
        change: 3, //变化类型 3入库 4出库
      },
      queryForm: {
        type: "spec",
        status: "",
        product_id: "", //归属套餐id
        keyword: "",
      },
      typeList: "",
      idArray: [], // 批量关闭
    };
  },
  methods: {
    okStore() {
      let store_data = {
        type: "store",
        id: this.objForm.id,
        product_id:this.objForm.product_id,
        number: this.objForm.number,
        change: this.objForm.change,
      };
      this.$api[this.apiName].save(store_data).then((response) => {
        if (response.code == 1) {
          this.$message({
            type: "success",
            message: this.$t("操作成功"),
          });
          this.onLoad();
        }
      });
      this.is_store_show = false;
    },
    storeGoodsProduct(row) {
      // this.objForm = row;
      this.objForm.id = row.id;
      this.objForm.product_name = row.product_name;
      this.objForm.product_id = row.product_id;
      this.objForm.spec = row.spec;
      this.objForm.store = row.store;
      this.objForm.change = 3;
      this.is_store_show = true;
    },
  },
  watch: {
    product_id: {
      handler(newVal, oldVal) {
        if (newVal != oldVal) {
          this.queryForm.product_id = newVal;
          this.onLoad();
        }
      },
      immediate: true,
    },
  },
  created() {
    // console.log(this.product_id);
    if (this.product_id) {
      this.queryForm.product_id = this.product_id;
    }
  },
};
</script>
<style>
.goods-sales .goods-sales-item {
  /* display: inline-block; */
  margin-left: 5px;
}

.goods-item-list > view {
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