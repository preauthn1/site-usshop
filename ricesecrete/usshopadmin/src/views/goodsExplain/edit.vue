<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>说明文档
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="类型" prop="type">
          <el-select v-model="objForm.type" placeholder="请选择类型">
            <el-option v-for="(item,key) in typeList" :key="key" :label="item" :value="key"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="标题" prop="title">
          <el-input v-model="objForm.title" placeholder="请输入标题" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="详情" prop="content">
          <WangEditorInput ref="wangEditorInput" :content="objForm.content" @htmlChange="contentDetail" />
        </el-form-item>
        <el-form-item label="是否启用">
          <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
            :inactive-value="2"></el-switch>
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
    WangEditorInput
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "goodsExplain",
      backRoute: "goodsExplainIndex",
      typeList: [],//类型列表
      // 'id' => $request->param('id'),
      //       'title' => $request->param('title'),
      //       'content' => $request->param('content'),
      //       'type' => $request->param('type'),
      //       'status' => $request->param('status'),
      objForm: {
        id: "",
        title: "",
        content: "",
        type: "",
        status: 1,
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入标签名称",
            trigger: "blur",
          },
          {
            max: 30,
            message: "最大长度30个字符",
            trigger: "blur",
          },
        ]
      },
    };
  },
  methods: {
    contentDetail(html) {
      //详情回调
      this.objForm.content = html;
    },
    getLoadList() {
      let params = {
        init: 'init_params'
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          this.typeList = response.data.typeList;

        }
      });
    },
  },
  created() {
    this.getLoadList();
  },
};
</script>
