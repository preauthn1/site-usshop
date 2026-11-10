<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
        </span>
        <b class="u-f-16">{{$t('代理返佣比例')}}</b>
        <router-link :to="{ name: 'commissionRateEdit' }" v-if="hasPerm('commissionRateEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
        </router-link>
      </div>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="序号" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('代理级别')" prop="level"></el-table-column>
        <el-table-column :label="$t('返佣比例')" prop="rate">
          <template slot-scope="scope">
            {{scope.row.rate}} %
          </template>
        </el-table-column>
        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
                       @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'commissionRateEdit')"
                       v-if="hasPerm('commissionRateEdit')">{{$t('编辑')}}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
                       v-if="hasPerm('commissionRateDelBtn')">{{$t('删除')}}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
                       :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
                       layout="total, sizes, prev, pager, next, jumper" :total="total"></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";

export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: "commissionRate",
      queryForm: {
        status: "",
        type: "",
        // keyword: "",
      },
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
  },
};
</script>