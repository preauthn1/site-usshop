import CryptoJS from 'crypto-js'
import config from '@/config'
import store from "@/store";

// const key_iv = "O6r0sXtEiqJWK2Npghsf2fNNAcQsCRrhJfgYUh1oHGv8tibyb5j2fc/lPz5HUmYs";
// const key_iv = config.key_iv;

//秘钥
const key = CryptoJS.enc.Utf8.parse(config.aes.key)
//秘钥偏移量
const iv = CryptoJS.enc.Utf8.parse(config.aes.iv)

//秘钥
let web_key = ""
//秘钥偏移量
let web_iv = ""


/**
 * aes 加密方法
 * @param word
 * @returns {string}
 * @constructor
 */
export function WebAesEncrypt (word){
    AesDecryptKey(store.getters.key_iv);
    let encrypted = CryptoJS.AES.encrypt(word, web_key, {
        iv: web_iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    return encrypted.toString()
}

/**
 * aes 解密方法
 * @param word
 * @returns {string}
 * @constructor
 */
export function WebAesDecrypt (word){
    AesDecryptKey(store.getters.key_iv);
    let decrypt = CryptoJS.AES.decrypt(word, web_key, {
        iv: web_iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    let decryptedStr = decrypt.toString(CryptoJS.enc.Utf8)
    return decryptedStr.toString()
}

/**
 * aes 加密方法
 * @param word
 * @returns {string}
 * @constructor
 */
export function AesEncrypt (word){
    let encrypted = CryptoJS.AES.encrypt(word, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    return encrypted.toString()
}

/**
 * aes 解密方法
 * @param word
 * @returns {string}
 * @constructor
 */
export function AesDecrypt (word){
    let decrypt = CryptoJS.AES.decrypt(word, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    let decryptedStr = decrypt.toString(CryptoJS.enc.Utf8)
    return decryptedStr.toString()
}

/**
 * aesKey 解密方法
 * @param word
 * @returns {string}
 * @constructor
 */
export function AesDecryptKey (word){
    let decrypt = CryptoJS.AES.decrypt(word, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    })
    let decryptedStr = decrypt.toString(CryptoJS.enc.Utf8)
    let key_arr = decryptedStr.split("_");
    web_key = CryptoJS.enc.Utf8.parse(key_arr[0]);
    web_iv = CryptoJS.enc.Utf8.parse(key_arr[1]);
    return decryptedStr.toString()
}

/**
 * base64 加密方法
 * @param val
 * @returns {*|string}
 * @constructor
 */
export function Base64Encode (val){
    let str = CryptoJS.enc.Utf8.parse(val)
    return CryptoJS.enc.Base64.stringify(str)
}

/**
 * base64 解密方法
 * @param val
 * @returns {string}
 * @constructor
 */
export function Base64Decode(val){
    let words = CryptoJS.enc.Base64.parse(val)
    return words.toString(CryptoJS.enc.Utf8)
}