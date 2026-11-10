<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('内容')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked" @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('类型')" prop="type">
          <el-select v-model="objForm.type">
            <el-option :label="item" :value="Number(index)" v-for="(item, index) in typeList" :key="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('语言')" prop="lang">
          <el-select v-model="objForm.lang">
            <el-option :label="item.title" :value="item.code" v-for="(item) in langList" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('标题')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入标题')" show-word-limit></el-input>
        </el-form-item>

        <el-form-item :label="$t('内容详情')" prop="content">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="contentDetail" />
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import WangEditorInput from "@/components/wangeditor/index.vue";

export default {
  components: {
    WangEditorInput
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "article",
      backRoute: "articleIndex",
      objForm: {
        id: "",
        type: "",
        lang:"",
        title: "",
        content: "",
        status: 1,
      },
      typeList: [],
      langList: [],
      rules: {
        title: [
          {
            required: true,
            message: this.$t('请输入标题'),
            trigger: "blur",
          },
        ],
        content: [
          {
            required: true,
            message: this.$t('请输入内容'),
            trigger: "blur",
          },
        ],
        type: [
          {
            required: true,
            message: this.$t('请选择类型'),
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
          this.langList = response.data.langList;
        }
      });
    },
    contentDetail(html) {
      //详情回调
      this.objForm.content = html;
    },
    afterGetInfo() {

    },
  },
  mounted() {
    this.getLoadList();
  }
};
</script>
