import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import 'vant/lib/index.css';
import './assets/tailwind.css';
import Vant from 'vant';
import pinia from './store/index.js';
import router from './router';
import { getImagePath } from './utils/utils.js';
import { LocalesService } from '@/api/locales'
import { createI18n } from 'vue-i18n';
import { sysCurrency } from './utils/global';
import { dataBase } from "./utils/databaseBusiness";
import DataBaseStorage from "./storage/DataBaseStorage";
import { UserService } from './api/user.js';

const baseWidth = 375; // 设计稿基准宽度
const maxWidth = 425; // 最大宽度限制
function setHtmlFontSize() {
    const screenWidth = Math.min(window.innerWidth, maxWidth); // 限制最大宽度
    const fontSize = (screenWidth / baseWidth) * 10; // 8.824设计稿等分为10份
    document.documentElement.style.fontSize = `${fontSize}px`;

}
const str = window.location.href;
// 正则表达式匹配 lang 参数
const regex = /[?&]lang=([^&]+)/;  // 匹配 'lang=' 后面的值，直到遇到 & 或字符串结束
const match = str.match(regex);
let lang;
let SystemConfig;
(async () => {
    const res = await UserService.systemConfig()
    let sys = localStorage.getItem('system')
    SystemConfig = res;
    let currency_code = "USD";
    if (sys) {
        sys = JSON.parse(sys)
        if(typeof sys.userInfo.currency_info == undefined){
            sysCurrency.value = res.currency_symbol
            currency_code = res.currency_code
        } else {
            sysCurrency.value = sys.userInfo.currency_info.symbol
            currency_code = sys.userInfo.currency_info.code
        }
    } else {
        sysCurrency.value = res.currency_symbol
    }
    // 设置系统货币
    localStorage.setItem('sys_currency',currency_code)

    if (match && match[1]) {
        lang = match[1];
    }
    if (lang) {
        console.log(lang)
        localStorage.setItem("locale", lang)
    } else {
        let locale = localStorage.getItem("locale")
        if (!locale) {
            localStorage.setItem("locale", res.lang)
        }
    }
    let systemLocal = localStorage.getItem("systemLocal")
    if (!systemLocal) {
        localStorage.setItem("systemLocal", JSON.stringify(res))
    }

    init()
})()



// 初始化设置字体大小
setHtmlFontSize();
// 监听窗口变化动态调整
window.addEventListener('resize', setHtmlFontSize);
const i18n = createI18n({
    locale: localStorage.getItem("locale"),
    fallbackLocale: localStorage.getItem("locale"),
    messages: {},
    legacy: false,
    // missing: async(locale, key) => {
    //     await LocalesService.updateLang({"title":key})
    //     console.log(key)
    // }
});

const fetLangWrap = async () => {
    try {
        let locales = localStorage.getItem("locales")
        if (locales) {
            let systemLocal = localStorage.getItem("systemLocal");
            if (systemLocal) {
                systemLocal = JSON.parse(systemLocal)
                if (!systemLocal.lang_cache_time == SystemConfig.lang_cache_time) {
                    const response = await LocalesService.translate()
                    localStorage.setItem("locales", JSON.stringify(response))
                    localStorage.setItem("systemLocal", JSON.stringify(SystemConfig))
                    updateLang(response)
                }
            }
        } else {
            const response = await LocalesService.translate()
            localStorage.setItem("locales", JSON.stringify(response))
            updateLang(response)
        }

        // Object.keys(response).forEach(lang => {
        //     i18n.global.setLocaleMessage(lang, response[lang]);
        // });
    } catch (error) {
    }
}
const updateLang = async(response) => {
    if (response) {
        Object.keys(response).forEach(lang => {
            i18n.global.setLocaleMessage(lang, response[lang]);
        });
    }
}

const init = async () => {
    let response = localStorage.getItem("locales")
    if (response) {
        updateLang(JSON.parse(response))
    }
    await fetLangWrap()
    await dataBase.open();
    await DataBaseStorage.init();

    const app = createApp(App);
    app.config.globalProperties.$getImagePath = getImagePath;
    app.config.globalProperties.$sysCurrency = sysCurrency;
    app.use(Vant).use(router).use(pinia).use(i18n).mount('#app')
}

