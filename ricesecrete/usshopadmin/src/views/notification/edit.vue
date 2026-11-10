<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('站内信')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('标题')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入标题')" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('发送对象')">
         <UserSearch :user_id="objForm.user_id" :field="'id'" @changeUserId="changeUserId" :multiple="true"></UserSearch>
        </el-form-item>
        <el-form-item :label="$t('详情')" prop="notification_detail">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="notificationDetail" />
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import WangEditorInput from '@/components/wangeditor/index.vue'
import UserSearch from '@/components/forms/usersearch.vue'
export default {
  components: {
    WangEditorInput,
    UserSearch
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "notification",
      backRoute: "notificationIndex",
      loading: false,
      user_list: [
        { id: 1, nickname: "用户1" },
        { id: 2, nickname: "用户2" },
      ], //用户列表
      objForm: {
        id: "",
        title: "",
        user_id: [],
        content: ""
      },
      rules: {
        title: [
          {
            required: true,
            message: this.langt("请输入标题"),
            trigger: "blur",
          },
          {
            max: 100,
            message: this.langt("最大长度100个字符"),
            trigger: "blur",
          },
        ]
      },
    };
  },
  methods: {
    changeUserId(user_id) {
      // console.log(user_id);
      this.objForm.user_id = user_id
    },
    notificationDetail(html) {
      //详情回调
      this.objForm.content = html;

      // console.log(this.objForm)
    },
    afterGetInfo() {

    }
  },
};
</script>
