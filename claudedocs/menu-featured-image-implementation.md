# メニュー項目アイキャッチ画像機能 実装ドキュメント

## 概要

WordPressの「外観 > メニュー」から各メニュー項目にアイキャッチ画像を設定し、グローバルナビゲーションで表示できる機能を実装しました。

## 実装ファイル

### 1. バックエンド機能 (`inc/swell_child.php`)

#### `Menu_Item_Featured_Image` クラス
メニュー編集画面での画像設定機能を提供します。

**主な機能:**
- メニュー編集画面にアイキャッチ画像アップロードフィールドを追加
- 選択した画像をメニュー項目のメタデータとして保存
- アイキャッチ画像があるメニュー項目に `has-featured-image` クラスを自動付与
- WordPress標準のメディアアップローダーを使用した直感的な画像選択

**フック:**
- `wp_nav_menu_item_custom_fields`: カスタムフィールド追加
- `wp_update_nav_menu_item`: 画像データ保存
- `nav_menu_css_class`: クラス名追加
- `admin_enqueue_scripts`: メディアアップローダー読み込み

#### `Menu_Featured_Image_Walker` クラス
フロントエンドでのメニュー表示をカスタマイズします。

**主な機能:**
- `Walker_Nav_Menu` を継承したカスタムWalkerクラス
- メニュー項目にアイキャッチ画像を自動挿入
- 画像サイズ: `medium` (最適なパフォーマンスとクオリティのバランス)
- alt属性の自動設定（メタデータまたはメニュータイトル）

### 2. フロントエンド表示 (`lib/pluggable_parts/header_parts.php`)

**修正箇所:**
```php
'walker' => new Menu_Featured_Image_Walker(),
```

`swl_parts__gnav` 関数の `wp_nav_menu` にカスタムWalkerを適用し、アイキャッチ画像表示を有効化。

### 3. スタイリング (`styles/parts/_header.scss`)

#### PCナビゲーション (`.c-gnav`)
```scss
.has-featured-image {
  a {
    .menu-item-featured-image {
      display: block;
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    .ttl {
      display: block;
      margin-top: 8px;
      font-size: 14px;
      text-align: center;
    }
  }
}
```

#### スマートフォンメニュー (`.p-spMenu`)
```scss
&.has-featured-image {
  a {
    display: flex;
    align-items: center;
    gap: 12px;

    .menu-item-featured-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 4px;
      flex-shrink: 0;
    }

    .ttl {
      flex: 1;
    }
  }
}
```

## 使用方法

### 管理画面での設定

1. WordPress管理画面で「外観 > メニュー」を開く
2. メニュー項目を展開（右側の▼をクリック）
3. 新しく追加された「アイキャッチ画像」フィールドを確認
4. 「画像を選択」ボタンをクリック
5. メディアライブラリから画像を選択、または新規アップロード
6. 「この画像を使用」をクリック
7. メニューを保存

### 画像の削除

- 「画像を削除」ボタンをクリック
- メニューを保存

### フロントエンド表示

アイキャッチ画像を設定したメニュー項目は、以下のように表示されます:

**PCナビゲーション:**
- 画像が上部に表示
- メニュータイトルが画像下部に表示（`.ttl` クラス）
- ホバー時の不透明度変更（既存動作を継承）

**スマートフォンメニュー:**
- 画像が左側にサムネイル表示（60x60px、角丸）
- メニュータイトルが右側に表示
- フレキシブルレイアウトで自動調整

## 技術詳細

### データ保存

- **メタキー**: `_menu_item_featured_image`
- **保存形式**: 画像のAttachment ID（整数値）
- **取得関数**: `get_post_meta($item->ID, '_menu_item_featured_image', true)`

### セキュリティ

- `sanitize_text_field()` による入力値のサニタイゼーション
- `esc_url()`, `esc_attr()` によるエスケープ処理
- WordPress標準のnonce検証（`wp_nav_menu` の仕組みを利用）

### パフォーマンス

- 画像サイズは `medium` を使用（大きすぎず小さすぎず）
- alt属性の自動設定で重複クエリを削減
- 条件分岐による不要なコードの実行回避

### 互換性

- WordPress 5.0+
- SWELLテーマ（親テーマ）との完全互換
- 既存のメニュー機能を損なわない実装
- カスタムWalkerクラスによる拡張可能な設計

## カスタマイズポイント

### 画像サイズの変更

```php
// inc/swell_child.php の 232行目
$image_url = wp_get_attachment_image_url($featured_image_id, 'medium');

// 'medium' を以下に変更可能:
// 'thumbnail' (150x150)
// 'medium' (300x300)
// 'large' (1024x1024)
// 'full' (元のサイズ)
```

### PCナビゲーションの画像スタイル調整

```scss
// styles/parts/_header.scss
.menu-item-featured-image {
  width: 80px;           // 固定幅に変更
  height: 80px;          // 固定高さに変更
  border-radius: 50%;    // 円形に変更
}
```

### スマホメニューのサムネイルサイズ変更

```scss
// styles/parts/_header.scss の p-spMenu セクション
.menu-item-featured-image {
  width: 80px;   // 60px から変更
  height: 80px;  // 60px から変更
}
```

## トラブルシューティング

### 画像が表示されない場合

1. メニュー項目に画像が正しく設定されているか確認
2. 画像ファイルがメディアライブラリに存在するか確認
3. ブラウザのキャッシュをクリア
4. CSSがビルドされているか確認（`pnpm build`）

### 管理画面でフィールドが表示されない場合

1. `inc/swell_child.php` が `functions.php` で正しく読み込まれているか確認
2. WordPress、PHP、テーマのエラーログを確認
3. 他のプラグインとの競合を確認（プラグインを一時無効化してテスト）

### スタイルが適用されない場合

1. SCSSがコンパイルされているか確認（`pnpm build` を実行）
2. ブラウザの開発者ツールでCSSクラスが正しく付与されているか確認
3. 既存のテーマCSSとの競合を確認（優先順位の調整が必要な場合あり）

## 今後の拡張案

- サブメニュー項目へのアイキャッチ画像対応
- 画像ホバー効果のカスタマイズオプション
- 画像の配置位置（左/右/上/下）の選択機能
- レスポンシブ画像の最適化（srcset対応）
- 画像のレイジーロード対応

---

**実装日**: 2025-11-26
**バージョン**: 1.0.0
**実装者**: Claude Code (AI Assistant)
