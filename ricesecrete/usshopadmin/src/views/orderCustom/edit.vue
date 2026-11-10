<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>定制旅游订单
        </b>
        <div class="u-right u-5mt">
          <el-button
            type="success"
            size="small"
            icon="el-icon-document-checked"
            @click="submitForm('objForm')"
            >提交保存</el-button
          >
          <el-button size="small" icon="el-icon-back" @click="$router.back()"
            >返回</el-button
          >
        </div>
      </div>
      <el-form
        :model="objForm"
        :rules="rules"
        ref="objForm"
        label-width="100px"
        class="u-form"
      >
        <el-row>
          <el-col :span="12">
            <el-form-item label="定制用户">
              <UserSearch
                :user_id="objForm.user_id"
                :field="'id'"
                @changeUserId="changeUserId"
                :multiple="false"
              ></UserSearch>
            </el-form-item>
            <el-form-item label="姓名" prop="name">
              <el-input v-model="objForm.name"></el-input>
            </el-form-item>
            <el-form-item label="手机号" prop="phone">
              <el-input v-model="objForm.phone"></el-input>
            </el-form-item>
            <el-form-item label="定制类型">
              <el-radio-group v-model="objForm.type">
                <el-radio :label="1">企业定制</el-radio>
                <el-radio :label="2">私人定制</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item
              label="企业名称"
              prop="enterprise"
              v-if="objForm.type == 1"
            >
              <el-input v-model="objForm.enterprise"></el-input>
            </el-form-item>
            <el-form-item label="出发地址" prop="address">
              <el-input v-model="objForm.address"></el-input>
            </el-form-item>
            <el-form-item label="目的地" prop="destination">
              <el-input v-model="objForm.destination"></el-input>
            </el-form-item>
            <el-form-item label="出发时间" prop="start_day">
              <el-date-picker
                v-model="objForm.start_day"
                type="date"
                placeholder="选择日期"
                value-format="yyyy-MM-dd"
              >
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="人数" prop="number">
              <el-input v-model="objForm.number"></el-input>
            </el-form-item>
            <el-form-item label="人均预算" prop="budget">
              <el-input v-model="objForm.budget"></el-input>
            </el-form-item>
            <el-form-item label="状态">
              <el-radio-group v-model="objForm.status" :disabled="true">
                <el-radio :label="1">申请中</el-radio>
                <el-radio :label="2">接待中</el-radio>
                <el-radio :label="3">接待成功</el-radio>
                <el-radio :label="4">拒绝接待</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="备注信息" prop="remark">
              <el-input v-model="objForm.remark"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import UserSearch from "@/components/forms/usersearch.vue";
export default {
  components: {
    UserSearch,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "orderCustom",
      backRoute: "orderCustomIndex",
      objForm: {
        id: "",
        user_id: " ", //定制用户
        name: "", //姓名
        phone: "", //手机号
        enterprise: "", //企业名称
        type: 1, //定制类型 1企业定制 2私人定制
        address: "", //出发地址
        destination: "", //目的地
        start_day: "", //出发时间
        number: "", //人数
        budget: "", //人均预算
        status: 1, //  状态：状态：0取消 1申请中  2接待中 3接待成功 4拒绝接待
        remark: "", //备注信息
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输姓名",
            trigger: "blur",
          },
          {
            max: 50,
            message: "最大长度50个字符",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    changeUserId(user_id) {
      // console.log(user_id);
      this.objForm.user_id = user_id;
    },
    afterGetInfo() {},
  },
};
</script>
