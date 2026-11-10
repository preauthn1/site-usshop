<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{ $t('编辑') }}</span>
          <span v-else>{{ $t('添加') }}</span>{{ $t('公告') }}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.go(-1)">{{ $t('返回') }}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('标题')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入标题')" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('状态')">
          <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
             :inactive-value="2"></el-switch>
        </el-form-item>
        <el-form-item :label="$t('是否弹窗')">
          <el-switch v-model="objForm.is_popup" active-color="#13ce66" :active-value="1" inactive-color="#666"
             :inactive-value="2"></el-switch>
        </el-form-item>
        <el-form-item :label="$t('详情')" prop="bulletin_detail">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="bulletinDetail" />
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import WangEditorInput from '@/components/wangeditor/index.vue'
export default {
  components: {
    WangEditorInput,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "bulletin",
      backRoute: "bulletinIndex",
      loading: false,
      objForm: {
        id: "",
        title: "",
        content: "",
        is_popup: ""
      },
      rules: {
        title: [
          {
            required: true,
            message: this.$t("请输入标题"),
            trigger: "blur",
          },
          {
            max: 50,
            message: this.$t("最大长度100个字符"),
            trigger: "blur",
          },
        ],
        content: {
          required: true,
          message: this.$t("请输入内容"),
          trigger: "blur",
        }
      },
    };
  },
  methods: {
    changeUserId(user_id) {
      // console.log(user_id);
      this.objForm.user_id = user_id
    },
    bulletinDetail(html) {
      //详情回调
      this.objForm.content = html;

      // console.log(this.objForm)
    },
    afterGetInfo() {

    }
  },
};
</script>
