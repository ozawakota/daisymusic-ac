/**
 *  WordPress用Vite設定
 *
 * 参考
 * https://ja.vitejs.dev/guide/backend-integration.html
 * https://github.com/idleberg/php-wordpress-vite-assets
 * https://github.com/andrefelipe/vite-php-setup/blob/master/vite/vite.config.js
 */

import { defineConfig } from "vite";
import { splitVendorChunkPlugin } from "vite";

import { resolve } from "path";

/* ソースのルートフォルダ */
const root = "./";

// 本番環境のベースパスを設定（ 例：/blog ）
const BASE_PATH = "";
const THEME_NAME = "swell_child";

const isDev = process.env.NODE_ENV === "development";

export default defineConfig({
  plugins: [
    splitVendorChunkPlugin(),
  ],
  root,
  base: isDev ? "/" : `${BASE_PATH}/wp-content/themes/${THEME_NAME}/dist/`,
  publicDir: resolve(root, "public"),

  // 本番用コードのビルド設定
  build: {
    assetsDir: "", // assetsフォルダの命名（""はassetsフォルダを作成しない）
    outDir: resolve(root, "dist"), // distフォルダにビルドした資材を配置
    emptyOutDir: true, // ビルド時にdistフォルダを空にする
    manifest: true, // wordpressとの統合にはmanifestファイルが必要
    target: "es2018", // ビルドターゲット
    minify: true, // ファイル圧縮
    chunkSizeWarningLimit: 1000, // チャンクサイズのリミット 1000kb
    rollupOptions: {
      // ビルドのエントリーポイント
      // - phpから直接読み込むファイルを指定
      // - ダイナミックインポートは指定不要
      input: {
        index: resolve(root, "scripts", "index.js"),
        loader: resolve(root, "styles", "style.scss"),
      },

      /* 出力ファイルの名前を指定したい場合
      output: {
          entryFileNames: `[name].js`,
          chunkFileNames: `[name].js`,
          assetFileNames: `[name].[ext]`
      }*/
    },
  },

  // 開発サーバの設定
  server: {
    cors: true, // CORSをAccess-Control-Allow-Origin: *とする
    strictPort: true, // ポート番号を固定
    port: 5133, // 開発サーバのポート
  },
  resolve: {
    alias: [
      {
        find: "#",
        replacement: "/scripts",
      },
    ],
  },
});
