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

/**
 * ローカル環境でのメール送信設定
 */
if (defined('WP_ENV') && WP_ENV === 'development') {
	// ローカル環境ではメール送信をログに記録するのみ
	add_filter('wp_mail', function($args) {
		error_log('=== Local Mail Sent ===');
		error_log('To: ' . $args['to']);
		error_log('Subject: ' . $args['subject']);
		error_log('Message: ' . $args['message']);
		error_log('======================');
		return $args;
	});
}

/**
 * ローカル環境での不要なスクリプト無効化（パフォーマンス向上）
 */
add_action('wp_enqueue_scripts', function() {
	// ローカル環境またはお問い合わせページ以外でreCAPTCHAを無効化
	if (!is_page('contact')) {
		wp_dequeue_script('google-recaptcha');
		wp_dequeue_script('wpcf7-recaptcha');
	}

	// 不要なElementorアニメーションCSSを無効化（使用していない場合）
	wp_dequeue_style('e-animations');
}, 999);

/**
 * Contact Form 7のGmail API連携を無効化（ローカル環境用）
 */
add_filter('wpcf7_use_really_simple_captcha', '__return_false');

// Gmail APIの使用を無効化
add_filter('wpcf7_mail_components', function($components, $contact_form, $mail) {
	// ローカル環境ではPHPのmail()を使用
	return $components;
}, 10, 3);

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


/**
 * メディア掲載のカスタム投稿タイプを登録
 */
add_action('init', function() {
	register_post_type('activity-media', array(
		'labels' => array(
			'name' => 'メディア掲載',
			'singular_name' => 'メディア掲載',
			'add_new' => '新規追加',
			'add_new_item' => '新しいメディア掲載を追加',
			'edit_item' => 'メディア掲載を編集',
			'new_item' => '新しいメディア掲載',
			'view_item' => 'メディア掲載を表示',
			'search_items' => 'メディア掲載を検索',
			'not_found' => '見つかりませんでした',
			'not_found_in_trash' => 'ごみ箱にはありませんでした'
		),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-format-image',
		'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
		'has_archive' => true,
		'rewrite' => array('slug' => 'activity-media'),
		'show_in_rest' => true,
		'hierarchical' => false
	));
});

/**
 * Intuitive Custom Post Orderプラグインサポートを追加
 */
add_filter('icp_post_types', function($post_types) {
	$post_types[] = 'activity-media';
	return $post_types;
});

/**
 * メディア掲載のカスタムフィールドを追加
 */
add_action('add_meta_boxes', function() {
	add_meta_box(
		'media_custom_fields',
		'メディア掲載情報',
		'media_custom_fields_callback',
		'activity-media'
	);
	
	// 独自の期限設定メタボックス追加
	add_meta_box(
		'media_expiry_settings',
		'表示期限設定',
		'media_expiry_callback',
		'activity-media',
		'side',
		'high'
	);
});

function media_custom_fields_callback($post) {
	wp_nonce_field('media_save_meta_box', 'media_meta_box_nonce');
	
	$media_link = get_post_meta($post->ID, '_media_link', true);
	
	echo '<table class="form-table">';
	echo '<tr>';
	echo '<th><label for="media_link">詳しくはこちら（URL）</label></th>';
	echo '<td><input type="url" id="media_link" name="media_link" value="' . esc_attr($media_link) . '" size="50" /></td>';
	echo '</tr>';
	echo '</table>';
}

function media_expiry_callback($post) {
	wp_nonce_field('media_expiry_meta_box', 'media_expiry_nonce');
	
	$expiry_enabled = get_post_meta($post->ID, '_media_expiry_enabled', true);
	$expiry_date = get_post_meta($post->ID, '_media_expiry_date', true);
	$expiry_action = get_post_meta($post->ID, '_media_expiry_action', true) ?: 'draft';
	
	echo '<div style="margin-bottom: 15px;">';
	echo '<label>';
	echo '<input type="checkbox" name="media_expiry_enabled" value="1" ' . checked(1, $expiry_enabled, false) . '> ';
	echo '表示期限を設定する';
	echo '</label>';
	echo '</div>';
	
	echo '<div id="media-expiry-settings" style="' . ($expiry_enabled ? '' : 'display:none;') . '">';
	
	echo '<div style="margin-bottom: 15px;">';
	echo '<label for="media_expiry_date">期限日時:</label><br>';
	echo '<input type="datetime-local" id="media_expiry_date" name="media_expiry_date" value="' . esc_attr($expiry_date) . '" style="width: 100%;" />';
	echo '</div>';
	
	echo '<div style="margin-bottom: 15px;">';
	echo '<label for="media_expiry_action">期限後のアクション:</label><br>';
	echo '<select id="media_expiry_action" name="media_expiry_action" style="width: 100%;">';
	echo '<option value="draft"' . selected('draft', $expiry_action, false) . '>下書きに変更</option>';
	echo '<option value="private"' . selected('private', $expiry_action, false) . '>非公開に変更</option>';
	echo '<option value="trash"' . selected('trash', $expiry_action, false) . '>ゴミ箱に移動</option>';
	echo '</select>';
	echo '</div>';
	
	echo '</div>';
	
	// JavaScript for toggle
	echo '<script>
		document.addEventListener("DOMContentLoaded", function() {
			const checkbox = document.querySelector("input[name=\'media_expiry_enabled\']");
			const settings = document.getElementById("media-expiry-settings");
			
			checkbox.addEventListener("change", function() {
				settings.style.display = this.checked ? "block" : "none";
			});
		});
	</script>';
}

/**
 * カスタムフィールドの保存
 */
add_action('save_post', function($post_id) {
	// 基本的なバリデーション
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	
	// メディア情報の保存
	if (isset($_POST['media_meta_box_nonce']) && wp_verify_nonce($_POST['media_meta_box_nonce'], 'media_save_meta_box')) {
		if (isset($_POST['media_link'])) {
			update_post_meta($post_id, '_media_link', sanitize_text_field($_POST['media_link']));
		}
	}
	
	// 期限設定の保存
	if (isset($_POST['media_expiry_nonce']) && wp_verify_nonce($_POST['media_expiry_nonce'], 'media_expiry_meta_box')) {
		$expiry_enabled = isset($_POST['media_expiry_enabled']) ? 1 : 0;
		update_post_meta($post_id, '_media_expiry_enabled', $expiry_enabled);
		
		if ($expiry_enabled && isset($_POST['media_expiry_date'])) {
			$expiry_date = sanitize_text_field($_POST['media_expiry_date']);
			update_post_meta($post_id, '_media_expiry_date', $expiry_date);
			
			$expiry_action = isset($_POST['media_expiry_action']) ? sanitize_text_field($_POST['media_expiry_action']) : 'draft';
			update_post_meta($post_id, '_media_expiry_action', $expiry_action);
			
			// WordPressのcronスケジューラに登録
			wp_clear_scheduled_hook('media_expiry_check', array($post_id));
			if ($expiry_date) {
				$timestamp = strtotime($expiry_date);
				if ($timestamp && $timestamp > time()) {
					wp_schedule_single_event($timestamp, 'media_expiry_check', array($post_id));
				}
			}
		} else {
			// 期限設定が無効の場合、スケジュールをクリア
			wp_clear_scheduled_hook('media_expiry_check', array($post_id));
			delete_post_meta($post_id, '_media_expiry_date');
			delete_post_meta($post_id, '_media_expiry_action');
		}
	}
});

/**
 * LazyBlocks用: Activity Media投稿を取得する関数
 */
function get_activity_media_posts($limit = -1) {
	$posts = get_posts(array(
		'post_type' => 'activity-media',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key' => '_media_expiry_enabled',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key' => '_media_expiry_enabled',
				'value' => '1',
				'compare' => '!='
			),
			array(
				'relation' => 'AND',
				array(
					'key' => '_media_expiry_enabled',
					'value' => '1',
					'compare' => '='
				),
				array(
					'key' => '_media_expiry_date',
					'value' => current_time('Y-m-d\TH:i'),
					'compare' => '>'
				)
			)
		)
	));
	
	return $posts;
}

/**
 * 期限切れ投稿の処理
 */
add_action('media_expiry_check', function($post_id) {
	$post = get_post($post_id);
	if (!$post || $post->post_type !== 'activity-media') {
		return;
	}
	
	$expiry_action = get_post_meta($post_id, '_media_expiry_action', true) ?: 'draft';
	
	switch ($expiry_action) {
		case 'draft':
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'draft'
			));
			break;
		case 'private':
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'private'
			));
			break;
		case 'trash':
			wp_trash_post($post_id);
			break;
	}
	
	// ログを記録（オプション）
	error_log("Media post #{$post_id} expired and set to {$expiry_action}");
});

/**
 * フロントエンドで期限切れ投稿を非表示にする
 */
add_action('pre_get_posts', function($query) {
	// 管理画面では処理しない
	if (is_admin() || !$query->is_main_query()) {
		return;
	}
	
	// activity-mediaの投稿のみ処理
	if (is_post_type_archive('activity-media') || (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'activity-media')) {
		$meta_query = $query->get('meta_query') ?: array();
		
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key' => '_media_expiry_enabled',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key' => '_media_expiry_enabled',
				'value' => '1',
				'compare' => '!='
			),
			array(
				'relation' => 'AND',
				array(
					'key' => '_media_expiry_enabled',
					'value' => '1',
					'compare' => '='
				),
				array(
					'key' => '_media_expiry_date',
					'value' => current_time('Y-m-d\TH:i'),
					'compare' => '>'
				)
			)
		);
		
		$query->set('meta_query', $meta_query);
	}
});

/**
 * 定期的な期限チェック（バックアップ処理）
 */
add_action('wp_scheduled_delete', function() {
	$expired_posts = get_posts(array(
		'post_type' => 'activity-media',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => '_media_expiry_enabled',
				'value' => '1',
				'compare' => '='
			),
			array(
				'key' => '_media_expiry_date',
				'value' => current_time('Y-m-d\TH:i'),
				'compare' => '<='
			)
		),
		'posts_per_page' => -1
	));
	
	foreach ($expired_posts as $post) {
		do_action('media_expiry_check', $post->ID);
	}
});

/**
 * PublishPress Futureでactivity-mediaカスタム投稿タイプを強制的に有効にする
 */
add_action('init', function() {
	// カスタム投稿タイプがPublic Queryableである必要がある
	add_post_type_support('activity-media', 'post-expirator');
}, 15);

// PublishPress Futureの投稿タイプリストに追加
add_filter('publishpress_future_supported_post_types', function($post_types) {
	if (!in_array('activity-media', $post_types)) {
		$post_types[] = 'activity-media';
	}
	return $post_types;
});

// 旧バージョンとの互換性
add_filter('post_expirator_supported_post_types', function($post_types) {
	if (!in_array('activity-media', $post_types)) {
		$post_types[] = 'activity-media';
	}
	return $post_types;
});

// プラグインのオプション直接更新（最終手段）
add_action('admin_init', function() {
	$options = get_option('publishpress_future_general', array());
	if (!isset($options['post-types']) || !is_array($options['post-types'])) {
		$options['post-types'] = array();
	}
	if (!in_array('activity-media', $options['post-types'])) {
		$options['post-types'][] = 'activity-media';
		update_option('publishpress_future_general', $options);
	}
});

		/**
 * トップページのみ medium_large（1024px）を srcset から除外
 */
add_filter( 'wp_calculate_image_srcset', function( $sources ) {

	// トップページ以外は何もしない
	if ( ! is_front_page() ) {
					return $sources;
	}

	foreach ( $sources as $width => $source ) {
					// 1024px以下（thumbnail / medium / medium_large）を除外
					if ( $width <= 1024 ) {
									unset( $sources[ $width ] );
					}
	}

	return $sources;
});

