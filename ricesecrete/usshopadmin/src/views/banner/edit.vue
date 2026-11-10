<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('Banner')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('Banner标题')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入Banner标题')" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('跳转类型')">
          <el-select v-model="objForm.jump_type" class="u-w-block">
            <el-option :label="$t('无跳转')" :value="0"></el-option>
            <el-option :label="$t('商品页面')" :value="1"></el-option>
            <el-option :label="$t('外部链接')" :value="2"></el-option>
            <el-option :label="$t('富文本详情')" :value="3"></el-option>
            <el-option :label="$t('内部链接')" :value="4"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item :label="$t('跳转链接')" v-show="objForm.jump_type == 2 || objForm.jump_type == 4">
          <el-input v-model="objForm.jump_link" :placeholder="$t('请输入跳转链接')" maxlength="255" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('商品类型')" v-if="objForm.jump_type == 1">
          <el-select v-model="objForm.goods_type" class="u-w-block">
            <el-option :label="$t('正常商品')" :value="0"></el-option>
            <el-option :label="$t('旅游套餐')" :value="1"></el-option>
            <el-option :label="$t('积分商品')" :value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('商品id')" v-show="objForm.jump_type == 1">
          <el-input v-model="objForm.goods_id" :placeholder="$t('商品id')" maxlength="255" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('时间区间')">
          <el-col :span="11">
            <el-date-picker type="datetime" value-format="timestamp" :placeholder="$t('生效时间')" v-model="objForm.start_time"
              style="width: 100%"></el-date-picker>
          </el-col>
          <el-col class="line u-text-center" :span="2">-</el-col>
          <el-col :span="11">
            <el-date-picker type="datetime" value-format="timestamp" :placeholder="$t('失效时间')" v-model="objForm.end_time"
              style="width: 100%"></el-date-picker>
          </el-col>
        </el-form-item>
        <el-form-item :label="$t('上传图片')" prop="src">
          <upload file-folder="banner" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png" :tips="$t('只能上传jpg/png文件，且不超过4MB')" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item :label="$t('banner详情')" prop="banner_detail" v-show="objForm.jump_type == 3">
          <WangEditorInput ref="wangEditorInput" :content="objForm.detail" @htmlChange="bannerDetail" />
        </el-form-item>
        <el-form-item :label="$t('排序')">
          <el-input-number v-model="objForm.sort" :min="0" :label="$t('数值越大权重越高')"></el-input-number>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import Upload from "@/components/upload/upload";
import WangEditorInput from '@/components/wangeditor/index.vue'
export default {
  components: {
    Upload,
    WangEditorInput
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "banner",
      backRoute: "bannerIndex",
      fileList: [],
      fileList2: [], //详情
      objForm: {
        id: "",
        title: "",
        src: "",
        detail: "",
        jump_type: 0,
        jump_link: "",
        goods_id: 0,
        end_time: "",
        start_time: "",
        status: 1,
        sort: 0,
      },
      rules: {
        title: [
          {
            required: true,
            message: this.$t('请输入Banner标题'),
            trigger: "blur",
          },
          {
            max: 100,
            message: this.$t('最大长度100个字符'),
            trigger: "blur",
          },
        ],
        src: [
          {
            required: true,
            message: this.$t('请上传Banner图片'),
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    bannerDetail(html) {
      //详情回调
      this.objForm.detail = html;
      console.log(html)
    },
    afterGetInfo() {
      if (this.objForm.src) {
        this.fileList = [
          {
            name: "banner_src",
            url: this.objForm.src,
          },
        ];
      }
    },
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.src = files[0]["url"];
    },
  },
};
</script>