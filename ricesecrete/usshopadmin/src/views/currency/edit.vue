<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('货币')}}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('货币名称')" prop="name">
          <el-input v-model="objForm.name" :placeholder="$t('请输入货币名称')" maxlength="255" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('货币符号')" prop="symbol">
          <el-input v-model="objForm.symbol" :placeholder="$t('请输入货币单位')" maxlength="20" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('货币代码')" prop="code">
          <el-input v-model="objForm.code" :placeholder="$t('请输入货币代码')" maxlength="20" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('汇率')" prop="exchange_rate">
          <el-input v-model="objForm.exchange_rate" type="number" :placeholder="$t('请输入货币汇率')" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('是否默认')">
          <el-switch v-model="objForm.is_default" :active-value="1" :inactive-value="2"></el-switch>
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
      apiName: "currency",
      backRoute: "currencyIndex",
      fileList: [],
      fileList2: [], //详情
      flags,
      objForm: {
        id: "",
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
            message: this.langt("请输入货币名称"),
            trigger: "blur",
          },
          {
            max: 50,
            message: this.langt("最大长度50个字符"),
            trigger: "blur",
          },
        ],
        code: [
          {
            required: true,
            message: this.langt("请输入货币代码"),
            trigger: "blur",
          },
          {
            max: 10,
            message: this.langt("最大长度10个字符"),
            trigger: "blur",
          },
        ],
        symbol: [
          {
            required: true,
            message: this.langt("请输入货币单位"),
            trigger: "blur",
          },
          {
            max: 10,
            message: this.langt("最大长度10个字符"),
            trigger: "blur",
          },
        ],
        exchange_rate: [
          {
            required: true,
            message: this.langt("请输入货币汇率"),
            trigger: "blur",
          }
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
