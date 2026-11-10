<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column
          :label="$t('名称')"
          prop="product_name"
          width="320"
        ></el-table-column>
        <el-table-column :label="$t('规格')" prop="spec"></el-table-column>
        <el-table-column :label="$t('改变前库存')" prop="store"></el-table-column>
        <el-table-column :label="$t('变化数量')" prop="num"></el-table-column>
        <el-table-column :label="$t('变化类型')">
          <!-- //变化类型 1购买  2退回 3入库 4出库 -->
          <template slot-scope="scope">
            <el-tag v-if="scope.row.change == 1" type="success">{{$t('购买')}}</el-tag>
            <el-tag v-if="scope.row.change == 2" type="warning">{{$t('退回')}}</el-tag>
            <el-tag v-if="scope.row.change == 3" type="info">{{$t('入库')}}</el-tag>
            <el-tag v-if="scope.row.change == 4" type="danger">{{$t('出库')}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column :label="$t('购买人')">
          <template slot-scope="scope">
            <div v-if="scope.row.user.phone">
              <div>{{ scope.row.user.phone }}</div>
              <div>{{ scope.row.user.nick_name }}</div>
              <el-image
                style="'width: 100px; height: 100px'"
                :src="scope.row.user.head_img"
                :data-src="scope.row.user.head_img"
                fit="cover"
                lazy
              ></el-image>
            </div>
            <div v-else>{{$t('未购买')}}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('管理员')">
          <template slot-scope="scope">
            <div v-if="scope.row.admin.username">
              <div>{{ scope.row.admin.username }}</div>
              <div>{{ scope.row.admin.realname }}</div>
              <el-image
                style="'width: 100px; height: 100px'"
                :src="scope.row.admin.avatar"
                :data-src="scope.row.admin.avatar"
                fit="cover"
                lazy
              ></el-image>
            </div>
            <div v-else>{{$t('未操作')}}</div>
          </template>
        </el-table-column>
        <el-table-column :label="$t('更新日期')" prop="update_time"></el-table-column>
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
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
export default {
  name: "StoreList",
  props: {
    product_id: {
      type: String,
    },
  },
  mixins: [commonPage],
  data() {
    return {
      apiName: "goodsProduct",
      queryForm: {
        type: "store",
        product_id: "", //归属套餐id
      },
    };
  },
  methods: {},
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