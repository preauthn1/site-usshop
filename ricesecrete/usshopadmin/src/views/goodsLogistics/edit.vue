<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{ $t('编辑') }}</span>
          <span v-else>{{ $t('添加') }}</span>{{ $t('物流公司') }}
        </b>
        <div class="u-right u-5mt">
          <el-button
            type="success"
            size="small"
            icon="el-icon-document-checked"
            @click="submitForm('objForm')"
            >{{ $t('提交保存') }}</el-button
          >
          <el-button size="small" icon="el-icon-back" @click="$router.back()"
            >{{ $t('返回') }}</el-button
          >
        </div>
      </div>
      <el-form
        :model="objForm"
        :rules="rules"
        ref="objForm"
        label-width="150px"
        class="u-form"
      >
        <el-form-item :label="$t('名称')" prop="name">
          <el-input
            v-model="objForm.name"
            :placeholder="$t('请输入名称')"
            maxlength="30"
            show-word-limit
          ></el-input>
        </el-form-item>
        <el-form-item :label="$t('代码')" prop="code">
          <el-input
            v-model="objForm.code"
            :placeholder="$t('请输入代码')"
            maxlength="30"
            show-word-limit
          ></el-input>
        </el-form-item>
        <el-form-item :label="$t('排序')">
          <el-input-number
            v-model="objForm.sort"
            :min="0"
            :label="$t('数值越大权重越高')"
          ></el-input-number>
        </el-form-item>
        <el-form-item :label="$t('是否启用')">
          <el-switch
            v-model="objForm.status"
            active-color="#13ce66"
            :active-value="1"
            inactive-color="#666"
            :inactive-value="2"
          ></el-switch>
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
      apiName: "goodsLogistics",
      backRoute: "goodsLogisticsIndex",
      objForm: {
        id: "",
        name: "",
        code: "",
        sort: 0,
        status: 1,
      },
      rules: {
        name: [
          {
            required: true,
            message: this.langt('请输入标签名称'),
            trigger: "blur",
          },
          {
            max: 30,
            message: this.langt('最大长度30个字符'),
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {},
};
</script>
