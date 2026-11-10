<template>
  <div class="u-p-20">
    <el-card shadow="never">
      <div slot="header">
        <b class="u-f-16">
          <span v-if="objForm.id">{{$t('编辑')}}</span>
          <span v-else>{{$t('添加')}}</span>{{$t('角色')}}
        </b>
        <div class="u-right u-5mt">
          <el-button
            type="success"
            size="small"
            icon="el-icon-document-checked"
            @click="submitForm('objForm')"
          >{{$t('提交保存')}}</el-button>
          <el-button size="small" icon="el-icon-back" @click="$router.back()">{{$t('返回')}}</el-button>
        </div>
      </div>
      <el-form :model="objForm" :rules="rules" ref="objForm" label-width="100px" class="u-form">
        <el-form-item :label="$t('角色名称')" prop="title">
          <el-input v-model="objForm.title" :placeholder="$t('请输入角色名称')"></el-input>
        </el-form-item>
        <el-form-item :label="$t('权限选择')">
          <el-tree
            class="u-mt-10"
            :data="authTreeData"
            :default-checked-keys="defaultChecked"
            :check-strictly="true"
            show-checkbox
            node-key="id"
            ref="authTree"
            @check-change="checkChange"
            highlight-current
            :props="authTreeProps"
          >
            <span class="custom-tree-node" slot-scope="{ node, data }">
              <span>{{ node.label }}</span>
              <span class="u-ml-10">
                <el-tag v-if="data.type==0" type="warning" size="mini">{{$t('目录')}}</el-tag>
                <el-tag v-else-if="data.type==1" type="success" size="mini">{{$t('菜单')}}</el-tag>
                <el-tag v-else-if="data.type==2" type="info" size="mini">{{$t('页面')}}</el-tag>
                <el-tag v-else-if="data.type==3" type="danger" size="mini">{{$t('按钮元素')}}</el-tag>
                <el-tag v-else-if="data.type==4" size="mini">{{$t('接口')}}-{{$t(data.api_method_text)}}</el-tag>
              </span>
            </span>
          </el-tree>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import commonEdit from "@/mixins/common-edit";

export default {
  mixins: [commonEdit],
  data() {
    return {
      apiName: "authGroup",
      backRoute: "authRole",
      defaultChecked: [],
      authTreeData: [],
      authTreeProps: {
        children: "children",
        label: "title"
      },
      objForm: {
        id: "",
        title: "",
        rules: "",
        status: 1
      },
      rules: {
        title: [
          { required: true, message: this.$t('请输入角色名称'), trigger: "blur" },
          { max: 30, message: this.$t('最大长度30个字符'), trigger: "blur" }
        ]
      }
    };
  },
  methods: {
    afterGetInfo() {
      this.defaultChecked = this.objForm.rules.split(",");
    },
    beforeSubmit() {
      let rules = this.$refs.authTree.getCheckedKeys().join(",");
      this.objForm.rules = rules;
    },
    getAuthTree() {
      this.$api.authRule.tree().then(response => {
        if (response.code == 1) {
          this.authTreeData = response.data.treeData;
        }
      });
    },
    checkChange(data, check) {
      // 父节点操作
      if (data.parent_id !== 0) {
        if (check === true) {
          // 如果选中，设置父节点为选中
          this.$refs.authTree.setChecked(data.parent_id, true);
        } else {
          // 如果取消选中，检查父节点是否该取消选中（可能仍有子节点为选中状态）
          var parentNode = this.$refs.authTree.getNode(data.parent_id);
          var parentHasCheckedChild = false;
          for (
            var i = 0, parentChildLen = parentNode.childNodes.length;
            i < parentChildLen;
            i++
          ) {
            if (parentNode.childNodes[i].checked === true) {
              parentHasCheckedChild = true;
              break;
            }
          }
          if (!parentHasCheckedChild)
            this.$refs.authTree.setChecked(data.parent_id, false);
        }
      }
      var childNode = null;
      var j = 0;
      var len = 0;
      // 子节点操作，如果取消选中，取消子节点选中
      if (data.children != null && check === false) {
        for (j = 0, len = data.children.length; j < len; j++) {
          childNode = this.$refs.authTree.getNode(data.children[j].id);
          if (childNode.checked === true) {
            this.$refs.authTree.setChecked(childNode.data.id, false);
          }
        }
      }
      if (data.children != null && check === true) {
        for (j = 0, len = data.children.length; j < len; j++) {
          childNode = this.$refs.authTree.getNode(data.children[j].id);
          if (childNode.checked === false) {
            this.$refs.authTree.setChecked(childNode.data.id, true);
          }
        }
      }
    }
  },
  created() {
    this.getAuthTree();
  }
};
</script>
