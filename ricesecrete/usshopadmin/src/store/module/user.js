import config from "@/config";
import account from "@/api/modules/account";
import common from "@/api/modules/common";
import router from "@/router";
import { setStorage, getStorage,isEmpty } from "@/libs/util";

export default {
  state: {
    access_token: getStorage(config.accessTokenKey),
    refresh_token: getStorage(config.refreshTokenKey),
    key_iv: getStorage(config.keyIv),
    realname: "",
    avatar: "",
    menuTree: [],
    permission: [],
    langList:getStorage(config.langList,true)
  },
  mutations: {
    setAccessToken(state, access_token) {
      state.access_token = access_token;
      setStorage(config.accessTokenKey, access_token);
    },
    setRefreshToken(state, refresh_token) {
      state.refresh_token = refresh_token;
      setStorage(config.refreshTokenKey, refresh_token);
    },
    setKeyIv(state, key_iv) {
      state.key_iv = key_iv;
      setStorage(config.keyIv, key_iv);
    },
    setRealname(state, realname) {
      state.realname = realname;
    },
    setAvatar(state, avatar) {
      state.avatar = avatar;
    },
    setMenuTree(state, menuTree) {
      state.menuTree = menuTree;
    },
    setPermission(state, permission) {
      state.permission = permission;
    },
    setLangList(state, langList) {
      state.langList = langList;
      setStorage(config.langList, langList,true);
    },
  },
  getters: {
    access_token: (state) => state.access_token,
    refresh_token: (state) => state.refresh_token,
    key_iv: (state) => state.key_iv,
    realname: (state) => state.realname,
    avatar: (state) => state.avatar,
    menuTree: (state) => state.menuTree,
    permission: (state) => state.permission,
    langList: (state) => state.langList,
  },
  actions: {
    getConfig({ commit }) {
      if (localStorage.getItem("lang_list_update")&&(isEmpty(this.access_token))) {
        return;
      }
      // https://api.sheens.shop/app_admin/common/config
      return new Promise((resolve, reject) => {
        common
          .config()
          .then((res) => {
            if (res.code == 1) {
              const data = res.data;
              if (data) {
                commit("setLangList", data.lang_list);
                localStorage.setItem("lang_data", JSON.stringify(data.lang));
                localStorage.setItem("langCacheTime", data.langCacheTime);
                localStorage.setItem("lang_list_update", 1);
              }
            }
            resolve(res);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },
    // 获取用户信息
    getProfile({ commit }) {
      return new Promise((resolve, reject) => {
        account
          .profile()
          .then((res) => {
            if (res.code == 1) {
              const data = res.data;
              commit("setRealname", data.realname);
              commit("setAvatar", data.avatar);
              commit("setMenuTree", data.menuTree);
              commit("setPermission", data.permission);
              commit("setLangList", data.lang_list);
              config.sysConfig = data.sysConfig;
              if (
                config.sysConfig.langCacheTime !=
                localStorage.getItem("langCacheTime")
              ) {
                localStorage.removeItem("lang_list_update");
              }
            }
            resolve(res);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    // 刷新token
    handleRefreshToken({ commit }, refresh_token) {
      return new Promise((resolve, reject) => {
        account
          .token(refresh_token)
          .then((res) => {
            if (res.code == 1) {
              const data = res.data;
              commit("setAccessToken", data.access_token);
              commit("setRefreshToken", data.refresh_token);
            }
            resolve(res);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },

    // 账号登录
    handleLogin({ commit }, { username, password, captcha }) {
      return new Promise((resolve, reject) => {
        account
          .login({ username, password, captcha })
          .then((res) => {
            if (res.code == 1) {
              const data = res.data;
              commit("setAccessToken", data.access_token);
              commit("setRefreshToken", data.refresh_token);
              commit("setKeyIv", data.key_iv);
            }
            resolve(res);
          })
          .catch((err) => {
            reject(err);
          });
      });
    },
    // 退出登录
    handleLogOut(isReset = true) {
      if (isReset) {
        return new Promise((resolve, reject) => {
          account
            .logout()
            .then((res) => {
              if (res.code == 1) {
                this.dispatch("handleReset");
              }
              resolve(res);
            })
            .catch((err) => {
              reject(err);
            });
        });
      } else {
        return this.dispatch("handleReset");
      }
    },

    handleReset({ commit }) {
      return new Promise((resolve) => {
        commit("setAccessToken", null);
        commit("setRefreshToken", null);
        commit("setRealname", "");
        commit("setAvatar", "");
        commit("setMenuTree", []);
        commit("setPermission", []);
        router.push({ name: "login" }).catch(() => {});
        resolve();
      });
    },
  },
};
