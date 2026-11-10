<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>退订规则
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="150px" class="u-form">
        <el-form-item label="类型">
          <el-select v-model="objForm.type">
            <el-option :label="item" :value="parseInt(index)" v-for="(item, index) in typeList" :key="index"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="最小天数">
          <el-input-number v-model="objForm.min_day" placeholder="距离集合前最小天数（包含）" maxlength="50"></el-input-number>
          <el-tag type="warning">距离集合前最小天数（包含） 0表示当天</el-tag>
        </el-form-item>
        <el-form-item label="最大天数">
          <el-input-number v-model="objForm.max_day" placeholder="距离集合前最大天数" maxlength="50"></el-input-number>
          <el-tag type="warning">距离集合前最大天数 0表示当天</el-tag>
        </el-form-item>
        <el-form-item label="金额比例（%）">
          <el-input-number v-model="objForm.ratio" placeholder="扣除或者补偿比例（%）" maxlength="50"></el-input-number>
          <el-tag type="warning">扣除或者补偿比例（%）</el-tag>
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
      apiName: "orderDamages",
      backRoute: "orderDamagesIndex",
      typeList: [],
      objForm: {
        id: '',
        type: 1,
        min_day: '',
        max_day: '',
        ratio: '',
        status: 1
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入名称",
            trigger: "blur",
          },
          {
            max: 50,
            message: "最大长度50个字符",
            trigger: "blur",
          },
        ]
      },
    };
  },
  methods: {
    getLoadList() {
      let params = {
        init: 'init_params'
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          // console.log(response.data);
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
