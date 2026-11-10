<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">编辑</span>
          <span v-else>添加</span>等级
        </b>
        <div class="u-right u-5mt">
          <el-button type="success" size="small" icon="el-icon-document-checked"
            @click="submitForm('objForm')">提交保存</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">返回</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item label="等级名称" prop="name">
          <el-input v-model="objForm.name" placeholder="请输入等级名称" maxlength="30" show-word-limit></el-input>
        </el-form-item>

        <el-form-item label="等级图片" prop="src">
          <upload file-folder="vip" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png" tips="只能上传jpg/png文件，且不超过4MB" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item label="等级标识" prop="level">
          <el-input-number v-model="objForm.level" placeholder="请输入等级标识"></el-input-number>
        </el-form-item>
        <el-form-item label="消费值" prop="money">
          <el-input-number v-model="objForm.money" placeholder="请输入消费值" :min="0"></el-input-number>
        </el-form-item>
        <el-form-item label="有效下级人数" prop="invite_num">
          <el-input-number v-model="objForm.invite_num" placeholder="请输入有效下级人数"></el-input-number>
        </el-form-item>

        <el-form-item label="权益">
          <el-select v-model="objForm.rights_ids" placeholder="权益" clearable multiple @change="selectRights">
            <el-option v-for="item in rightsDict" :key="item.id" :label="item.name" :value="item.id"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item labelposition="top" :label="item.name" v-for="(item, index) in objForm.rights" :key="index + '_fees'">
          <el-form :inline="true" label-position="top" label-width="80px" >
            <el-form-item label="权益值">
              <el-input-number v-model="objForm.rights[index].number" placeholder="权益值" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item label="权益单位">
              <el-input v-model="objForm.rights[index].unit" placeholder="权益单位" style="width: 300px;"></el-input>
            </el-form-item>
            <el-form-item label="是否开启">
              <el-switch v-model="objForm.rights[index].is_open" active-color="#13ce66" :active-value="1" inactive-color="#666"
                         :inactive-value="2"></el-switch>
            </el-form-item>
          </el-form>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import Upload from "@/components/upload/upload";
export default {
  components: {
    Upload,
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "vip",
      backRoute: "vipIndex",
      fileList: [],
      fileList2: [], //详情
      objForm: {
        id: "",
        name: "",
        level: "",
        src: "",
        status: 1,
        sort: 0,
        rights: [],
        rights_ids: '',
      },
      rules: {
        name: [
          {
            required: true,
            message: "请输入等级名称",
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
            message: "请上传等级图片",
            trigger: "blur",
          },
        ],
        level: [
          {
            required: true,
            message: "请输入等级标识",
            trigger: "blur",
          },
        ],
        money: [
          {
            required: true,
            message: "请输入消费值",
            trigger: "blur",
          },
        ],
        invite_num: [
          {
            required: true,
            message: "请输入有效下级人数",
            trigger: "blur",
          },
        ],
      },
      rightsDict: []
    };
  },
  methods: {
    afterGetInfo() {
      if (this.objForm.src) {
        this.fileList = [
          {
            name: "vip_src",
            url: this.objForm.src,
          },
        ];
      }
      this.objForm.rights_ids = this.objForm.rights.map(item => item.id);
    },
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.src = files[0]["url"];
    },
    getLoadList() {
      let params = {
        init: 'init_params'
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code === 1) {
          this.rightsDict = response.data.rightsDict;
        }
      });
    },
    selectRights() {
     // 移除this.objForm.rights 中 id 不在 this.objForm.rights_ids 中的项
      this.objForm.rights = this.objForm.rights.filter(item => this.objForm.rights_ids.includes(item.id))
      // 遍历 this.objForm.rights_ids 数组，如果不存在则添加到 this.objForm.rights 中
      this.objForm.rights_ids.forEach(id => {
        if (!this.objForm.rights.some(item => item.id === id)) {
          let item = this.rightsDict.find(item => item.id === id)
          this.objForm.rights.push({
            id: id,
            name: item.name,
            number: 0,
            unit: ''
          })
        }
      })
    }
  },
  created() {
    this.getLoadList();
  }
};
</script>
