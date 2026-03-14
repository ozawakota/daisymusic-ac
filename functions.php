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
			
			// WordPressのcronスケジューラに登録（タイムゾーン考慮）
			wp_clear_scheduled_hook('media_expiry_check', array($post_id));
			if ($expiry_date) {
				// WordPress時刻を考慮してタイムスタンプ変換
				$wp_timezone = wp_timezone();
				$datetime = DateTime::createFromFormat('Y-m-d\TH:i', $expiry_date, $wp_timezone);
				
				if ($datetime) {
					$timestamp = $datetime->getTimestamp();
					$current_timestamp = current_time('timestamp');
					
					if ($timestamp > $current_timestamp) {
						wp_schedule_single_event($timestamp, 'media_expiry_check', array($post_id));
						// デバッグログ
						error_log("Scheduled expiry for post #{$post_id} at " . $datetime->format('Y-m-d H:i:s T'));
					}
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
 * 期限チェック用の共通meta_queryを生成
 */
function get_media_expiry_meta_query() {
	return array(
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
}

/**
 * 個別投稿の期限チェック
 */
function is_media_post_expired($post_id) {
	$expiry_enabled = get_post_meta($post_id, '_media_expiry_enabled', true);
	
	if ($expiry_enabled !== '1') {
		return false; // 期限設定なし
	}
	
	$expiry_date = get_post_meta($post_id, '_media_expiry_date', true);
	if (empty($expiry_date)) {
		return false; // 期限日未設定
	}
	
	$current_time = current_time('Y-m-d\TH:i');
	return $expiry_date <= $current_time;
}

/**
 * LazyBlocks用: Activity Media投稿を取得する関数（改良版）
 */
function get_activity_media_posts($limit = -1) {
	$posts = get_posts(array(
		'post_type' => 'activity-media',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'meta_query' => get_media_expiry_meta_query()
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
 * リアルタイム期限チェック（フロントエンド表示時）
 */
add_action('wp_head', function() {
	if (!is_admin() && (is_page() || is_post_type_archive('activity-media'))) {
		// 期限切れ投稿を即座に処理
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
			'posts_per_page' => 10 // 一度に処理する件数を制限
		));
		
		foreach ($expired_posts as $post) {
			do_action('media_expiry_check', $post->ID);
		}
	}
});

/**
 * 期限切れ投稿の自動非公開処理
 */
function handle_expired_media_posts() {
    $timezone = wp_timezone();
    $current_time_tz = new DateTime('now', $timezone);
    $current_time_mysql = $current_time_tz->format('Y-m-d H:i:s');
    
    // デバッグログ記録
    media_expiry_debug_log("期限切れ処理開始 - 現在時刻: " . $current_time_mysql);
    
    $args = array(
        'post_type' => 'activity-media',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
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
        )
    );
    
    $query = new WP_Query($args);
    $processed_count = 0;
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $expiry_date = get_post_meta($post_id, '_media_expiry_date', true);
            
            // 下書きに変更
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'draft'
            ));
            
            $processed_count++;
            media_expiry_debug_log("投稿ID {$post_id} を下書きに変更しました (期限: {$expiry_date})");
        }
    }
    
    media_expiry_debug_log("期限切れ処理完了 - 処理件数: {$processed_count}件");
    
    wp_reset_postdata();
}

/**
 * 定期的な期限チェック（バックアップ処理）
 */
add_action('wp_scheduled_delete', function() {
	handle_expired_media_posts();
});

// 毎日の期限チェック処理をスケジュール
if (!wp_next_scheduled('media_expiry_daily_check')) {
    wp_schedule_event(time(), 'daily', 'media_expiry_daily_check');
}

add_action('media_expiry_daily_check', 'handle_expired_media_posts');

// デバッグ機能とログ記録
function media_expiry_debug_log($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $timestamp = current_time('Y-m-d H:i:s');
        error_log("[Media Expiry Debug {$timestamp}] {$message}");
    }
}

// 管理画面にデバッグ情報を表示
function add_media_expiry_debug_page() {
    if (current_user_can('manage_options')) {
        add_submenu_page(
            'edit.php?post_type=activity-media',
            'メディア掲載期限デバッグ',
            'デバッグ情報',
            'manage_options',
            'media-expiry-debug',
            'media_expiry_debug_page'
        );
    }
}
add_action('admin_menu', 'add_media_expiry_debug_page');

function media_expiry_debug_page() {
    echo '<div class="wrap">';
    echo '<h1>メディア掲載期限デバッグ情報</h1>';
    
    // 現在時刻情報
    $timezone = wp_timezone();
    $current_time_tz = new DateTime('now', $timezone);
    echo '<h2>時刻情報</h2>';
    echo '<p><strong>WordPress現在時刻:</strong> ' . current_time('Y-m-d H:i:s') . '</p>';
    echo '<p><strong>タイムゾーン:</strong> ' . $timezone->getName() . '</p>';
    echo '<p><strong>UTCオフセット:</strong> ' . get_option('gmt_offset') . '時間</p>';
    
    // 期限設定されている投稿の一覧
    echo '<h2>期限設定投稿一覧</h2>';
    $args = array(
        'post_type' => 'activity-media',
        'post_status' => array('publish', 'draft'),
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => '_media_expiry_date',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => '_media_expiry_enabled',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        echo '<table class="widefat striped">';
        echo '<thead><tr><th>投稿タイトル</th><th>ステータス</th><th>期限日時</th><th>期限切れ判定</th></tr></thead><tbody>';
        
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $expiry_date = get_post_meta($post_id, '_media_expiry_date', true);
            $current_time = current_time('Y-m-d\TH:i');
            $is_expired = $expiry_date <= $current_time;
            $status = get_post_status();
            
            echo '<tr>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . $status . '</td>';
            echo '<td>' . $expiry_date . '</td>';
            echo '<td>' . ($is_expired ? '<span style="color: red;">期限切れ</span>' : '<span style="color: green;">有効</span>') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>期限設定されている投稿はありません。</p>';
    }
    wp_reset_postdata();
    
    // 次回のcron実行予定
    echo '<h2>Cron実行予定</h2>';
    $next_daily = wp_next_scheduled('media_expiry_daily_check');
    if ($next_daily) {
        echo '<p><strong>次回日次チェック:</strong> ' . date('Y-m-d H:i:s', $next_daily) . '</p>';
    } else {
        echo '<p>日次チェックがスケジュールされていません。</p>';
    }
    
    // 手動実行ボタン
    echo '<h2>手動実行</h2>';
    if (isset($_POST['manual_check'])) {
        echo '<div class="notice notice-info"><p>手動チェックを実行しました。</p></div>';
        handle_expired_media_posts();
    }
    echo '<form method="post">';
    echo '<input type="submit" name="manual_check" class="button button-primary" value="期限チェックを手動実行" />';
    echo '</form>';
    
    echo '</div>';
}

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

