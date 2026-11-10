<template>
  <div class="layout-sider">
    <el-menu
      :default-active="activeName"
      :mode="mode"
      :collapse="isCollapse"
      :unique-opened="true"
      router
    >
      <template v-for="menu in menuTree">
        <el-submenu :index="menu.name" :key="menu.id" v-if="menu.type == 0">
          <template slot="title">
            <i :class="menu.icon"></i>
            <span slot="title">{{ menu.title }}</span>
            <el-badge :value="menu.sum" class="item" v-if="menu.sum > 0">
            </el-badge>
          </template>

          <template v-for="child in menu.children">
            <el-menu-item-group v-if="child.type == 0" :key="child.id">
              <template slot="title">{{ child.title }}</template>

              <template v-for="child2 in child.children">
                <el-menu-item
                  :index="child2.name"
                  :route="{ name: child2.name }"
                  :key="child2.id"
                  >{{ child2.title }}</el-menu-item
                >
              </template>
            </el-menu-item-group>

            <el-menu-item
              :index="child.name"
              :route="{ name: child.name }"
              :key="child.id"
              v-else-if="child.type == 1"
              >{{ child.title }}</el-menu-item
            >
          </template>
        </el-submenu>

        <el-menu-item
          :index="menu.name"
          :route="{ name: menu.name }"
          :key="menu.id"
          v-else-if="menu.type == 1"
        >
          <i :class="menu.icon"></i>
          <span slot="title">{{ menu.title }}</span>
        </el-menu-item>
      </template>
    </el-menu>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "LayoutSider",
  props: {
    mode: String,
  },
  data() {
    return {
      activeName: "",
    };
  },
  computed: {
    ...mapGetters({
      isCollapse: "siderCollapsed",
      menuTree: "menuTree",
    }),
  },
  methods: {
    setActiveName() {
      if (this.$route.meta.type == 1) {
        this.activeName = this.$route.name;
      }
    },
  },
  watch: {
    $route() {
      this.setActiveName();
    },
  },
  mounted() {
    this.setActiveName();
  },
};
</script>
<style scoped>
.item {
  margin-left: 20px;
}
</style>
