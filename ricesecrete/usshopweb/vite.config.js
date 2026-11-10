import vue from "@vitejs/plugin-vue";
import * as path from "path";
import { defineConfig, loadEnv } from "vite";

export default ({  mode }) => {
  const env = loadEnv(mode, process.cwd(), "");
  return defineConfig({
    plugins: [vue()],
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "./src"),
      },
    },
    server: {
      host: true,
      cors: true,
      proxy: {
        "/app_index": {
          target: env.VITE_API,
          changeOrigin: true,
          secure: false,
          rewrite: (path) => path.replace(/^\/app_index/, ""),
        },
      },
    },
    build: {
      minify: 'terser', // 使用 Terser 进行压缩
      terserOptions: {
        output: {
          comments: false, // 去除所有注释
        },
        compress: {
          drop_console: true, // 去除所有 console.* 语句
          drop_debugger: true, // 去除 debugger
          // 其他可选的压缩选项
          unused: true, // 删除未使用的变量和函数
          dead_code: true, // 删除死代码
          // additional optimizations can be configured as needed
        },
        mangle: true, // 启用名称混淆
      },
    },
  });
};