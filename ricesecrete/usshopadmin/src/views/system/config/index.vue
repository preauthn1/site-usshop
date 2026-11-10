<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">{{ $t('参数列表') }}</b>
        <router-link :to="{ name: 'sysConfigEdit' }" v-if="hasPerm('sysConfigEdit')">
          <el-button type="danger" size="small" icon="el-icon-plus" round class="u-right u-5mt">{{ $t('添加') }}</el-button>
        </router-link>
      </div>

      <el-form :inline="true" :model="queryForm" size="small" class="u-text-center">
        <el-form-item :label="$t('关键词')">
          <el-input v-model="queryForm.keyword" :placeholder="$t('输入关键词')"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" icon="el-icon-search" @click="getPageData(true)">{{ $t('查询') }}</el-button>
        </el-form-item>
      </el-form>

      <el-table :data="tableData" class="u-mt-10">
        <el-table-column :label="$t('ID')" prop="id" width="80"></el-table-column>
        <el-table-column :label="$t('参数名称')" prop="name"></el-table-column>
        <el-table-column :label="$t('参数键')" prop="key"></el-table-column>
        <el-table-column :label="$t('参数值')" prop="value"></el-table-column>
        <el-table-column :label="$t('添加日期')" prop="add_time" :formatter="dateFormat"></el-table-column>
        <el-table-column :label="$t('修改日期')" prop="update_time" :formatter="dateFormat"></el-table-column>
        <el-table-column :label="$t('操作')" width="150">
          <template slot-scope="scope">
            <el-button size="mini" type="text" @click="handleRoute(scope.row.id, 'sysConfigEdit')"
              v-if="hasPerm('sysConfigEdit')">{{ $t('编辑') }}</el-button>
            <el-button size="mini" type="text" @click="handleDelete(scope.row.id)"
              v-if="hasPerm('sysConfigDelBtn')">{{ $t('删除') }}</el-button>
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
  </div>
</template>

<script>
import commonPage from '@/mixins/common-page'

export default {
  mixins: [commonPage],
  data() {
    return {
      apiName: 'sysConfig',
      queryForm: {
        keyword: ''
      }
    }
  },
  methods: {
    dateFormat: function (row, column) {
      var date = row[column.property];
      if (date == undefined) { return '' }
      date *= 1000;
      return this.$moment(date).format("YYYY-MM-DD HH:mm:ss")
    },
  }
}
</script>