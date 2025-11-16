# Technology Stack

## Architecture

WordPressテーマ子テーマアーキテクチャ - SWELLテーマ拡張によるカスタマイズ志向

## Core Technologies

- **Language**: PHP 8.0+ (WordPress), JavaScript ES2018, SCSS
- **Framework**: WordPress 6.0+, SWELLテーマ親テーマ
- **Build System**: Vite 6.3+ (フロントエンドビルド)
- **Runtime**: Node.js 20.5.0 (Volta管理)

## Key Libraries

- **GSAP 3.13**: アニメーション・インタラクション
- **TailwindCSS 4.1**: ユーティリティファーストCSS
- **Autoprefixer 10.4**: CSS互換性自動付与
- **Detect Browser**: ブラウザ固有処理

## Development Standards

### Theme Structure
- WordPressテーマファイルヒエラルキー準拠
- 子テーマパターン（親テーマ機能継承）
- `parts/`ディレクトリによる部分テンプレート管理

### Code Quality
- SCSS構造化（globals/pages/parts分離）
- CSS Declaration Sorter（プロパティ自動並び替え）
- クロスブラウザ対応（iOS 10.0+, モダンブラウザ）

### Asset Management
- Viteマニフェスト連携
- 開発/本番環境分離
- チャンクサイズ最適化（1000kb制限）

## Development Environment

### Required Tools
- Node.js 20.5.0 (Volta)
- PNPM 10.12+ (パッケージ管理)
- PHP 8.0+ (WordPress互換性)

### Common Commands
```bash
# Dev: pnpm dev (Vite開発サーバ:5133)
# Build: pnpm build (本番用アセットビルド)
# Preview: pnpm preview (ビルド結果プレビュー)
```

## Key Technical Decisions

- **Vite統合**: WordPressとのHMR連携によるDX向上
- **SCSS採用**: CSS設計パターンとコンポーネント管理
- **部分テンプレート方式**: WordPressテーマカスタマイズのベストプラクティス
- **TailwindCSS導入**: ユーティリティクラス活用による効率的なスタイリング

---
_Document standards and patterns, not every dependency_