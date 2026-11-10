<template>
  <div class="itemContent">
    <div
      v-for="(item,index) in fileList"
      :key="index"
      v-loading="loadingapp"
      class="listitem"
      @mouseenter="showDelBtn(index)"
      @mouseleave="hiddenDelBtn"
    >
      <video v-if="item" :src="item.url" class="imgSty" controls="controls" />
      <div class="delIcon" v-show="index==currentDelBtn">
        <i class="el-icon-zoom-in" @click="payVdieo(item)" />
        <i class="el-icon-delete" @click="deleImg(item,index)" />
      </div>
    </div>
    <div class="uploadIcon">
      <el-upload
        :show-file-list="false"
        ref="upload"
        action
        :multiple="multiple"
        :accept="accept"
        :limit="parseInt(limit)"
        :file-list="fileList"
        :http-request="uploadFile"
        :on-exceed="onExceed"
        :list-type="listType"
        :auto-upload="autoUpload"
      >
        <i class="el-icon-plus" v-if="listType=='picture-card'"></i>
        <el-button size="small" type="success" v-else>点击上传</el-button>
        <div slot="tip" class="el-upload__tip">
          <div>{{tips}}</div>
          <el-progress :percentage="percentage" v-if="start"></el-progress>
        </div>
      </el-upload>
    </div>
  </div>
</template>

<script>
// import oss from "@/libs/oss";
import common from "@/api/modules/common"

export default {
  name: "UploadVideo",
  data() {
    return {
      fileList: [],
      currentDelBtn: -1,
      loadingapp: false,
      drag: false,
      percentage: 0,
      start:false,
    };
  },
  props: {
    fileArr: {
      type: Array,
      default: function () {
        return [];
      },
    },
    multiple: Boolean,
    accept: String,
    fileFolder: String,
    limit: Number,
    listType: String,
    tips: String,
    maxSize: {
      type: Number,
      default: 0,
    },
    maxWidth: {
      type: Number,
      default: 0,
    },
    maxHeight: {
      type: Number,
      default: 0,
    },
    autoUpload: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    //显示删除图片的图标
    showDelBtn(index) {
      this.currentDelBtn = index;
    },
    //隐藏删除图片的图标
    hiddenDelBtn() {
      this.currentDelBtn = -1;
    },
    //播放视频
    payVdieo(data) {
      window.open(data.url);
    },
    // 删除图片
    deleImg(data, index) {
      var that = this;
      this.$confirm(`确定删除该文件吗？`, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          that.fileList.splice(index, 1);
          that.$emit("getUploadFiles", that.fileList);
        })
        .catch((err) => {
          window.console.log(err);
        });
    },
    async uploadFile(param) {
      let param_file = new FormData();
      param_file.append("file", param.file);
      param_file.append("folder", this.fileFolder);
      // console.log(param_file);
      await common.upload(param_file).then((response) => {
        if (response.code == 1) {
          this.fileList = this.fileList.concat(response.data);
          // console.log(this.fileList)
          this.$emit("getUploadFiles", this.fileList);
        }
      });
      // let that = this;
      // param.folder = this.fileFolder;
      // that.start = true;
      // param.onProgress = function (e) {
      //   that.percentage = e.percent;
      //   if(e.percent>=100){
      //       that.start = false;
      //   }
      // };
      // await oss.uploadFile(param).then((response) => {
      //   this.fileList = this.fileList.concat([response]);
      //   // console.log(this.fileList);
      //   this.$emit("getUploadFiles", this.fileList);
      // });
    },
    onExceed() {
      this.$message.error("最多上传" + this.limit + "个文件");
    },
    async onRemove(file) {
      await common.delfile(file.url).then(() => {
        this.fileList.splice(
          this.fileList.findIndex((item) => item.url === file.url),
          1
        );
        this.$emit("getUploadFiles", this.fileList);
      });
    },
    onPreview(file) {
      window.open(file.url);
    },
    onSubmit() {
      this.$refs.upload.submit();
    },
  },
  watch: {
    fileArr: {
      immediate: true,
      handler(val) {
        this.fileList = val;

      }
    }
  }
};
</script>

<style scoped>
.listitem {
  display: inline-block;
  margin: 5px 17px;
  position: relative;
  border: 1px solid #bbb;
  border-radius: 9px;
  overflow: hidden;
  width: 150px;
  height: 150px;
}
.imgSty {
  width: 100%;
  height: 100%;
}

.delIcon {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}
.el-icon-zoom-in::before {
  position: absolute;
  left: 5px;
  top: 7px;
  z-index: 10;
  color: #fff;
  font-size: 140%;
}

.el-icon-delete::before {
  position: absolute;
  right: 5px;
  top: 7px;
  z-index: 10;
  color: #fff;
  font-size: 140%;
}

.uploadIcon {
  /* margin-left: 17px; */
}
</style>
