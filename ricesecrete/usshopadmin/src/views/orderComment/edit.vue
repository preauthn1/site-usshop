<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span>查看评论</span>
        </b>
        <div class="u-right u-5mt">
          <el-button size="small" icon="el-icon-back" @click="$router.back()"
            >返回</el-button
          >
        </div>
      </div>
      <el-form
        :model="objForm"
        :rules="rules"
        ref="objForm"
        label-width="150px"
        class="u-form"
      >
        <el-row>
          <el-col :span="12">
            <el-form-item label="评论人">
              <div>{{ objForm.user_name }}</div>
              <el-image
                style="'width: 100px; height: 80px"
                :src="objForm.user_head"
                :data-src="objForm.user_head"
                fit="cover"
                lazy
              ></el-image>
            </el-form-item>
            <el-form-item label="产品信息">
              <el-card class="box-card" style="width: 300px">
                <div slot="header" class="clearfix">
                  <span>{{ objForm.product_name }}</span>
                </div>
                <div
                  style="
                    width: 100%;
                    display: inline-block;
                    vertical-align: top;
                  "
                >
                  <el-image :src="objForm.product_img" fit="cover"></el-image>
                </div>
              </el-card>
            </el-form-item>
            <el-form-item label="评分">
              <div class="rate-item">
                <div class="rate-name">综合</div>
                <div class="rate-value">
                  <el-rate
                    v-model="objForm.all_score"
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
                  {{ objForm.type == 1 ? "导游" : "描述" }}
                </div>
                <div class="rate-value">
                  <el-rate
                    v-model="objForm.tour_guide_score"
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
                  {{ objForm.type == 1 ? "服务" : "包装" }}
                </div>
                <div class="rate-value">
                  <el-rate
                    v-model="objForm.service_score"
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
                  {{ objForm.type == 1 ? "体验" : "使用" }}
                </div>
                <div class="rate-value">
                  <el-rate
                    v-model="objForm.experience_score"
                    disabled
                    show-score
                    text-color="#ff9900"
                    score-template="{value}"
                  >
                  </el-rate>
                </div>
              </div>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="时间">
              <div>{{ objForm.update_time }}</div>
            </el-form-item>
            <el-form-item label="评论内容">
              <div>{{ objForm.content }}</div>
            </el-form-item>
            <el-form-item label="评论图片">
              <div>
                <el-image
                  v-for="(item, index) in objForm.images"
                  :key="index"
                  style="'width: 180px; height: 180px"
                  :src="item"
                  :preview-src-list="objForm.images"
                  fit="cover"
                  lazy
                ></el-image>
              </div>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
export default {
  mixins: [commonEdit],
  data() {
    return {
      apiName: "orderComment",
      backRoute: "orderCommentIndex",
      typeList: [],
      objForm: {
        id: "",
        type: 1,
        min_day: "",
        max_day: "",
        ratio: "",
        status: 1,
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入名称",
            trigger: "blur",
          },
          {
            max: 50,
            message: "最大长度50个字符",
            trigger: "blur",
          },
        ],
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
  },
  created() {
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
