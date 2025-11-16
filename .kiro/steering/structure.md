# Project Structure

## Organization Philosophy

**WordPressテーマ構造 + モダンフロントエンド開発**  
テンプレートファイルとアセット管理の分離、部分テンプレートによる段階的カスタマイズ

## Directory Patterns

### **Root Level Templates**
**Location**: `/`  
**Purpose**: WordPressテーマファイル（index.php, functions.php, style.css）  
**Example**: `functions.php`（テーマ機能拡張）, `home.php`（フロントページテンプレート）

### **Partial Templates**
**Location**: `/parts/`  
**Purpose**: 再利用可能なテンプレート部品（header/footer/content要素）  
**Example**: `parts/top/main_visual.php`（メインビジュアル）, `parts/header/header_contents.php`

### **Asset Sources**
**Location**: `/scripts/`, `/styles/`  
**Purpose**: ビルド前のJavaScript/SCSSソース  
**Example**: `scripts/index.js`（エントリーポイント）, `styles/style.scss`（メインスタイル）

### **Compiled Assets**
**Location**: `/dist/`  
**Purpose**: Viteでビルドされたプロダクション用アセット  
**Example**: `dist/index.js`（ビルド済みJS）, `dist/style.css`（コンパイル済みCSS）

### **Configuration & Utilities**
**Location**: `/inc/`  
**Purpose**: テーマ機能拡張・Vite統合ユーティリティ  
**Example**: `inc/vite.php`（Vite統合）, `inc/swell_child.php`（独自機能）

## Naming Conventions

- **PHP Templates**: WordPress標準（snake_case）- `main_visual.php`
- **SCSS Files**: アンダースコア接頭辞（`_main.scss`, `_variables.scss`）
- **JavaScript**: ES Modules（camelCase変数、PascalCase Classes）
- **Directories**: kebab-case 推奨（`post_list/`, `top/`）

## Import Organization

```scss
// SCSS Import Pattern
@import "globals/variables";    // グローバル設定
@import "globals/functions";    // 関数・mixins
@import "parts/main";          // コンポーネントスタイル
@import "pages/home";          // ページ固有スタイル
```

```javascript
// JavaScript Import Pattern
import "#/helper/config";       // 設定・ヘルパー
import { Component } from "#/component/";  // コンポーネント
```

**Path Aliases**:
- `#/`: `/scripts` directory mapping

## Code Organization Principles

- **Template Hierarchy**: WordPressファイル優先度に準拠
- **Progressive Enhancement**: 基本機能→追加スタイル→インタラクションの順序
- **Component Isolation**: 各部分テンプレートは独立性を保つ
- **Asset Bundling**: 開発時分割、本番時最適化（Vite）
- **Backwards Compatibility**: 親テーマ（SWELL）機能の継承・拡張

---
_Document patterns, not file trees. New files following patterns shouldn't require updates_