<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>权益
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="权益名称" prop="name">
          <el-input v-model="objForm.name" placeholder="请输入权益名称" maxlength="30" show-word-limit></el-input>
        </el-form-item>

        <el-form-item label="权益图片" prop="src">
          <upload file-folder="rights" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png" tips="只能上传jpg/png文件，且不超过4MB" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item label="权益描述" prop="content">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="contentDetail"/>
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
    Upload,
    WangEditorInput
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "rights",
      backRoute: "rightsIndex",
      fileList: [],
      fileList2: [], //详情
      objForm: {
        id: "",
        name: "",
        src: "",
        content: "",
        status: 1,
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入权益名称",
            trigger: "blur",
          },
          {
            max: 10,
            message: "最大长度10个字符",
            trigger: "blur",
          },
        ],
        src: [
          {
            required: true,
            message: "请上传权益图片",
            trigger: "blur",
          },
        ],
        rights_id: [
          {
            required: true,
            message: "请输入权益标识",
            trigger: "blur",
          },
        ],
        money: [
          {
            required: true,
            message: "请输入消费值",
            trigger: "blur",
          },
        ],
        invite_num: [
          {
            required: true,
            message: "请输入有效下级人数",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    contentDetail(html) {
      //详情回调
      this.objForm.content = html;
    },
    afterGetInfo() {
      if (this.objForm.src) {
        this.fileList = [
          {
            name: "rights_src",
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
