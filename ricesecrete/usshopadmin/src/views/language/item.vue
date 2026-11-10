<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <span>
          <b class="u-f-16">{{ $t('列表') }}</b>
        </span>
        <el-button v-if="hasPerm('languageItemEdit')" type="danger" size="small" icon="el-icon-plus"
          class="u-right u-5mt" round @click="handleAdd">{{ $t('添加') }}
        </el-button>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.title" :placeholder="$t('请输入') + $t('关键词') + $t('查询')" clearable></el-input>
        </el-form-item>
        <el-form-item >
          <el-button type="primary" icon="el-icon-search" @click="myGetPageData(true)">{{ $t('查询') }}</el-button>
              <el-button type="primary" icon="el-icon-refresh" @click="updateLang()">{{ $t('更新') }}</el-button>
              <el-button type="primary" icon="el-icon-folder" @click="outFile()">{{ $t('导出') }}</el-button>
              <el-upload :show-file-list="false" ref="upload" action accept=".csv" :limit="1" :file-list="[]"
                :http-request="inputFile" :auto-upload="true">
                <el-button size="small" icon="el-icon-folder" type="primary">上传</el-button>
              </el-upload>
          <!-- <el-button type="primary" icon="el-icon-folder" @click="inputFile()">{{ $t('导入') }}</el-button> -->
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10" v-viewer>
        <el-table-column :label="$t('序号')" type="index" :index="getIndexNo" width="100"></el-table-column>
        <el-table-column :label="$t('类目')">
          <template slot-scope="scope">
            <el-input v-model="scope.row.title" prop="title" :placeholder="$t('请输入') + $t('类目')" clearable
              @change="saveItem(scope.row.title, scope.row.id)"></el-input>
          </template>
        </el-table-column>
        <el-table-column v-for="language in languageData" :key="language.id" :label="language.name">
          <template slot-scope="scope">
            <el-input :value="scope.row.details[language.id]?.text" :placeholder="$t('请输入') + $t('翻译')" clearable
              @input="(val) => updateDetailText(scope.row, language.id, val)"
              @change="(val) => saveItemDetail(val, language.id, scope.row.id, scope.row.details[language.id]?.id)"></el-input>
          </template>
        </el-table-column>

        <el-table-column :label="$t('操作')" width="120">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)" v-if="hasPerm('languageDelBtn')">{{
              $t('删除') }}</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div class="u-mt-20 u-text-center">
        <el-pagination background @size-change="handleSizeChange" @current-change="handlePageChange"
          :current-page="currentPage" :page-sizes="[10, 20, 30, 40, 50]" :page-size="limit"
          layout="total, sizes, prev, pager, next, jumper" :total="total">
        </el-pagination>
      </div>
    </el-card>

    <!-- 添加类目弹窗 -->
    <el-dialog :title="$t('添加') + $t('类目')" :visible.sync="dialogVisible" width="500px">
      <el-form :model="formData" :rules="rules" ref="formRef" label-width="150px">
        <el-form-item :label="$t('类目') + $t('名称')" prop="title">
          <el-input v-model="formData.title" :placeholder="$t('请输入') + $t('类目') + $t('名称')"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">{{ $t('取 消') }}</el-button>
        <el-button type="primary" @click="submitForm">{{ $t('确 认') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import commonPage from "@/mixins/common-page";
import store from "@/store";
import { getApiUrl } from "@/libs/util";
export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: "languageItem",
      queryForm: {
        text: "",
      },
      languageData: [],
      dialogVisible: false,
      formData: {
        title: '',
        translations: {}
      },
      rules: {
        title: [
          { required: true, message: this.$t('请输入') + this.$t('名称'), trigger: 'blur' }
        ]
      }
    };
  },
  methods: {
    handleChange() {
      this.getPageData(true);
    },
    afterOnLoad() {
      this.$api.language.page({
        page: 1,
        limit: 99,
        status: 1,
      }).then(response => {
        this.languageData = response.data.pageList;
        // 重构表格数据，确保每个语言都有对应的翻译项
        this.tableData = this.tableData.map(row => {
          const detailMap = {};

          // 循环所有可用语言
          this.languageData.forEach(lang => {
            // 在原有details中查找当前语言的翻译
            const existingDetail = row.details.find(detail => detail.language_id === lang.id);

            // 如果找到就使用现有的，没找到就初始化空对象
            detailMap[lang.id] = existingDetail ? {
              id: existingDetail.id,
              text: existingDetail.text
            } : {
              id: undefined,
              text: ''
            };
          });

          return {
            ...row,
            details: detailMap
          };
        });
      })
    },
    saveItem(title, id) {
      let that = this;
      this.$api[this.apiName].save({
        id,
        title
      }).then(response => {
        if (response.code == 1) {
          this.$message({
            type: 'success',
            message: that.$t('操作成功') + '!'
          });
        }
      });
    },
    //导出语言包
    outFile() {
      let url =
        getApiUrl() +
        "/language/item/detail/file?write_lang=out_file&type=AppAdmin&token=" +
        store.getters.access_token;
      console.log(url);
      window.open(url);
    },
    inputFile(param) {
      let param_file = new FormData();
      //?write_lang=input_file
      param_file.append("write_lang", "input_file");
      param_file.append("file", param.file);
      console.log(param_file);
      this.$api[this.apiName].inputFile(param_file).then((response) => {
        if (response.code == 1) {
          console.log(response.data);
        }
      });
    },
    //刷新语言包
    updateLang() {
      let that = this;
      this.$api.languageItem.detailSave({
        write_lang: 'update'
      }).then(response => {
        if (response.code == 1) {
          this.$message({
            type: 'success',
            message: that.$t('操作成功') + '!'
          });
        }
      });
    },
    saveItemDetail(text, language_id, item_id, id) {
      let that = this;
      if (!text) return; // 如果文本为空，不进行保存

      // 确保有 details 对象
      if (!this.tableData.find(row => row.id === item_id).details[language_id]) {
        this.$set(this.tableData.find(row => row.id === item_id).details, language_id, {
          id: undefined,
          text: text
        });
      }

      this.$api.languageItem.detailSave({
        id,
        language_id,
        item_id,
        text
      }).then(response => {
        if (response.code == 1) {
          this.$message({
            type: 'success',
            message: that.$t('操作成功') + '!'
          });
        }
      });
    },
    myGetPageData(refresh = false) {
      this.getPageData(refresh).then(() => {
        this.afterOnLoad()
      })
    },
    updateDetailText(row, languageId, value) {
      if (!row.details[languageId]) {
        this.$set(row.details, languageId, { id: undefined, text: value });
      } else {
        row.details[languageId].text = value;
      }
    },
    handleSizeChange(val) {
      this.limit = val
      this.getPageData().then(() => {
        this.afterOnLoad()
      })
    },
    handlePageChange(val) {
      this.currentPage = val
      this.getPageData().then(() => {
        this.afterOnLoad()
      })
    },
    handleAdd() {
      this.dialogVisible = true;
      this.formData = {
        title: ''
      };
    },
    submitForm() {
      let that = this;
      this.$refs.formRef.validate((valid) => {
        if (valid) {
          this.$api.languageItem.save({
            title: this.formData.title
          }).then(response => {
            if (response.code === 1) {
              this.$message.success(that.$t('保存成功'));
              this.dialogVisible = false;
              this.myGetPageData(true);
            }
          });
        }
      });
    }
  },
};
</script>
