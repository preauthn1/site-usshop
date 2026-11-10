<template>
  <div class="itemContent">
    <draggable v-model="fileList" class="list-group" tag="ul" v-bind="dragOptions" @update="datadragEnd"
      @start="drag = true" @end="drag = false">
      <div v-for="(item, index) in fileList" :key="index" v-loading="loadingapp" class="listitem"
        @mouseenter="showDelBtn(index)" @mouseleave="hiddenDelBtn">
        <video v-if="item.type == 'mp4'" :src="item.url" class="imgSty" controls="controls"></video>
        <img v-else :src="item.url" class="imgSty" />
        <div class="delIcon" v-show="index == currentDelBtn">
          <i class="el-icon-delete" @click="deleImg(item, index)" />
        </div>
      </div>
    </draggable>
    <div class="uploadIcon" v-if="parseInt(limit) > fileList.length">
      <el-upload :show-file-list="false" ref="upload" action :multiple="multiple" :accept="accept"
        :limit="parseInt(limit)" :http-request="uploadFile" :on-exceed="onExceed" :list-type="listType"
        :auto-upload="autoUpload">
        <i class="el-icon-plus" v-if="listType == 'picture-card'"></i>
        <el-button size="small" type="success" v-else>点击上传</el-button>
        <div slot="tip" class="el-upload__tip">{{ tips }}</div>
      </el-upload>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
// import oss from "@/libs/oss";
import common from "@/api/modules/common"

export default {
  name: "UploadDrag",
  data() {
    return {
      fileList: [],
      currentDelBtn: -1,
      loadingapp: false,
      drag: false,
    };
  },
  components: {
    draggable,
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
  computed: {
    //这一部分直接拿过来用的
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
  },

  methods: {
    datadragEnd() {
      this.$emit("getUploadFiles", this.fileList);
    },
    //显示删除图片的图标
    showDelBtn(index) {
      this.currentDelBtn = index;
    },
    //隐藏删除图片的图标
    hiddenDelBtn() {
      this.currentDelBtn = -1;
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
      await common.upload(param_file).then((data) => {
        if (data.code == 1) {
          if (data.data) {
            let response = data.data[0];
            let name = response.name;
            name = name.replace(/[^\d]/g, "");
            if (name == "" || name == undefined) {
              name = Date.parse(new Date()) / 1000;
            }
            this.fileList.push({
              name: parseInt(name),
              type: response.type,
              url: response.url,
            });
            this.fileList.sort(function (a, b) {
              return a.name - b.name;
            });
            this.$emit("getUploadFiles", this.fileList);
          }
        }
      });
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
      },
    },
  },
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
