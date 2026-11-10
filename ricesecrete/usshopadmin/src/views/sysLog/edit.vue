<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          {{ $t('查看操作日志') }}
        </b>
        <div class="u-right u-5mt">
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
        </div>
      </div>
      <el-form :model="objForm" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('名称')" prop="name">
          <el-input v-model="objForm.name" :placeholder="$t('')" maxlength="100" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('路径')" prop="url">
          <el-input v-model="objForm.url" :placeholder="$t('')" maxlength="100" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="$t('参数')">
          <el-input type="textarea" :rows="10" :placeholder="$t('')" v-model="objForm.param">
          </el-input>
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
      apiName: "sysLog",
      backRoute: "sysLogIndex",
      objForm: {
        id: "",
        name: "",
        url: "",
        param: ""
      }
    };
  },
  methods: {

  },
};
</script>
