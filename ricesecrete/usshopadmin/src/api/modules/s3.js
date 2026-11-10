import {get} from '@/libs/axios';

import axios from 'axios';

const s3Api = {
  // 获取预签名URL
  getPresignedUrl(folder, filename) {
    return get('/common/s3url', {
       folder, filename
    });
  },

  // 使用预签名URL上传文件
  uploadFile(presignedUrl, file, onProgress) {
    return axios.put(presignedUrl, file, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress: (progressEvent) => {
        if (onProgress && progressEvent.total) {
          const percentage = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          );
          onProgress(percentage);
        }
      },
    });
  },
};

export default s3Api;
