<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING      = SWELL_Theme::get_setting();
$header_class = SWELL_Theme::get_header_class(); // ヘッダーとfixバーへのクラクラス

// お知らせバー（上部表示）
if ( $SETTING['info_bar_pos'] === 'head_top' ) SWELL_Theme::get_parts( 'parts/header/info_bar' );
?>
<header id="header" class="l-header <?=esc_attr( $header_class )?>" data-spfix="<?=$SETTING['fix_header_sp'] ? '1' : '0'?>">
	<?php // if ( SWELL_Theme::is_use( 'head_bar' ) ) SWELL_Theme::get_parts( 'parts/header/head_bar' ); // ヘッダーバー ?>
	<div class="l-header__inner l-container">
		<div class="l-header__logo">
			<?php echo SWELL_PARTS::head_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="catch-phrase">
				<p>幼児から大人まで、<br>本格的な音楽を学ぶハイエンドスクール
				</p>
			</div>
		</div>
		<nav id="gnav" class="l-header__gnav c-gnavWrap">
			<?php
			// メニューアイテムを取得
			$menu_name = 'header_menu';
			$locations = get_nav_menu_locations();
			$menu = wp_get_nav_menu_object($locations[$menu_name] ?? '');

			if ($menu) {
				$menu_items = wp_get_nav_menu_items($menu->term_id);

				if ($menu_items) :
			?>
			<ul class="c-gnavParent">
				<?php
				// 親メニュー項目のみを抽出
				$parent_items = array_filter($menu_items, function($item) {
					return $item->menu_item_parent == 0;
				});

				foreach ($parent_items as $item) :
					// アイキャッチ画像を取得
					$featured_image_id = get_post_meta($item->ID, '_menu_item_featured_image', true);
					$image_url = '';
					if ($featured_image_id) {
						$image_url = wp_get_attachment_image_url($featured_image_id, 'medium');
					}

					// 子メニューを取得
					$child_items = array_filter($menu_items, function($child) use ($item) {
						return $child->menu_item_parent == $item->ID;
					});

					$has_children = !empty($child_items);
				?>
				<li>
					<?php if ($has_children) : ?>
						<span>
							<?php if ($image_url) : ?>
								<img src="<?= esc_url($image_url) ?>" alt="<?= esc_attr($item->title) ?>">
							<?php else : ?>
								<?= esc_html($item->title) ?>
							<?php endif; ?>
						</span>
						<ul class="c-gnavGrandParent">
							<?php foreach ($child_items as $child) : ?>
								<li><a href="<?= esc_url($child->url) ?>">-<?= esc_html($child->title) ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<a href="<?= esc_url($item->url) ?>">
							<?php if ($image_url) : ?>
								<img src="<?= esc_url($image_url) ?>" alt="<?= esc_attr($item->title) ?>">
							<?php else : ?>
								<?= esc_html($item->title) ?>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php
				endif;
			}
			?>
		</nav>
		<?php
			// ヘッダー内ウィジェット
			SWELL_Theme::outuput_widgets( 'head_box', [
				'before' => '<div class="w-header pc_"><div class="w-header__inner">',
				'after'  => '</div></div>',
			] );

			// メニューボタン & カスタムボタン
			SWELL_Theme::get_parts( 'parts/header/sp_btns' );
		?>
	</div>
	<?php
	if ( SWELL_Theme::is_use( 'sp_head_nav' ) ) :
		SWELL_Theme::get_parts( 'parts/header/sp_head_nav' );
	endif;
	?>
</header>
<?php

// FIXヘッダー
if ( SWELL_Theme::is_use( 'fix_header' ) ) SWELL_Theme::get_parts( 'parts/header/fix_header', $header_class );

// お知らせバー（下部表示）
if ( $SETTING['info_bar_pos'] === 'head_bottom' ) SWELL_Theme::get_parts( 'parts/header/info_bar' );
