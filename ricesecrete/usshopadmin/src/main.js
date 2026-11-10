import Vue from "vue";

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";

import Viewer from "v-viewer";
import "viewerjs/dist/viewer.css";
import enlocale from "element-ui/lib/locale/lang/en";
import zhlocale from "element-ui/lib/locale/lang/zh-CN";
let admin_lang = localStorage.getItem("admin_lang");
if (isEmpty(admin_lang)) {
  localStorage.setItem("admin_lang", "en");
  admin_lang = "en";
}
let locale = zhlocale;
if (admin_lang != "zh-cn") {
  locale = enlocale;
}
Vue.use(ElementUI, { locale });
import moment from "moment";
import "moment/locale/zh-cn";
moment.locale("zh-cn");

import App from "@/App.vue";
import router from "@/router/index";
import store from "@/store/index";
import config from "@/config/index";
import api from "@/api/index";
import { hasPermission } from "@/libs/util";
import VueAMap from "vue-amap";
import VueI18n from "vue-i18n";
import { isEmpty } from "xe-utils";
import { MD5 } from "crypto-js";

Vue.use(VueAMap);

// 使用 vue-i18n
Vue.use(VueI18n);
let lang = localStorage.getItem("lang_data");
if (!isEmpty(lang)) {
  lang = JSON.parse(lang);
} else {
  lang = {
    en: {},
    zh: {},
  };
  api.common
  .config()
  .then((res) => {
    if (res.code == 1) {
      lang = res.data.lang;
      localStorage.setItem("lang_data", JSON.stringify(lang));
      
      window.location.reload();
    }
  });
}

const messages = lang;
const i18n = new VueI18n({
  locale: admin_lang ?? "en", // 设置默认语言
  messages, // 设置语言包
  silentTranslationWarn: false, // 允许控制台输出警告信息
  missing: (locale, key) => {
    console.log(`Translation missing for key: ${key} in locale: ${locale}`);
    if (config.sysConfig.isUploadLang) {
      let key_md5 = MD5(key);
      let temp_key = localStorage.getItem(key_md5);
      if (temp_key) {
        console.log(temp_key);
      } else {
        api.common
          .updateLang(key)
          .then((res) => {
            if (res.code == 1) {
              localStorage.setItem(key_md5, key);
              console.log(`add: ${key}`);
            }
          })
          .catch((err) => {
            console.log(`errer: ${err}`);
          });
      }
    }
  },
  // missingHandler: (locale, key) => {
  //   console.log(`Translation missing for key: ${key} in locale: ${locale}`);
  //   // 你可以在这里记录日志或执行其他操作

  // },
});

Vue.config.productionTip = false;
Vue.prototype.$config = config;
Vue.prototype.$moment = moment;
Vue.prototype.$api = api;
Vue.prototype.hasPerm = hasPermission;
ElementUI.Dialog.props.closeOnClickModal.default = false;
ElementUI.Dialog.props.destroyOnClose.default = true;
Vue.use(ElementUI);
Vue.use(Viewer, {
  defaultOptions: {
    zIndex: 9999,
    url: "data-src",
  },
});

new Vue({
  i18n,
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
