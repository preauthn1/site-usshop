import Cookies from "js-cookie";
import config from "@/config";
import store from "@/store";

/**
 * 检测是否为空
 * @param str
 * @returns {boolean}
 */
export const isEmpty = (str) => {
  if (str == "" || str == null || typeof str == "undefined") {
    return true;
  }
  return false;
};

/**
 * 获取接口地址
 * @returns {string}
 */
export const getApiUrl = () => {
  let url = "";
  // window.console.log(process.env);
  switch (process.env.NODE_ENV) {
    case "development": {
      url = config.apiUrl.dev;
      break;
    }
    case "debug": {
      url = config.apiUrl.debug;
      break;
    }
    case "production": {
      url = config.apiUrl.pro;
      break;
    }
  }
  return url;
};

//获取url文件类型
export const getFileTypeFromUrl = (url) => {
  const parsedUrl = new URL(url);
  const path = parsedUrl.pathname;
  const match = path.match(/\.(\w+)$/);
  return match ? match[1] : null;
};
/**
 * 设置缓存
 * @param key
 * @param data
 * @param isObject
 */
export const setStorage = (key, data, isObject = false) => {
  if (isObject && typeof data == "object" && data) {
    localStorage.setItem(key, JSON.stringify(data));
  } else if (!isEmpty(data)) {
    localStorage.setItem(key, data);
  } else {
    localStorage.removeItem(key);
  }
};

/**
 * 获取缓存
 * @param key
 * @param isObject
 * @returns {*}
 */
export const getStorage = (key, isObject = false) => {
  const data = localStorage.getItem(key);
  if (data) {
    return isObject ? JSON.parse(data) : data;
  } else {
    return false;
  }
};

// 设置cookie
export const setCookie = (key, value) => {
  if (!isEmpty(value)) {
    Cookies.set(key, value, { expires: config.cookieExpires });
  } else {
    Cookies.remove(key);
  }
};

// 获取cookie
export const getCookie = (key) => {
  const value = Cookies.get(key);
  if (!isEmpty(value)) {
    return value;
  } else {
    return false;
  }
};

/**
 * 权限检测
 * @param name
 * @returns {boolean}
 */
export const hasPermission = (name) => {
  const permission = store.getters.permission;
  if (typeof permission == "object" && permission) {
    const index = permission.findIndex((item) => {
      return item.name === name;
    });
    return index > -1;
  } else {
    return false;
  }
};

/**
 * 查找所有父级节点
 * @param node
 * @param tree
 * @param parentNodes
 * @param index
 * @returns {*}
 */
export function findTreeAllParent(node, tree, parentNodes = [], index = 0) {
  if (!node || node.parent_id === 0) {
    return;
  }
  findTreeParent(node, parentNodes, tree);
  let parentNode = parentNodes[index];
  findTreeAllParent(parentNode, tree, parentNodes, ++index);
  return parentNodes;
}

/**
 * 查找父级节点
 * @param node
 * @param parentNodes
 * @param tree
 */
export function findTreeParent(node, parentNodes, tree) {
  for (let i = 0; i < tree.length; i++) {
    let item = tree[i];
    if (item.id === node.parent_id) {
      parentNodes.push(item);
      return;
    }
    if (item.children && item.children.length > 0) {
      findTreeParent(node, parentNodes, item.children);
    }
  }
}

function add0(m) {
  return m < 10 ? "0" + m : m;
}

export function formatTime(shijianchuo, all = false, text = false) {
  //shijianchuo是整数，否则要parseInt转换
  var time = new Date(shijianchuo);
  var y = time.getFullYear();
  var m = time.getMonth() + 1;
  var d = time.getDate();
  var h = time.getHours();
  var mm = time.getMinutes();
  var s = time.getSeconds();
  var timestamp = time.getTime();
  // return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
  if (!all) {
    if (text) {
      return y + "年" + add0(m) + "月" + add0(d) + "日";
    }
    return y + "-" + add0(m) + "-" + add0(d);
  } else {
    let objTime = {
      y,
      m: add0(m),
      d: add0(d),
      h: add0(h),
      mm: add0(mm),
      s: add0(s),
      timestamp,
    };
    return objTime;
  }
}

/**
 * 查找父级节点
 * @param node
 * @param parentNodes
 * @param tree
 */
export function getUrlArr(str, name = "photo") {
  let photo = str ? str.split(",") : [];
  let fileList = [];
  for (var i = 0; i < photo.length; i++) {
    fileList.push({
      name: name + "+" + i,
      type: photo[i].split(".").pop().toLowerCase(),
      url: photo[i],
    }); //相册
  }
  return fileList;
}
