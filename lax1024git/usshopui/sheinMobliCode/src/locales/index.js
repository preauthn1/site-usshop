import { createI18n } from 'vue-i18n'
import en from './en'
import cn from './cn'

const messages = { en,cn };
const i18n = createI18n({
  locale: 'cn', 
  fallbackLocale: 'cn',
  messages,
  legacy: false
});
export default i18n;