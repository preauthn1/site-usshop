<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{ $t('编辑') }}</span>
          <span v-else>{{ $t('添加') }}</span>{{ $t('语言') }}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('标题')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入语言标题')" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('名称')" prop="name">
          <el-input v-model="objForm.name" :placeholder="$t('请输入语言名称')" maxlength="30" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('代码')" prop="flag">
          <el-select v-model="objForm.code" :placeholder="$t('请选择代码')" filterable>
            <el-option v-for="flag in flags" :key="flag.code" :label="flag.name" :value="flag.code">
              <div class="flag-option">
                <img :src="flag.icon" class="flag-icon" />
                <span>{{ flag.name }}</span>
              </div>
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('是否默认')">
          <el-switch v-model="objForm.is_default"  :active-value="1" :inactive-value="2"></el-switch>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import flags from '@/utils/flags';
export default {
  mixins: [commonEdit],
  data() {
    return {
      apiName: "language",
      backRoute: "languageIndex",
      fileList: [],
      fileList2: [], //详情
      flags,
      objForm: {
        id: "",
        title:"",
        name: "",
        code: "",
        rtl: 1,
        status: 1,
        is_default: 2,
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入语言名称",
            trigger: "blur",
          },
          {
            max: 10,
            message: "最大长度10个字符",
            trigger: "blur",
          },
        ],
        code: [
          {
            required: true,
            message: "请输入语言代码",
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

  },
};
</script>
<style scoped>
.flag-option {
  display: flex;
  align-items: center;
}

.flag-icon {
  width: 20px;
  height: 15px;
  margin-right: 8px;
  object-fit: cover;
}
</style>
