<template>
  <div class="itemContent">
    <draggable v-model="fileList" class="list-group" tag="ul" v-bind="dragOptions" @update="datadragEnd"
      @start="drag = true" @end="drag = false">
      <div v-for="(item, index) in fileList" :key="index" v-loading="loadingapp" class="listitem"
        @mouseenter="showDelBtn(index)" @mouseleave="hiddenDelBtn">
        <video v-if="item.type == 'mp4'" :src="item.url" class="imgSty" controls="controls"></video>
        <el-image v-else :ref="'img_' + index" class="imgSty" :src="item.url" :preview-src-list="[item.url]">
        </el-image>
        <!-- <div class="viewIcon" v-show="index == currentDelBtn">
          <i class="el-icon-view" @click="viewImg(index)" />
        </div>
         -->
        <div class="delIcon">
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
        <div slot="tip" class="el-upload__tip">
          <div>{{ tips }}</div>
          <el-progress :percentage="percentage" v-if="start"></el-progress>
        </div>
      </el-upload>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import common from "@/api/modules/common";
import s3Api from "@/api/modules/s3";
import { getFileTypeFromUrl } from "@/libs/util";

export default {
  name: "UploadDragOss",
  data() {
    return {
      fileList: [],
      currentDelBtn: -1,
      loadingapp: false,
      drag: false,
      percentage: 0,
      start: false,
      imageUrl: "",
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
    viewImg(index) {
      console.log(index);
      console.log(this.$refs);
      let name = "img_" + index;
      console.log(name);
      if (this.$refs.img_0) {
        // 使用dispatchEvent触发点击事件
        const event = new MouseEvent("click", {
          view: window,
          bubbles: true,
          cancelable: false,
        });
        this.$refs.img_0.dispatchEvent(event);
      }
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
      try {
        const file = param.file;
        const urlRes = await s3Api.getPresignedUrl(this.fileFolder, file.name);
        if (urlRes.code !== 1) {
          this.$message.error('获取上传地址失败');
          return;
        }

        let that = this;
        that.start = true;
        that.percentage = 0;

        // Upload file using pre-signed URL with progress tracking
        await s3Api.uploadFile(
          urlRes.data.url,
          file,
          (progress) => {
            that.percentage = progress;
            if (progress >= 100) {
              that.start = false;
            }
          }
        ).then(async (res) => {
          if (res.status == 200) {
            let url = urlRes.data.base_url + '/' + urlRes.data.key;
            let type = getFileTypeFromUrl(url);
            this.fileList.push({
              name: parseInt(file.name),
              type: type,
              url: url,
            });
            this.$emit("getUploadFiles", this.fileList);
          }
        }).catch(err => {
          console.error('Upload error:', err);
          this.$message.error('上传失败');
        });
      } catch (error) {
        console.error('Upload error:', error);
        this.$message.error('上传失败');
        this.start = false;
      }
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

.viewIcon {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
}

.el-icon-view::before {
  position: absolute;
  left: 65px;
  top: 65px;
  z-index: 10;
  color: #fff;
  font-size: 140%;
}

.delIcon {
  position: absolute;
  right: 0;
  top: 0;
  width: 30px;
  height: 30px;
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
