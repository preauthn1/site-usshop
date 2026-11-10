<template>
  <div >
    <div class="dialog-header">
      <el-button type="success" size="small" icon="el-icon-document-checked"
        @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
    </div>
    <el-card shadow="never">

      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="150px" class="u-form">
        <el-form-item :label="$t('分类名称')">
          <el-input type="text" v-model="objForm.name" :placeholder="$t('请输入分类名称')"></el-input>
        </el-form-item>
        <el-form-item :label="$t('分类图片')" prop="img">
          <upload file-folder="goods_type" :file-arr="fileList" list-type="picture-card" :limit="1"
            accept="image/jpeg, image/png,image/webp" :tips="$t('只能上传jpg/png/webp文件，且不超过4MB')" @getUploadFiles="getUploadFiles"></upload>
        </el-form-item>
        <el-form-item :label="$t('是否启用')">
          <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
            :inactive-value="2"></el-switch>
        </el-form-item>
        <el-form-item :label="$t('是否显示')">
          <el-switch v-model="objForm.is_show" active-color="#13ce66" :active-value="1" inactive-color="#666"
            :inactive-value="2"></el-switch>
        </el-form-item>
        <!-- <el-form-item :label="$t('跳转链接')">
          <el-input type="text" v-model="objForm.url" :placeholder="$t('特殊分类跳转链接')"></el-input>
        </el-form-item> -->
        <el-form-item :label="$t('排序')">
          <el-col :span="11">
            <el-input v-model="objForm.sort" :placeholder="$t('排序')"></el-input>
          </el-col>
          <el-col class="line" :span="1"></el-col>
          <el-col :span="11">
            <el-button class="button-new-tag" size="small">{{$t('数值越大权重越高')}}</el-button>
          </el-col>
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
    Upload
  },
  mixins: [commonEdit],
  data() {
    return {
      apiName: "goodsType",
      backRoute: "goodsTypeIndex",
      fileList: [], //临时相册
      levelTree: [],
      level_ids: [],
      levelProps: {
        label: "name",
        value: "id",
        children: "children",
      },
      objForm: {
        id: "",
        name: "",
        img: '',
        status: 1,
        is_show: 1,
        sort: 0,
        url: '',
        parent_id: 0,
      },
    };
  },
  methods: {
    afterGetInfo() {
      this.level_ids = this.objForm.parent_id;
      if (this.objForm.img) {
        this.fileList = [
          {
            name: "tourism_type_img",
            url: this.objForm.img,
          },
        ];
      }
    },
    getLevelTree() {
      this.$api.goodsType.tree().then((response) => {
        if (response.code == 1) {
          this.levelTree = response.data.treeData;
        }
      });
    },
    handleLevelSelect(val) {
      if (!(val == null || val == undefined)) {
        this.objForm.parent_id = val[val.length - 1];
      }
    },
    getUploadFiles(files) {
      this.fileList = files;
      this.objForm.img = files[0]["url"];
    }
  },
  created() {
    // console.log(this.$route.params.pid);

    if (this.pid) {
      this.objForm.parent_id = this.pid;
      this.level_ids = this.objForm.parent_id;
    }
    // this.getLevelTree();
  },
};
</script>
