<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{ $t('编辑') }}</span>
          <span v-else>{{ $t('添加') }}</span>{{ $t('支付渠道') }}
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked" @click="submitForm('objForm')">{{ $t('提交保存') }}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{ $t('返回') }}</el-button>
        </div>
      </div>

      <el-tabs v-model="activeTab" type="card">
        <el-tab-pane :label="$t('基本信息')" name="basic">
          <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
            <el-form-item :label="$t('名称')" prop="name">
              <el-input v-model="objForm.name" :placeholder="$t('请输入名称')" maxlength="30" show-word-limit></el-input>
            </el-form-item>
            <el-form-item :label="$t('标识')" prop="ident">
              <el-input v-model="objForm.ident" :placeholder="$t('请输入标识')" maxlength="30" show-word-limit></el-input>
            </el-form-item>
            <el-form-item :label="$t('上传图片')" prop="src">
              <upload file-folder="banner" :file-arr="fileList" list-type="picture-card" :limit="1"
                accept="image/jpeg, image/png" :tips="$t('只能上传jpg/png文件，且不超过4MB')" @getUploadFiles="getUploadFiles"></upload>
            </el-form-item>
            <el-form-item :label="$t('最低提现金额')" prop="min_withdrawal_money">
              <el-input-number v-model="objForm.min_withdrawal_money" :min="0" :label="$t('请输入最低提现金额')"></el-input-number>
            </el-form-item>
            <el-form-item :label="$t('最高提现金额')" prop="max_withdrawal_money">
              <el-input-number v-model="objForm.max_withdrawal_money" :min="0" :label="$t('请输入最高提现金额')"></el-input-number>
            </el-form-item>
            <el-form-item :label="$t('支付状态')">
              <el-switch v-model="objForm.pay_status" active-color="#13ce66" :active-value="1" inactive-color="#666"
                :inactive-value="2"></el-switch>
            </el-form-item>
            <el-form-item :label="$t('支付类型')">
              <el-radio v-model="objForm.type" :label="1">线上支付</el-radio>
              <el-radio v-model="objForm.type" :label="2">线下支付</el-radio>
            </el-form-item>
            <el-form-item :label="$t('提现状态')">
              <el-switch v-model="objForm.withdrawal_status" active-color="#13ce66" :active-value="1"
                inactive-color="#666" :inactive-value="2"></el-switch>
            </el-form-item>
            <el-form-item :label="$t('是否启用')">
              <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
                :inactive-value="2"></el-switch>
            </el-form-item>
          </el-form>
        </el-tab-pane>

        <el-tab-pane :label="$t('配置')" name="config">
          <div class="config-container">
            <div class="config-header">
              <h3>{{ $t('支付渠道配置项') }}</h3>
              <el-button type="primary" size="small" @click="addConfigItem">{{ $t('添加配置项') }}</el-button>
            </div>

            <el-table :data="configItems" style="width: 100%; margin-top: 15px;">
              <el-table-column :label="$t('键名')" width="250">
                <template slot-scope="scope">
                  <el-input v-model="scope.row.key" :placeholder="$t('请输入键名')"></el-input>
                </template>
              </el-table-column>
              <el-table-column :label="$t('值')">
                <template slot-scope="scope">
                  <el-input v-model="scope.row.value" :placeholder="$t('请输入值')" type="textarea" :rows="2"></el-input>
                </template>
              </el-table-column>
              <el-table-column :label="$t('操作')" width="100">
                <template slot-scope="scope">
                  <upload file-folder="ewm" :file-arr="[]" list-type="" :limit="1" :index="scope.$index"
                  accept="image/jpeg,image/png" :tips="$t('')" @getUploadFiles="getUploadFile"></upload>
               
                  <el-button type="danger" size="mini" @click="removeConfigItem(scope.$index)">{{ $t('删除') }}</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import Upload from "@/components/upload/upload.vue";

export default {
  components: {
    Upload
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "paymentChannel",
      backRoute: "paymentChannelIndex",
      fileList: [],
      fileList2: [], //详情
      activeTab: 'basic',
      configItems: [],
      objForm: {
        id: "",
        title: "",
        src: "",
        status: 1,
        sort: 0,
        config: "", // 存储JSON格式的配置
        min_withdrawal_money: 0,
        max_withdrawal_money: 0,
        type:1,//1线上支付，2线下支付
      },
      rules: {
        title: [
          {
            required: true,
            message: this.$t('请输入支付渠道标题'),
            trigger: "blur",
          },
          {
            max: 10,
            message: this.$t('最大长度10个字符'),
            trigger: "blur",
          },
        ],
        src: [
          {
            required: true,
            message: this.$t('请上传支付渠道图片'),
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    afterGetInfo() {
      if (this.objForm.src) {
        this.fileList = [
          {
            name: "paymentChannel_src",
            url: this.objForm.src,
          },
        ];
      }

      // 如果存在配置，解析JSON为对象并转换为易于编辑的格式
      if (this.objForm.config) {
        try {
          const configObj = JSON.parse(this.objForm.config);
          this.configItems = Object.keys(configObj).map(key => ({
            key: key,
            value: configObj[key]
          }));
        } catch (e) {
          this.configItems = [];
          console.error('配置解析错误:', e);
        }
      }
    },
    getUploadFile(files){
      console.log(files);
      if(files.length>0){
        this.configItems[files[0]['index']]['value'] = this.objForm.src = files[0]["url"];
      }
    },
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.src = files[0]["url"];
    },

    // 添加配置项
    addConfigItem() {
      this.configItems.push({
        key: '',
        value: ''
      });
    },

    // 删除配置项
    removeConfigItem(index) {
      let that = this;
      this.$confirm(that.$t('确定要删除此配置项吗?'), that.$t('提示'), {
        confirmButtonText: that.$t('确定'),
        cancelButtonText: that.$t('取消'),
        type: 'warning'
      }).then(() => {
        this.configItems.splice(index, 1);
      }).catch(() => { });
    },

    // 提交表单前处理配置项
    beforeSubmit() {
      // 将配置项转换为单个对象格式 {"key1": "value1", "key2": "value2"}
      const configObj = {};

      this.configItems.forEach(item => {
        if (item.key) {
          configObj[item.key] = item.value;
        }
      });

      this.objForm.config = JSON.stringify(configObj);
    }
  },
};
</script>
