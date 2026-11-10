import config from '@/config'
import CryptoJS from 'crypto-js'
import moment from 'moment'
import 'moment/locale/zh-cn'
moment.locale('zh-cn')

let OSS = require('ali-oss')

export default {
    /**
     * 创建随机字符串
     * @param num
     * @returns {string}
     */
    randomString(num) {
        const chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']
        let res = ''
        for (let i = 0; i < num; i++) {
            var id = Math.ceil(Math.random() * 35)
            res += chars[id]
        }
        return res
    },

    /**
     * 创建oss客户端对象
     * @returns {Promise<any>}
     */
    createClient() {
       

        return new Promise((resolve) => {
            const client = new OSS({
                region: config.sysConfig.ossRegion,
                accessKeyId: config.sysConfig.ossAccessKeyId,
                accessKeySecret: config.sysConfig.ossAccessKeySecret,
                bucket: config.sysConfig.ossBucket
            })
            resolve(client)
        })
    },

    /**
     * 上传文件
     * @param option
     * @returns {Promise<any>}
     */
    uploadFile(option) {
        const file = option.file
        const self = this
        return new Promise((resolve, reject) => {
            const year = moment().format('YYYY')
            const month = moment().format('MM')
            const day = moment().format('DD')
            const dateTime = moment().format('YYYYMMDDhhmmss')
            const randomStr = self.randomString(10)
            const fname = CryptoJS.MD5(dateTime+randomStr);
            const extensionName = file.name.substr(file.name.lastIndexOf('.'))
            const fileName = option.folder + '/'+year+"/"+month+'/'+day+"/" + fname + extensionName
            self.createClient().then(client => {
                client.multipartUpload(fileName, file, {
                    progress: function(p) {
                        const e = {}
                        e.percent = Math.floor(p * 100)
                        option.onProgress(e)
                    }
                }).then((val) => {
                    if (val.res.statusCode === 200) {
                        option.onSuccess(val)
                        resolve({
                            name: file.name,
                            url: config.sysConfig.ossUrl+'/'+val.name
                        })
                    } else {
                        option.onError('上传失败')
                    }
                }, err => {
                    option.onError('上传失败')
                    reject(err)
                })
            })
        })
    },

    /**
     * 删除文件
     * @param object
     * @returns {Promise<any>}
     */
    deleteFile(object) {
        const self = this
        return new Promise((resolve, reject) => {
            self.createClient().then(client => {
                try {
                    let arr = object.split(config.sysConfig.ossUrl+'/');
                    let result = client.delete(arr[1])
                    resolve(result)
                } catch (e) {
                    reject(e)
                }
            })
        })
    }
}
