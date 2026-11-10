<template>
  <div>
    <div class="dialog-header">
      <el-button type="success" size="small" icon="el-icon-document-checked"
        @click="submitForm('objForm')">{{$t('提交保存')}}</el-button>
    </div>
    <el-card shadow="never">
      <el-form :model="objForm" ref="objForm" label-width="150px" class="u-form" style="max-width: 90%">
        <el-tabs v-model="activeName" @tab-click="submitFormTemp">
          <el-tab-pane :label="$t('基本信息')" name="basic">
            <el-form-item :label="$t('分类')">
              <el-cascader expand-trigger="hover" :options="levelTree" @change="handleLevelSelect" :placeholder="$t('分类')"
                clearable change-on-select :props="levelProps" v-model="level_ids" class="u-w-block"></el-cascader>
            </el-form-item>
            <el-form-item :label="$t('排序')">
              <el-input-number v-model="objForm.sort" :min="0" :label="$t('数值越大权重越高')"></el-input-number>
            </el-form-item>
            <el-form-item :label="$t('销量')">
              <el-input-number v-model="objForm.buy" :min="0"></el-input-number>
            </el-form-item>
            <el-form-item :label="$t('是否上线')">
              <el-switch v-model="objForm.status" active-color="#13ce66" :active-value="1" inactive-color="#666"
                :inactive-value="2"></el-switch>
            </el-form-item>
            <el-form-item :label="$t('是否推荐')">
              <el-switch v-model="objForm.rec" active-color="#13ce66" :active-value="1" inactive-color="#666"
                :inactive-value="2"></el-switch>
            </el-form-item>
            <el-form-item :label="$t('是否热门')">
              <el-switch v-model="objForm.hot" active-color="#13ce66" :active-value="1" inactive-color="#666"
                :inactive-value="2"></el-switch>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane :label="$t('商品标题图片、详情')" name="img">
            <el-tabs v-model="langCode" @tab-click="handleLangClick">
              <el-tab-pane :label="lang.name" :name="lang.code" v-for="lang in langList" :key="lang.id">
                <el-form-item :label="$t('商品名称')">
                  <el-input v-model="objForm.title[lang.code]" :placeholder="$t('商品名称')" maxlength="500"
                    show-word-limit></el-input>
                </el-form-item>
                <el-form-item :label="$t('封面图片')" prop="img">
                  <upload file-folder="product_img" :file-arr="fileList" list-type="picture-card" :limit="1"
                    accept="image/jpeg, image/png, image/webp" :tips="$t('只能上传jpg/png/webp文件，且不超过4MB')" @getUploadFiles="getUploadFiles">
                  </upload>
                </el-form-item>
                <el-form-item :label="$t('相册图片/视频')" prop="photo">
                  <upload-drag-s3 file-folder="product_photo" :file-arr="fileList1" :multiple="true"
                    list-type="picture-card" :limit="10" accept="image/jpeg, image/png, video/mp4, image/webp"
                    :tips="$t('只能上传jpg/png/mp4/webp格式的文件，图片/视频尺寸为750px*485px，图片大小不超过4MB,视频大小不超过20MB')"
                    @getUploadFiles="productPhoto"></upload-drag-s3>
                </el-form-item>
                <el-form-item :label="$t('详情图片')" prop="details">
                  <upload-drag-s3 file-folder="product_detail" :file-arr="fileList2" :multiple="true"
                    list-type="picture-card" :limit="31" accept="image/jpeg, image/png ,image/webp" :tips="$t('只能上传jpg/png/webp文件，且不超过4MB')"
                    @getUploadFiles="productDetail"></upload-drag-s3>
                </el-form-item>
              </el-tab-pane>
            </el-tabs>
          </el-tab-pane>
          <el-tab-pane :label="$t('规格')" name="attr">
            <div class="attr-div">
              <el-row :gutter="20">
                <el-col :span="12">
                  <el-form-item :label="$t('添加规格名称')">
                    <el-input v-model="specName" :placeholder="$t('规格名称 例如 颜色，尺寸，重量 等')" clearable></el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="3">
                  <el-button type="primary" @click="addAttr">{{$t('添加')}}</el-button>
                </el-col>
              </el-row>
              <div class="line"></div>
              <el-row :gutter="20" v-for="(item, index) in attr_list" v-bind:key="index">
                <el-col :span="14">
                  <el-form-item :label="item.name">
                    <el-checkbox v-for="(itemx, indexx) in item.list" v-bind:key="indexx + '_value'"
                      v-model="itemx.selected" @change="selectValue">
                      {{ itemx.name }}
                      <i class="el-icon-close" @click.stop="onDeleteAttrValue(index, indexx)"></i>
                    </el-checkbox>
                  </el-form-item>
                </el-col>
                <el-col :span="10">
                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-input v-model="specValue[index]" size="mini" :placeholder="$t('规格值 例如颜色： 白色，黑色，尺寸：M,L,XL 等')"
                        clearable></el-input>
                    </el-col>
                    <el-col :span="5">
                      <el-button type="primary" size="mini" @click="addValue(index)">{{$t('添加')}}</el-button>
                    </el-col>
                    <el-col :span="7">
                      <el-button type="danger" size="mini" @click="onDeleteAttr(index)">{{$t('删除')}}</el-button>
                    </el-col>
                  </el-row>
                </el-col>
              </el-row>
            </div>
            <div class="goods-list">
              <el-table :data="objForm.goods_list" class="u-mt-10" v-viewer>
                <el-table-column :label="$t('商品图片')" width="100">
                  <template slot-scope="scope">
                    <div @click="uploadImg('uploadGoods' + scope.$index)"
                      style="width: 80px; height: 80px; background-size: 100%"
                      :style="'background-image: url(' + scope.row.img + ')'">
                      <span v-if="scope.row.img == ''">{{$t('上传图片')}}</span>
                    </div>
                    <div style="display: none">
                      <upload file-folder="goods_img" :file-arr="fileTempList" list-type="picture-card"
                        :index="scope.$index" :limit="1" accept="image/jpeg, image/png, image/webp" :tips="$t('只能上传jpg/png/webp文件，且不超过4MB')"
                        @getUploadFiles="getGoodsUploadFiles"></upload>
                    </div>
                  </template>
                </el-table-column>
                <el-table-column :label="$t('规格名称')" prop="name" width="300"></el-table-column>
                <el-table-column :label="$t('进货价')" width="100">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.cost_price"></el-input>
                  </template>
                </el-table-column>
                <el-table-column :label="objForm.classify == 2 ? $t('售价') : $t('积分')" width="100">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.price"></el-input>
                  </template>
                </el-table-column>
                <el-table-column :label="$t('库存')" width="100">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.store"></el-input>
                  </template>
                </el-table-column>
                <el-table-column :label="$t('限购')" width="100">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.limit"></el-input>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-tab-pane>
        </el-tabs>
      </el-form>
    </el-card>
<!--    <el-dialog :title="$t('选着地址')" :visible.sync="locationDialogVisible" width="80%" center>-->
<!--      <span slot="footer" class="dialog-footer">-->
<!--        <el-button type="primary" @click="locationDialogVisible = false">{{$t('确 定')}}</el-button>-->
<!--      </span>-->
<!--    </el-dialog>-->
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";
import { getUrlArr } from "@/libs/util";
import Upload from "@/components/upload/upload";
import { MessageBox } from "element-ui";
import UploadDragS3 from "@/components/uploaddrag/uploaddrags3";
// import AMapSelect from "@/components/map/amapselect";
export default {
  components: {
    Upload,
    // VDistpicker,
    UploadDragS3,
    // AMapSelect,
  },
  mixins: [commonEdit],
  data() {
    return {
      activeName: "basic",
      apiName: "goodsProduct",
      backRoute: "goodsProductIndex", //成功跳转地址
      fileList: [], //临时封面
      fileList1: [], //临时相册
      fileList2: [], //详情
      fileTempList: [],//临时
      levelTree: [],
      level_ids: [],
      levelProps: {
        label: "name",
        value: "id",
        children: "children",
      },
      langList: [],
      locationDialogVisible: false,
      specName: "", //规格名称
      specValue: [], //规格值
      attr_list: [], //规格属性列表
      temp_goods_list: [], //临时规格列表
      langCode: "",//语言标识
      objForm: {
        id: "",
        type_id: "", //分类id
        classify: 2, //商品模块分类  2正常商品  3积分商品
        title: {}, //标题
        express_type: 1, //物流类型 1快递  2到店
        // express_id: "", //快递政策
        // serve: {
        //   //服务说明  id 集合
        //   refund_id: "", //退订政策
        //   safeguard_id: "", //假一赔四
        //   send_id: "", //48小时发货
        // },
        country: "", //国家
        province: "", //省
        city: "", //市
        county: "", //区
        address: "", //自提具体地址
        lng: "", //经度
        lat: "", //纬度
        img: {}, //封面退订政策
        photo: {}, //相册
        details: {}, //详情
        status: 1, //是否上线 2下线，1上线
        sort: 0,
        hot: 1, //是否热门 1否 2是
        rec: 1, //是否推荐 1否 2是
        spec: {}, //商品规格属性列表
        goods_list: [], //商品规格列表
        buy: 100,
      },
    };
  },
  methods: {
    onSelectLocation(lng, lat, searchKey) {
      // console.log(lng, lat, searchKey);
      this.objForm.address = searchKey;
      this.objForm.lng = lng;
      this.objForm.lat = lat;
    },
    province(data) {
      //省选择
      this.objForm.province = data.value == "省" ? "" : data.value;
    },
    city(data) {
      //市选择
      this.objForm.city = data.value == "市" ? "" : data.value;
    },
    area(data) {
      //区选择
      this.objForm.county = data.value == "区" ? "" : data.value;
    },
    getLoadList() {
      let params = {
        init: "init_params",
      };
      this.$api[this.apiName].page(params).then((response) => {
        if (response.code == 1) {
          this.levelTree = response.data.levelTree;
          this.langList = response.data.langList;
          if (this.langList.length > 0) {
            this.langCode = this.langList[0].code;
            // let arrtemp = ["title",'img','photo','details'];
            // this.langList.forEach(item => {
            //   this.objForm.title[item.code] = "";
            //   this.objForm.img[item.code] = "";
            //   this.objForm.photo[item.code] = "";
            //   this.objForm.details[item.code] = "";
            // })
            console.log(this.objForm);
            this.updateImgs();
          }
        } else {
          this.$message.error(response.msg);
        }
      });
    },
    handleLevelSelect(val) {
      if (!(val == null || val == undefined)) {
        this.objForm.type_id = val[val.length - 1];
      }
    },
    getUploadFiles(files) {
      // window.console.log(files);
      //封面上传回调
      if (files.length > 0) {
        this.fileList = files;
        this.objForm.img[this.langCode] = files[0]["url"];

      } else {
        this.fileList = [];
        this.objForm.img[this.langCode] = "";
      }
    },
    productPhoto(files) {
      //相册上传回调
      if (files.length > 0) {
        this.fileList1 = files;
        this.objForm.photo[this.langCode] = "";
        for (var i = 0; i < files.length; i++) {
          this.objForm.photo[this.langCode] +=
            this.objForm.photo[this.langCode] == "" ? files[i].url : "," + files[i].url;
        }
      } else {
        this.fileList1 = [];
        this.objForm.photo[this.langCode] = "";
      }
    },
    productDetail(files) {
      console.warn(files);
      //详情上传回调
      if (files.length > 0) {
        this.fileList2 = files;
        this.objForm.details[this.langCode] = "";
        for (var i = 0; i < files.length; i++) {
          this.objForm.details[this.langCode] +=
            this.objForm.details[this.langCode] == "" ? files[i].url : "," + files[i].url;
        }
      } else {
        this.fileList2 = [];
        this.objForm.details[this.langCode] = "";
      }
    },
    handleLangClick() {
      this.updateImgs();
    },
    afterGetInfo() {
      this.level_ids = this.objForm.type_id;
      this.attr_list = this.objForm.spec;
      this.temp_goods_list = this.objForm.goods_list;
    },
    updateImgs() {
      if (this.langCode) {
        //初始化-获取编辑数据
        this.fileList = getUrlArr(this.objForm.img[this.langCode], "img_"); //相册
        this.fileList1 = getUrlArr(this.objForm.photo[this.langCode], "photo_"); //相册
        this.fileList2 = getUrlArr(this.objForm.details[this.langCode], "details_"); //详情
      }
    },
    beforeSubmit() {
      //提交数据前
    },
    //保存数据
    submitFormTemp() {
      if (this.activeName == "attr") {
        const that = this;
        that.$refs["objForm"].validate((valid) => {
          if (valid) {
            that.$api[that.apiName].save(that.objForm).then((response) => {
              if (response.code == 1) {
                that.objForm.id = response.data.id;
              }
            });
          }
        });
      }
    },
    onDeleteAttr(index) {
      this.delAttribute("attr", index);
    },
    onDeleteAttrValue(index, indexx) {
      this.delAttribute("value", index, indexx);
    },
    addAttr() {
      let that = this;
      let is_rep = false; //规格名称是否重复
      this.attr_list.forEach(function (item) {
        if (item.name == that.specName) {
          is_rep = true;
        }
      });
      if (is_rep) {
        MessageBox({
          title: "提示",
          message: "规格名称已存在,请更换",
          type: "error",
        });
      } else {
        this.addAttribute("attr", this.specName, 0);
        this.specName = "";
      }
    },
    addValue(index) {
      let that = this;
      let is_rep = false; //规格名称是否重复
      this.attr_list.forEach(function (item) {
        if (item.list.length > 0) {
          item.list.forEach(function (itemx) {
            if (itemx.name == that.specValue[index]) {
              is_rep = true;
            }
          });
        }
      });
      if (is_rep) {
        MessageBox({
          title: this.langt("提示"),
          message: this.langt("规格值已存在,请更换"),
          type: "error",
        });
      } else {
        this.addAttribute("value", this.specValue[index], index);
        this.specValue[index] = "";
      }
    },
    addAttribute(type, name, index = 0) {
      name = name.replace(/\s+/g, "");
      //添加规格名称--规格值
      if (type == "attr") {
        var temp = {
          name: name,
          list: [],
        };
        this.attr_list.push(temp);
        this.specValue.push("");
      } else if (type == "value") {
        this.attr_list[parseInt(index)].list.push({
          selected: true,
          name: name,
        });
      }
      this.objForm.spec = this.attr_list;
      this.updateSKU();
    },
    delAttribute(type, index, indexx = 0) {
      //删除规格名称-规格值
      if (type == "attr") {
        this.attr_list.splice(parseInt(index), 1);
      } else if (type == "value") {
        this.attr_list[index].list.splice(parseInt(indexx), 1);
      }
      this.updateSKU();
    },
    selectValue() {
      this.updateSKU();
    },
    uploadImg(id) {
      // console.log(id);
      document.getElementById(id).click();
      // this.$refs[id].$emit("click");
    },
    getGoodsUploadFiles(files) {
      // console.log(files);
      //规格封面上传回调
      let index = files[0]["index"];
      this.objForm.goods_list[index].img = files[0]["url"];
      this.fileTempList = [];
      // console.log(this.objForm.goods_list);
    },
    /**
     * 转换数据并进行SKU组合
     */
    updateSKU() {
      let that = this;
      let temp_sku = Array();
      let attr_arr_list = [];

      if (this.attr_list.length <= 0) {
        return;
      }
      this.attr_list.forEach(function (item, index) {
        if (item.list.length > 0) {
          item.list.forEach(function (itemx) {
            if (itemx.selected) {
              if (attr_arr_list[index] == undefined) {
                attr_arr_list[index] = [];
              }
              attr_arr_list[index].push(itemx.name);
            }
          });
        }
      });
      // console.log(attr_arr_list);
      for (let key in attr_arr_list) {
        temp_sku.push(attr_arr_list[key]);
      }
      if (temp_sku.length <= 0) {
        return;
      }
      let sku = this.doExchange(temp_sku);
      that.objForm.goods_list = [];
      sku.forEach(function (item) {
        let goods = that.isInGoods(item);
        let goods_item = null;
        if (goods != "") {
          goods_item = goods;
        } else {
          goods_item = {
            id: "",
            name: item, //规格名称 ;分割
            spec: that.getSpecStr(item), //规格
            img: "", //属性图片
            store: 99999, //库存
            price: 0, //售价
            cost_price: 0, //进价
            limit: 0, //限购数量 0表示不限
            buy: 0, //销量
          };
        }
        that.objForm.goods_list.push(goods_item);
      });
    },
    //获取规格
    getSpecStr(name) {
      let namearr = name.split(";");
      let spec = [];
      let that = this;

      namearr.forEach(function (item, index) {
        spec.push(that.attr_list[index].name + "|" + item);
      });
      return spec.join(";");
    },
    isInGoods(name) {
      //判断原来商品是否存在
      let itemx = "";
      if (this.temp_goods_list.length > 0) {
        this.temp_goods_list.forEach(function (item) {
          if (item.name == name) {
            itemx = item;
            return;
          }
        });
      }
      return itemx;
    },
    /*
     * 对商品属性进行SKU组合
     */
    doExchange(arr) {
      var len = arr.length;
      // 当数组大于等于2个的时候
      if (len >= 2) {
        // 第一个数组的长度
        var len1 = arr[0].length;
        // 第二个数组的长度
        var len2 = arr[1].length;
        // 2个数组产生的组合数
        var lenBoth = len1 * len2;
        //  申明一个新数组,做数据暂存
        var items = new Array(lenBoth);
        // 申明新数组的索引
        var index = 0;
        var i = 0;
        var j = 0;
        // 2层嵌套循环,将组合放到新数组中
        for (i = 0; i < len1; i++) {
          for (j = 0; j < len2; j++) {
            items[index] = arr[0][i] + ";" + arr[1][j];
            index++;
          }
        }
        // 将新组合的数组并到原数组中
        var newArr = new Array(len - 1);
        for (i = 2; i < arr.length; i++) {
          newArr[i - 1] = arr[i];
        }
        newArr[0] = items;
        // 执行回调
        return this.doExchange(newArr);
      } else {
        return arr[0];
      }
    },
  },
  created() {
    if (this.$route.params.id) {
      this.objForm.id = this.$route.params.id;
    }
    this.getLoadList();
  },
};
</script>

<style>
.el-checkbox.is-bordered+.el-checkbox.is-bordered {
  margin-bottom: 10px;
  margin-left: 0;
  margin-right: 15px;
}

.el-checkbox.is-bordered {
  padding: 9px 10px 9px 10px;
}

.el-icon-close {
  color: red;
}

.line {
  width: 100%;
  height: 1px;
  background: #ccc;
  margin: 10px 0;
}
</style>
