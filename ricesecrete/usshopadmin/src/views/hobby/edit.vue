<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>兴趣
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="类型" prop="type">
          <el-select v-model="objForm.type">
            <el-option :label="item" :value="Number(index)" v-for="(item, index) in hobbyType" :key="index"></el-option>
          </el-select>
        </el-form-item>


        <el-form-item label="兴趣标题" prop="title">
          <el-input v-model="objForm.title" placeholder="请输入兴趣标题" maxlength="30" show-word-limit></el-input>
        </el-form-item>

        <!-- <el-form-item label="上传图片" prop="src">
          <upload file-folder="hobby" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png" tips="只能上传jpg/png文件，且不超过4MB" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item> -->
        <el-form-item label="排序">
          <el-input-number v-model="objForm.sort" :min="0" label="数值越大权重越高"></el-input-number>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
// import Upload from "@/components/upload/upload";
export default {
  components: {
    // Upload,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "hobby",
      backRoute: "hobbyIndex",
      fileList: [],
      fileList2: [], //详情
      objForm: {
        id: "",
        title: "",
        type: 1,
        src: "",
        status: 1,
        sort: 0,
      },
      hobbyType: [],
      rules: {
        title: [
          {
            required: true,
            message: "请输入兴趣标题",
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
            message: "请上传兴趣图片",
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
          this.hobbyType = response.data.hobbyType;
        }
      });
    },
    afterGetInfo() {
      if (this.objForm.src) {
        this.fileList = [
          {
            name: "hobby_src",
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
  mounted() {
    this.getLoadList();
  }
};
</script>
