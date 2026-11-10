<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>活动
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="活动标题" prop="title">
          <el-input v-model="objForm.title" placeholder="请输入活动标题" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-select v-model="objForm.type">
            <el-option label="通知类活动" :value="1"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="上传图片" prop="head_img">
          <upload file-folder="activity" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png" tips="只能上传jpg/png文件，且不超过4MB" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item label="摘要" prop="summary">
          <el-input
            v-model="objForm.summary"
            type="textarea"
            placeholder=""
            maxlength="255" show-word-limit>
          ></el-input>
        </el-form-item>
        <el-form-item label="内容详情" prop="content">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="contentDetail"/>
        </el-form-item>
        <el-form-item label="状态">
          <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
                     :inactive-value="2"></el-switch>
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
      apiName: "activity",
      backRoute: "activityIndex",
      fileList: [],
      fileList2: [], //详情
      objForm: {
        id: "",
        title: "",
        type: "",
        summary: "",
        content: "",
        head_img: "",
        status: 1,
        sort: 0,
      },
      rules: {
        title: [
          {
            required: true,
            message: "请输入活动标题",
            trigger: "blur",
          },
          {
            max: 10,
            message: "最大长度10个字符",
            trigger: "blur",
          },
        ],
        type: [
          {
            required: true,
            message: "请选择类型",
            trigger: "blur",
          },
        ],
        head_img: [
          {
            required: true,
            message: "请上传头图",
            trigger: "blur",
          },
        ],
        summary: [
          {
            required: true,
            message: "请输入摘要",
            trigger: "blur",
          },
        ],
        content: [
          {
            required: true,
            message: "请输入活动内容",
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
      if (this.objForm.head_img) {
        this.fileList = [
          {
            name: "activity_head_img",
            url: this.objForm.head_img,
          },
        ];
      }
    },
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.head_img = files[0]["url"];
    },
  },
};
</script>
