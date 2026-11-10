<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">评论列表</b>
        </span>
      </div>

      <el-form
        :inline="true"
        :model="queryForm"
        size="small"
        class="u-text-center"
      >
        <el-form-item label="类型">
          <el-select v-model="queryForm.type" clearable @change="handleChange">
            <el-option label="全部" value=""></el-option>
            <el-option
              :label="item"
              :value="index"
              v-for="(item, index) in typeList"
              :key="index"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="状态">
          <el-select
            v-model="queryForm.status"
            clearable
            @change="handleChange"
          >
            <el-option label="全部" value></el-option>
            <el-option label="启用" value="1"></el-option>
            <el-option label="禁用" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="关键词">
          <el-input
            v-model="queryForm.keyword"
            placeholder="输入标题查询"
            clearable
          ></el-input>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            icon="el-icon-search"
            @click="getPageData(true)"
            >查询</el-button
          >
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column label="ID" prop="id" width="80"></el-table-column>
        <el-table-column label="类型" width="120">
          <!-- //类型 1旅游套餐 2正常商品 3积分商品 -->
          <template slot-scope="scope">
            <el-tag v-if="scope.row.type == 1">旅游套餐</el-tag>
            <el-tag v-if="scope.row.type == 2">正常商品</el-tag>
            <el-tag v-if="scope.row.type == 3">积分商品</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="产品信息" width="180">
          <template slot-scope="scope">
            <div class="title">{{ scope.row.product_name }}</div>
            <div class="img">
              <el-image
                style="max-width: 100px"
                :src="scope.row.product_img"
                :data-src="scope.row.product_img"
                fit="cover"
                lazy
              ></el-image>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="评论人" width="130">
          <template slot-scope="scope">
            <div>{{ scope.row.user_name }}</div>
            <el-image
              style="'width: 100px; height: 80px"
              :src="scope.row.user_head"
              :data-src="scope.row.user_head"
              fit="cover"
              lazy
            ></el-image>
          </template>
        </el-table-column>
        <el-table-column label="评分" width="280">
          <template slot-scope="scope">
            <div class="rate-item">
              <div class="rate-name">综合</div>
              <div class="rate-value">
                <el-rate
                  v-model="scope.row.all_score"
                  disabled
                  show-score
                  text-color="#ff9900"
                  score-template="{value}"
                >
                </el-rate>
              </div>
            </div>
            <div class="rate-item">
              <div class="rate-name">
                {{ scope.row.type == 1 ? "导游" : "描述" }}
              </div>
              <div class="rate-value">
                <el-rate
                  v-model="scope.row.tour_guide_score"
                  disabled
                  show-score
                  text-color="#ff9900"
                  score-template="{value}"
                >
                </el-rate>
              </div>
            </div>
            <div class="rate-item">
              <div class="rate-name">
                {{ scope.row.type == 1 ? "服务" : "包装" }}
              </div>
              <div class="rate-value">
                <el-rate
                  v-model="scope.row.service_score"
                  disabled
                  show-score
                  text-color="#ff9900"
                  score-template="{value}"
                >
                </el-rate>
              </div>
            </div>
            <div class="rate-item">
              <div class="rate-name">
                {{ scope.row.type == 1 ? "体验" : "使用" }}
              </div>
              <div class="rate-value">
                <el-rate
                  v-model="scope.row.experience_score"
                  disabled
                  show-score
                  text-color="#ff9900"
                  score-template="{value}"
                >
                </el-rate>
              </div>
            </div>
          </template>
        </el-table-column>

        <!-- <el-table-column label="内容" prop="content"></el-table-column> -->
        <!-- <el-table-column label="图片">
          <template slot-scope="scope">
            <el-image
              v-for="(item, index) in scope.row.images"
              :key="index"
              style="'width: 80px; height: 80px"
              :src="item"
              :preview-src-list="scope.row.images"
              fit="cover"
              lazy
            ></el-image>
          </template>
        </el-table-column> -->
        <el-table-column
          label="创建时间"
          prop="create_time"
          sortable
        ></el-table-column>
        <el-table-column label="操作" width="120">
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="text"
              @click="handleRoute(scope.row.id, 'orderCommentEdit')"
              v-if="hasPerm('orderCommentEdit')"
              >查看</el-button
            >
            <el-button
              size="mini"
              type="text"
              @click="handleDelete(scope.row.id)"
              v-if="hasPerm('orderCommentDelBtn')"
              >删除</el-button
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
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";

export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: "orderComment",
      typeList: [],
      queryForm: {
        type: "",
        status: "",
        keyword: "",
      },
    };
  },
  methods: {
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
          this.typeList = response.data.typeList;
        }
      });
    },
    handleChange() {
      this.getPageData(true);
    },
  },
  mounted() {
    this.getLoadList();
  },
};
</script>
<style>
.rate-item {
  display: block;
}
.rate-name {
  display: inline-block;
  width: 50px;
  text-align: right;
  margin-right: 10px;
}
.rate-value {
  display: inline-block;
  width: 140px;
}
</style>
