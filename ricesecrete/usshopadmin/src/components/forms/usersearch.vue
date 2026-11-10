<template>
  <div>
    <el-select
      v-if="field == 'id'"
      style="width: 420px"
      v-model="user_ids"
      :multiple="multiple"
      filterable
      remote
      reserve-keyword
      clearable
      placeholder="请输入用户"
      :remote-method="remoteMethod"
      :loading="loading"
      @change="changeUserId"
    >
      <el-option
        v-for="item in user_list"
        :key="item.id"
        :label="item.email + (item.nick_name ? '(' + item.nick_name + ')' : '')"
        :value="item.id"
      >
      </el-option>
    </el-select>
    <el-select
      v-if="field == 'phone'"
      style="width: 420px"
      v-model="user_ids"
      :multiple="multiple"
      filterable
      remote
      reserve-keyword
      clearable
      placeholder="请输入用户"
      :remote-method="remoteMethod"
      :loading="loading"
      @change="changeUserPhone"
    >
      <el-option
        v-for="item in user_list"
        :key="item.id"
        :label="item.phone + '(' + item.nick_name + ')'"
        :value="item.phone"
      >
      </el-option>
    </el-select>
  </div>
</template>

<script>
import user from "@/api/modules/user";

export default {
  name: "UserSearch",
  data() {
    return {
      fileList: [],
      user_list: [],
      loading: false,
      user_ids: [],
      query: "",
    };
  },
  props: {
    user_id: [],
    //是否可以多选
    multiple: {
      type: Boolean,
      default: false,
    },
    field: {
      type: String,
      default: "id",
    },
    keyword: {
      type: String,
      default: "",
    },
  },
  methods: {
    remoteMethod(query) {
      if (query !== "") {
        this.loading = true;
        let param = {
          keyword: query,
          page: 1,
          limit: 20,
        };
        user.page(param).then((res) => {
          this.loading = false;
          this.user_list = res.data.pageList;
          // console.log(this.user_list);
        });
      } else {
        this.user_list = [];
      }
    },
    initData() {
      this.loading = true;
      let param = {
        keyword: this.query,
        page: 1,
        limit: 20,
      };
      user.page(param).then((res) => {
        this.loading = false;
        this.user_list = res.data.pageList;
        // console.log(this.user_list);
      });
    },
    changeUserId(val) {
      this.$emit("changeUserId", val);
    },
    changeUserPhone(val) {
      this.$emit("changeUserPhone", val);
    },
  },
  mounted() {
    this.initData();
  },
  watch: {
    keyword: {
      handler: function (val, oldVal) {
        if (val != oldVal) {
          this.initData();
        }
      },
      deep: true,
    },
  },
  created() {
    if (this.user_id == "") {
      this.user_ids = [];
    } else {
      this.user_ids = this.user_id;
      this.query = this.user_id;
    }
  },
};
</script>
