<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{ $t('编辑') }}</span>
          <span v-else>{{ $t('添加') }}</span>{{ $t('购买记录') }}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('购买人')">
          <UserSearch :user_id="objForm.user_id" :field="'id'" @changeUserId="changeUserId" :multiple="false"></UserSearch>
        </el-form-item>
        <el-form-item :label="$t('商品id')">
          <el-input v-model="objForm.product_id" :placeholder="$t('商品id')" maxlength="50"></el-input>
        </el-form-item>
        <!-- <el-form-item :label="$t('套餐规格')">
          <TourismSpecSearch :spec_id="objForm.spec_id" :product_id="objForm.product_id" @changeSpecId="changeSpecId">
          </TourismSpecSearch>
        </el-form-item>
        <el-form-item :label="$t('数量')">
          <el-input-number v-model="objForm.num" :placeholder="$t('数量')" maxlength="50"></el-input-number>
        </el-form-item>
        <el-form-item :label="$t('费用')">
          <el-input-number v-model="objForm.price" :placeholder="$t('费用')" maxlength="50"></el-input-number>
        </el-form-item> -->
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import UserSearch from '@/components/forms/usersearch.vue'

export default {
  components: {
    UserSearch,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "goodsBuy",
      backRoute: "goodsBuyIndex",
      objForm: {
        id: "",
        product_id: "",
        // spec_id: "",
        // num: 1,
        // price: 0,
      }
    };
  },
  methods: {
    changeUserId(user_id) {
      // console.log(user_id);
      this.objForm.user_id = user_id
    },
    changeTourismId(val) {
      console.log(val);
      this.objForm.product_id = parseInt(val);
    }
  },
};
</script>