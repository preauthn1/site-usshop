<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>热门搜索词条
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="词条名称" prop="keyword">
          <el-input v-model="objForm.keyword" placeholder="词条名称" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="排序">
          <el-input-number v-model="objForm.sort" :min="0" label="数值越大权重越高"></el-input-number>
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
export default {
  mixins: [commonEdit],
  data() {
    return {
      apiName: "userSearch",
      backRoute: "userSearchIndex",
      objForm: {
        id: "",
        keyword: "",
        sort: 0,
        status: 1
      }
    };
  },
  methods: {
   
  },
};
</script>
