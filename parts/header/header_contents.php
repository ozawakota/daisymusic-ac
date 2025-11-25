<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$SETTING      = SWELL_Theme::get_setting();
$header_class = SWELL_Theme::get_header_class(); // ヘッダーとfixバーへのクラクラス

// お知らせバー（上部表示）
if ( $SETTING['info_bar_pos'] === 'head_top' ) SWELL_Theme::get_parts( 'parts/header/info_bar' );
?>
<header id="header" class="l-header <?=esc_attr( $header_class )?> o-anim-ready fadeIn delay-100ms" data-spfix="<?=$SETTING['fix_header_sp'] ? '1' : '0'?>">
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
			<ul class="c-gnavParent">
				<li>
					<span>
						<img src="<?= ASSET_URI . "/img/gnav-img01.png" ?>" alt="Daisy Music Academyとは？">
					</span>
					<ul class="c-gnavGrandParent">
						<li><a href="">-Daisy Music Academyとは？</a></li>
						<li><a href="">-講師プロフィール</a></li>
					</ul>
				</li>
				<li>
					<span>
					<img src="<?= ASSET_URI . "/img/gnav-img02.png" ?>" alt="レッスン講座">
					</span>
					<ul class="c-gnavGrandParent">
						<li><a href="">-新ソルフェージュ指導法講座</a></li>
						<li><a href="">-ソルフェージュスクール</a></li>
						<li><a href="">-音楽教室</a></li>
					</ul>
				</li>
				<li>
					<a href="">
						<img src="<?= ASSET_URI . "/img/gnav-img03.png" ?>" alt="音楽指導者コミュニティ">
					</a>
				</li>
				<li>
					<a href="">
						<img src="<?= ASSET_URI . "/img/gnav-img04.png" ?>" alt="出版書籍&メディア掲載">
					</a>
				</li>
				<li>
					<a href="/history/">
						<img src="<?= ASSET_URI . "/img/gnav-img05.png" ?>" alt="歴史沿革">
					</a>
				</li>
				<li>
					<a href="/blog/">
						<img src="<?= ASSET_URI . "/img/gnav-img06.png" ?>" alt="みか先生のブログ">
					</a>
				</li>
				<li>
					<a href="/contact/">
						<img src="<?= ASSET_URI . "/img/gnav-img06.png" ?>" alt="お問い合わせお申し込み">
					</a>
				</li>
			</ul>
			<?php //
				// SWELL_Theme::pluggable_parts( 'gnav', [
				// 	'use_search' => 'head_menu' === $SETTING['search_pos'],
				// ] );
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
