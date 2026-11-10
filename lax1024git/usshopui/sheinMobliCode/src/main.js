import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import 'vant/lib/index.css';
import './assets/tailwind.css';
import Vant from 'vant';
import router from './router';
import i18n from './locales';
import { getImagePath } from './utils/utils.js';

const baseWidth = 375; // 设计稿基准宽度
const maxWidth = 425; // 最大宽度限制
function setHtmlFontSize() {
    const screenWidth = Math.min(window.innerWidth, maxWidth); // 限制最大宽度
    const fontSize = (screenWidth / baseWidth) * 10; // 设计稿等分为10份
    document.documentElement.style.fontSize = `${fontSize}px`;

}

// 初始化设置字体大小
setHtmlFontSize();
// 监听窗口变化动态调整
window.addEventListener('resize', setHtmlFontSize);

const app = createApp(App);
app.config.globalProperties.$getImagePath = getImagePath;
app.use(Vant).use(router).use(i18n).mount('#app')
