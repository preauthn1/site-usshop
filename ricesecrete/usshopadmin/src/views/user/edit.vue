<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span> {{$t('用户')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('邮箱')" prop="email">
          <el-input v-model="objForm.email" :placeholder="$t('请输入邮箱')" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('密码')" prop="password">
          <el-input v-model="objForm.password" :placeholder="$t('请输入密码（为空不更改密码）')" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('昵称')" prop="nick_name">
          <el-input v-model="objForm.nick_name" :placeholder="$t('请输入昵称')" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('性别')">
          <el-radio v-model="objForm.gender" :label="1">{{$t('男')}}</el-radio>
          <el-radio v-model="objForm.gender" :label="2">{{$t('女')}}</el-radio>
        </el-form-item>
        <el-form-item :label="$t('状态')">
          <el-radio v-model="objForm.status" :label="1">{{$t('正常')}}</el-radio>
          <el-radio v-model="objForm.status" :label="2">{{$t('停用')}}</el-radio>
        </el-form-item>
        <el-form-item :label="$t('代理状态')">
          <el-radio v-model="objForm.is_agent" :label="1">{{$t('是')}}</el-radio>
          <el-radio v-model="objForm.is_agent" :label="2">{{$t('否')}}</el-radio>
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
    // Upload
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "user",
      backRoute: "userIndex",
      fileList: [],
      objForm: {
        id: "",
        phone: "",
        email: "",
        password: "",
        gender: 1,
        nick_name: "",
        head_img: "",
        status: 1,
        is_agent: 2,
      },
      rules: {
        email: [
          { required: true, message: '请输入邮箱', trigger: 'blur' },
          { type: 'email', message: '请输入正确的邮箱格式', trigger: 'blur' }
        ]
      },
    };
  },
  methods: {
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.head_img = files[0]["url"];
    },
    afterGetInfo() {
      if (this.objForm.head_img) {
        this.fileList = [
          {
            name: "head_img",
            url: this.objForm.head_img,
          },
        ];
      }
    }
  },
};
</script>
