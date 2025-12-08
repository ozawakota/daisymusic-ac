<?php

// エントリーポイントのJavaScriptファイル
define('ENTRY_POINT', 'scripts/index.js');

// JS、CSS、画像、動画の静的コンテンツを取得する際に利用するASSET_URLを環境毎に設定
$host = $_SERVER['HTTP_HOST'];

// 開発環境の時（Vite開発サーバーが起動している場合）
// localsite.io も開発環境として扱う
// if ($host === 'localhost' || substr($host, -6) === '.local' || $host === '127.0.0.1' || strpos($host, 'localsite.io') !== false) {
//     // Vite開発サーバーが起動しているかチェック
//     $vite_server_running = @file_get_contents('http://localhost:5133');

//     if ($vite_server_running !== false) {
//         define('IS_DEV', true);
//         define('ASSET_URI', 'http://localhost:5133');
//     } else {
//         // Vite開発サーバーが起動していない場合はビルド済みアセットを使用
//         define('IS_DEV', false);
//         define('ASSET_URI', get_stylesheet_directory_uri() . '/dist');
//     }

// localsite.io も開発環境として扱う
if ($host === 'localhost' || substr($host, -6) === '.local' || $host === '127.0.0.1' || strpos($host, 'localsite.io') !== false) {
    // Vite開発サーバーが起動しているかチェック
    $vite_server_running = @file_get_contents('http://localhost:5133');

    if ($vite_server_running !== false) {
        define('IS_DEV', true);
        define('ASSET_URI', 'http://localhost:5133');
    } else {
        // Vite開発サーバーが起動していない場合はビルド済みアセットを使用
        define('IS_DEV', false);
        define('ASSET_URI', get_stylesheet_directory_uri() . '/dist');
    }
} else {
    // 本番環境の時
    define('IS_DEV', false);
    define('ASSET_URI', get_stylesheet_directory_uri() . '/dist');
}


add_action('wp_head', 'vite_head_module_hook');
function vite_head_module_hook()
{
    $theme = wp_get_theme();
    $version = $theme->get('Version') || '1.0';

    // 依存配列がある場合追加 例：array('jquery')
    $js_deps = array();
    $css_deps = array();

    // JSからURLを読み込むようにインラインでURLを追加
    echo "<script>window.ASSET_URI = '" . ASSET_URI . "';window.IS_DEV = " . (IS_DEV ? "true" : "false") . ";</script>";

    // 開発環境の時
    if (defined('IS_DEV') && IS_DEV === true) {

        // scripts/index.jsのメインスクリプトをheadに追加
        wp_enqueue_script('not-equal-main', ASSET_URI . "/" . ENTRY_POINT, $js_deps, $version);

    } else {

        // 本番環境の時
        // Viteビルド時に生成されるmanifest.jsonからファイル名を取得
        $manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            if (is_array($manifest)) {
                $entryFile = @$manifest[ENTRY_POINT];
                if ($entryFile && isset($entryFile['file'])) {
                    $js_file = $entryFile['file'];
                    wp_enqueue_script('not-equal-main', ASSET_URI . "/" . $js_file, $js_deps, $version);

                    if (isset($entryFile['css'][0])) {
                        $css_file = $entryFile['css'][0];
                        wp_enqueue_style('not-equal-main-style', ASSET_URI . "/" . $css_file, $css_deps, $version, 'all');
                    }
                }
            }
        }

    }
}

add_filter('script_loader_tag', 'add_module_attribute', 10, 3);
function add_module_attribute($tag, $handle, $src)
{
    if ($handle === 'not-equal-main') {
        // スクリプト内でESModuleを使用しているため、type="module"を付与
        // 開発サーバーの場合、オリジンが異なるため、crossoriginを設定
        $tag = '<script crossorigin type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}