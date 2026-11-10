<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{$t('Banner列表')}}</b>
        </span>
        <router-link :to="{ name: 'bannerEdit' }" v-if="hasPerm('bannerEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" class="u-right u-5mt" round>{{$t('添加')}}</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('状态')">
          <el-select v-model="queryForm.status" clearable @change="handleChange">
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('启用')" value="1"></el-option>
            <el-option :label="$t('禁用')" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('跳转类型')">
          <el-select v-model="queryForm.jump_type" clearable>
            <el-option :label="$t('全部')" value></el-option>
            <el-option :label="$t('无跳转')" value="0"></el-option>
            <el-option :label="$t('商品页面')" value="1"></el-option>
            <el-option :label="$t('外部链接')" value="2"></el-option>
            <el-option :label="$t('富文本详情')" value="3"></el-option>
            <el-option :label="$t('内部链接')" value="4"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入Banner标题查询')" clearable></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{$t('查询')}}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('图片')">
          <template slot-scope="scope">
            <el-image style="'width: 150px; height: 80px" :src="scope.row.src" :data-src="scope.row.src" fit="cover" lazy></el-image>
          </template>
        </el-table-column>
        <el-table-column :label="$t('标题')" prop="title"></el-table-column>
        <el-table-column :label="$t('跳转类型')">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.jump_type == 1">{{$t('商品页面')}}</el-tag>
            <el-tag type="success" v-else-if="scope.row.jump_type == 2">{{$t('外部链接')}}</el-tag>
            <el-tag type="success" v-else-if="scope.row.jump_type == 3">{{$t('富文本详情')}}</el-tag>
            <el-tag type="success" v-else-if="scope.row.jump_type == 4">{{$t('内部链接')}}</el-tag>
            <el-tag type="info" v-else>{{$t('无跳转')}}</el-tag>
          </template>
        </el-table-column>

        <el-table-column :label="$t('跳转参数')">
          <template slot-scope="scope">
            <el-tag v-if="scope.row.jump_type == 1">{{
          scope.row.goods_id
        }}</el-tag>
            <el-tag type="success" v-else-if="scope.row.jump_type == 2">{{
          scope.row.jump_link
        }}</el-tag>
            <el-tag type="info" v-else>{{$t('无跳转')}}</el-tag>
          </template>
        </el-table-column>

        <el-table-column :label="$t('状态')">
          <template slot-scope="scope">
            <el-switch v-model="scope.row.status" :active-value="1" :inactive-value="2" active-color="#13ce66"
              @change="handleStatus(scope.row.id, scope.row.status)"></el-switch>
          </template>
        </el-table-column>
        <el-table-column :label="$t('排序')" prop="sort"></el-table-column>
        <el-table-column :label="$t('生效时间')" prop="start_time" sortable></el-table-column>
        <el-table-column :label="$t('失效时间')" prop="end_time" sortable></el-table-column>
        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'bannerEdit')"
              v-if="hasPerm('bannerEdit')">{{$t('编辑')}}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('bannerDelBtn')">{{$t('移除')}}</el-button>
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
      apiName: "banner",
      queryForm: {
        status: "",
        jump_type: "",
        keyword: "",
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