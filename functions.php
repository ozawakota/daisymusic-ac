<?php

/* 子テーマのfunctions.phpは、親テーマのfunctions.phpより先に読み込まれることに注意してください。 */
// Viteとの統合のための設定読み込み
include "inc/vite.php";

// swell_childで使用する関数
include "inc/swell_child.php";


/**
 * 親テーマのfunctions.phpのあとで読み込みたいコードはこの中に。
 */
// add_filter('after_setup_theme', function(){
// }, 11);


/**
 * 子テーマでのファイルの読み込み
 */
add_action('wp_enqueue_scripts', function() {
	
	$timestamp = date( 'Ymdgis', filemtime( get_stylesheet_directory() . '/style.css' ) );
	wp_enqueue_style( 'child_style', get_stylesheet_directory_uri() .'/style.css', [], $timestamp );

	/* その他の読み込みファイルはこの下に記述 */
	function my_body_class($classes)
{
    if (is_page()) {
        $page = get_post();
        $classes[] = $page->post_name;
    }
    return $classes;
}
add_filter('body_class', 'my_body_class');
	

}, 11);

/**
 * メニュータイトルから[br]と[spbr]を削除
 */
// add_filter('nav_menu_item_title', function($title, $item, $args, $depth) {
// 	return str_replace(array('[br]', '[spbr]'), '', $title);
// }, 10, 4);

/**
 * カスタムリンクでURLが空の場合、リンクではなくテキストのみ表示
 */
add_filter('nav_menu_link_attributes', function($atts, $item, $args, $depth) {
	// URLが空（#のみ含む）の場合
	if (empty($item->url) || $item->url === '#' || $item->url === '') {
		// data属性を追加してCSSで制御できるようにする
		$atts['data-no-link'] = 'true';
		// クリック無効化
		$atts['onclick'] = 'return false;';
		// カーソルをデフォルトに
		$atts['style'] = isset($atts['style']) ? $atts['style'] . ' cursor: default;' : 'cursor: default;';
	}
	return $atts;
}, 10, 4);

/**
 * 新ソルフェージュ指導法講座ページでSwiperを読み込む
 */
add_action('wp_enqueue_scripts', function() {
	// 新ソルフェージュ指導法講座ページ（スラッグで判定）
	if (is_page('solfege-seminar') || is_page('piano-lesson') || is_page('media') || is_page('history')) {
		// SWELLテーマで登録されているSwiperを読み込む
		wp_enqueue_style('swell_swiper');
		wp_enqueue_script('swell_swiper');
	}

	// お問い合わせページでContact Form 7の修正スクリプトを読み込む
	if (is_page('contact')) {
		wp_enqueue_script(
			'contact-form-fix',
			get_stylesheet_directory_uri() . '/scripts/contact-form-fix.js',
			array(),
			filemtime(get_stylesheet_directory() . '/scripts/contact-form-fix.js'),
			true
		);
	}
}, 20); // SWELLテーマの後に実行

/**
 * Contact Form 7 APIエンドポイントを現在のドメインに動的に設定
 */
add_filter('wpcf7_load_js', function() {
	// Contact Form 7のJavaScript設定を現在のサイトURLで上書き
	if (wp_script_is('contact-form-7', 'enqueued')) {
		wp_add_inline_script('contact-form-7',
			'var wpcf7 = {"api":{"root":"' . esc_url_raw(rest_url()) . '","namespace":"contact-form-7/v1"}};',
			'before'
		);
	}
	return true;
});

/**
 * Contact Form 7のスクリプトデータをローカライズ
 */
add_action('wp_enqueue_scripts', function() {
	if (wp_script_is('contact-form-7', 'enqueued')) {
		wp_localize_script('contact-form-7', 'wpcf7', array(
			'api' => array(
				'root' => esc_url_raw(rest_url()),
				'namespace' => 'contact-form-7/v1'
			)
		));
	}
}, 100);

if(!is_admin()) {
    function remove_lazyblocks_div(){
      $args = array(
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_type' => 'page' //ここを各自変更します。複数やpage属性設定可
      );
      $all_posts = get_posts($args);
      foreach( $all_posts as $single_page ) {
        if (has_blocks( $single_page->post_content )){
          $single_page = parse_blocks( $single_page->post_content );
          $block_arr = array_unique($single_page,SORT_REGULAR);
          foreach($block_arr as $content){
            add_filter( $content['blockName'] . '/frontend_allow_wrapper', '__return_false' );
          }
        }
      }
    }
    add_action('wp','remove_lazyblocks_div');
  }
