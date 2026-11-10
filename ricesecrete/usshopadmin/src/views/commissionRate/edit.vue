<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('返佣比例')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
                     @click="submitForm('objForm')">{{$t('提交保存')}}
          </el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('代理级别')" prop="title">
          <el-input-number v-model="objForm.level" :min="0" :placeholder="$t('请输入代理级别')"></el-input-number>
        </el-form-item>
        <el-form-item :label="$t('返佣比例')">
          <el-input-number v-model="objForm.rate" :min="0" :placeholder="$t('请输入返佣比例')"></el-input-number>
        </el-form-item>
        <el-form-item :label="$t('状态')">
          <el-select v-model="objForm.status" clearable>
            <el-option :label="$t('启用')" :value="1"></el-option>
            <el-option :label="$t('停用')" :value="2"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
export default {
  components: {
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "commissionRate",
      backRoute: "commissionRateIndex",
      objForm: {
        id: "",
        level: "",
        rate: "",
        status: 1,
      },
      rules: {
        version_name: [
          {
            required: true,
            message: this.$t('请输入版本号'),
            trigger: "blur",
          },
        ],
        version_code: [
          {
            required: true,
            message: this.$t('请输入版本号（数字）'),
            trigger: "blur",
          },
        ],
        bulletin: [
          {
            required: true,
            message: this.$t('请输入公告内容'),
            trigger: "blur",
          },
        ],
        platform_type: [
          {
            required: true,
            message: this.$t('请选择平台'),
            trigger: "blur",
          },
        ],
        url: [
          {
            required: true,
            message: this.$t('请上传安装包'),
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    bulletinDetail(html) {
      //详情回调
      this.objForm.bulletin = html;
    },
    afterGetInfo() {

    },
    getUploadFiles(files) {
      // 取files的最后一个元素的url
      this.objForm.url = files[files.length - 1]["url"];
    },
  },
};
</script>