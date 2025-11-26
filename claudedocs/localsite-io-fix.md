# Localsite.io 404エラー修正ドキュメント

## 問題

Localツールのlive link (`measly-afterthought.localsite.io`) でアセット（CSS/JS）が404エラーになる。

## 原因

1. **manifest.jsonのパスが間違っていた**
   - ❌ `/dist/manifest.json`
   - ✅ `/dist/.vite/manifest.json`

2. **親テーマと子テーマのパス混同**
   - ❌ `get_template_directory_uri()` → 親テーマ（SWELL）のパス
   - ✅ `get_stylesheet_directory_uri()` → 子テーマ（swell_child）のパス

3. **localsite.io ドメインが開発環境として認識されていなかった**

## 修正内容

### 1. `inc/vite.php` の修正

#### 環境判定ロジックの改善

**修正前:**
```php
if ($host === 'localhost' || substr($host, -6) === '.local' || $host === '127.0.0.1') {
    define('IS_DEV', true);
    define('ASSET_URI', 'http://localhost:5133');
} else {
    define('IS_DEV', false);
    define('ASSET_URI', get_template_directory_uri() . '/dist');  // ❌ 親テーマ
}
```

**修正後:**
```php
if ($host === 'localhost' || substr($host, -6) === '.local' || $host === '127.0.0.1' || strpos($host, 'localsite.io') !== false) {
    // Vite開発サーバーが起動しているかチェック
    $vite_server_running = @file_get_contents('http://localhost:5133');

    if ($vite_server_running !== false) {
        define('IS_DEV', true);
        define('ASSET_URI', 'http://localhost:5133');
    } else {
        // Vite開発サーバーが起動していない場合はビルド済みアセットを使用
        define('IS_DEV', false);
        define('ASSET_URI', get_stylesheet_directory_uri() . '/dist');  // ✅ 子テーマ
    }
} else {
    define('IS_DEV', false);
    define('ASSET_URI', get_stylesheet_directory_uri() . '/dist');  // ✅ 子テーマ
}
```

**改善点:**
- `localsite.io` ドメインを開発環境として認識
- Vite開発サーバーの起動状態を自動チェック
- 子テーマのパス（`get_stylesheet_directory_uri()`）を使用

#### manifest.jsonパスの修正

**修正前:**
```php
$manifest = json_decode(file_get_contents(get_template_directory() . '/dist/manifest.json'), true);
```

**修正後:**
```php
$manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';

if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (is_array($manifest)) {
        $entryFile = @$manifest[ENTRY_POINT];
        if ($entryFile && isset($entryFile['file'])) {
            // アセット読み込み
        }
    }
}
```

**改善点:**
- 正しいmanifest.jsonパス（`/dist/.vite/manifest.json`）
- ファイル存在チェックを追加
- より堅牢なエラーハンドリング

## 動作フロー

### Localsite.io でアクセスした場合

1. **ホスト判定**: `measly-afterthought.localsite.io` → `localsite.io`を含む
2. **Vite開発サーバーチェック**: `http://localhost:5133` にアクセス試行
3. **分岐**:
   - Vite起動中 → `http://localhost:5133/scripts/index.js` を読み込み（HMR有効）
   - Vite停止中 → `https://measly-afterthought.localsite.io/wp-content/themes/swell_child/dist/index-[hash].js` を読み込み

### 実際のアセットURL

**開発時（Vite起動中）:**
```
http://localhost:5133/scripts/index.js
http://localhost:5133/@vite/client
```

**ビルド済み（Vite停止中）:**
```
https://measly-afterthought.localsite.io/wp-content/themes/swell_child/dist/index-D5VrJR69.js
https://measly-afterthought.localsite.io/wp-content/themes/swell_child/dist/loader-DiVM3B6E.css
https://measly-afterthought.localsite.io/wp-content/themes/swell_child/dist/img/gnav-img01.png
```

## 確認方法

### 1. ビルド実行

```bash
cd /Users/kouta.ozawa/Local\ Sites/daisymusic-ac/app/public/wp-content/themes/swell_child
pnpm build
```

### 2. ブラウザで確認

1. `https://measly-afterthought.localsite.io` にアクセス
2. 開発者ツール（F12）→ Networkタブ
3. 以下のファイルが200ステータスで読み込まれているか確認:
   - `index-[hash].js`
   - `loader-[hash].css`
   - `img/*` （画像ファイル）

### 3. エラーがある場合

**404エラーの場合:**
- パスが正しいか確認: `/wp-content/themes/swell_child/dist/`
- manifest.jsonが存在するか確認: `/dist/.vite/manifest.json`

**CORSエラーの場合:**
- Vite開発サーバーが起動している場合に発生
- `pnpm build` でビルド済みアセットを使用する

## トラブルシューティング

### アセットが読み込まれない

```bash
# 1. distフォルダを確認
ls -la /Users/kouta.ozawa/Local\ Sites/daisymusic-ac/app/public/wp-content/themes/swell_child/dist/

# 2. manifest.jsonを確認
cat /Users/kouta.ozawa/Local\ Sites/daisymusic-ac/app/public/wp-content/themes/swell_child/dist/.vite/manifest.json

# 3. 再ビルド
pnpm build
```

### キャッシュクリア

1. ブラウザのハードリロード（Cmd+Shift+R / Ctrl+Shift+R）
2. WordPress側: WP Super Cache等のキャッシュプラグインをクリア
3. ビルドキャッシュクリア: `rm -rf dist && pnpm build`

## まとめ

✅ `localsite.io` ドメインで開発環境として認識
✅ manifest.json の正しいパス（`/dist/.vite/manifest.json`）
✅ 子テーマパスの使用（`get_stylesheet_directory_uri()`）
✅ Vite開発サーバーの自動検出
✅ 堅牢なエラーハンドリング

これで、`measly-afterthought.localsite.io` でアセットが正しく読み込まれるようになります。

---

**修正日**: 2025-11-26
**対象ファイル**: `inc/vite.php`
