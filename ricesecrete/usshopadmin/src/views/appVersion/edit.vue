<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>APP版本
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
                     @click="submitForm('objForm')">提交保存
          </el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="版本号" prop="title">
          <el-input v-model="objForm.version_name" placeholder="请输入版本号" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="版本号（数字）">
          <el-input-number v-model="objForm.version_code" :min="0" label="请输入版本号（数字）"></el-input-number>
        </el-form-item>

        <el-form-item label="升级公告" prop="content">
          <WangEditorInput ref="wangEditorInput" :content="objForm.bulletin" @htmlChange="bulletinDetail"/>
        </el-form-item>
        <el-form-item label="平台" prop="type">
          <el-select v-model="objForm.platform_type">
            <el-option label="Android" :value="1"></el-option>
            <el-option label="iOS" :value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="是否强制升级">
          <el-switch v-model="objForm.is_force_update" active-color="#13ce66" :active-value="1" inactive-color="#666"
                     :inactive-value="2"></el-switch>
        </el-form-item>
        <el-form-item label="是否是wgt包">
          <el-switch v-model="objForm.is_wgt" active-color="#13ce66" :active-value="1" inactive-color="#666"
                     :inactive-value="2"></el-switch>
        </el-form-item>

        <el-form-item label="上传安装包" prop="src">
          <upload file-folder="appVersion" list-type="" :show-file-list="false"
                  accept="" tips=""
                  @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item label="下载链接" prop="title">
          <el-input v-model="objForm.url" placeholder="请输入版本号" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="objForm.status" clearable>
            <el-option label="启用" value="1"></el-option>
            <el-option label="停用" value="0"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import Upload from "@/components/upload/upload";
import WangEditorInput from "@/components/wangeditor/index.vue";

export default {
  components: {
    WangEditorInput,
    Upload,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "appVersion",
      backRoute: "appVersionIndex",
      objForm: {
        id: "",
        version_code: "",
        version_name: "",
        bulletin: "",
        platform_type: "",
        is_force_update: "",
        is_wgt: "",
        url: "",
        status: 1,
      },
      rules: {
        version_name: [
          {
            required: true,
            message: "请输入版本号",
            trigger: "blur",
          },
        ],
        version_code: [
          {
            required: true,
            message: "请输入版本号（数字）",
            trigger: "blur",
          },
        ],
        bulletin: [
          {
            required: true,
            message: "请输入公告内容",
            trigger: "blur",
          },
        ],
        platform_type: [
          {
            required: true,
            message: "请选择平台",
            trigger: "blur",
          },
        ],
        url: [
          {
            required: true,
            message: "请上传安装包",
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
