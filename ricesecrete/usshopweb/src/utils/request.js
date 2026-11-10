import axios from "axios";
import router from "@/router";
import { showToast} from 'vant';
// const baseURL = "/app_index"
const baseURL = "https://api-sheen.esbao.org/app_index"
// const baseURL = "http://192.168.31.59/"
const instance = axios.create({
    baseURL: baseURL,
    // withCredentials: true,
    timeout: 30000
});
instance.interceptors.request.use(

    config => {
        const system = localStorage.getItem('system')
        let token = ''
        if (system) {
            token = JSON.parse(system).token.token
        }
        if (config.method == "get") {
            config.params = { ...config.params, lang: localStorage.getItem('locale'),currency:localStorage.getItem('sys_currency',"USD") }
        } else {
            config.url = config.url + `?lang=${localStorage.getItem('locale')}`+`&currency=${localStorage.getItem('sys_currency',"USD")}`
        }
        config.headers['Client-Type'] = 'AppIndex'
        if (token) {
            config.headers['Authorization'] = token;
        }
        return config
    }

)

instance.interceptors.response.use(
    response => {
        const { code, data, msg } = response.data
        const { config } = response
        switch (code) {
            case 200:
                if (config.successTips) {
                    showToast(msg)
                }
                return data;
            case 1:
                if (config.successTips) {
                    showToast(msg)
                }
                return data;
            case 2001:
            case 2002:
            case 2003:
            case 2004:
            case 2007:
            case 2011:
                localStorage.removeItem("system")
                router.push('/login')
                break;
            default:
                showToast(msg)
                break;

        }

        throw new Error();
    },
    error => {
        return Promise.reject(error);
    }
);

export default instance;