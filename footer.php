<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( SWELL_Theme::is_show_sidebar() ) {
	get_sidebar();
}
?>
</div>
<?php
	$SETTING = SWELL_Theme::get_setting();

	if ( SWELL_Theme::is_use( 'pjax' ) ) echo '</div>'; // End : Barba[data-barba="container"]

	// フッター前ウィジェット
	if ( is_active_sidebar( 'before_footer' ) ) :
		echo '<div id="before_footer_widget" class="w-beforeFooter">';
		if ( ! SWELL_Theme::is_use( 'ajax_footer' ) ) :
			SWELL_Theme::get_parts( 'parts/footer/before_footer' );
		endif;
		echo '</div>';
	endif;

	// ぱんくず
	if ( 'top' !== $SETTING['pos_breadcrumb'] ) :
		SWELL_Theme::get_parts( 'parts/breadcrumb' );
	endif;
?>
<footer id="footer" class="l-footer">
	<?php if ( ! SWELL_Theme::is_use( 'ajax_footer' ) ) SWELL_Theme::get_parts( 'parts/footer/footer_contents' ); ?>
</footer>
<?php
	// 固定フッターメニュー
	if ( has_nav_menu( 'fix_bottom_menu' ) ) :
		$cache_key = $SETTING['cache_bottom_menu'] ? 'fix_bottom_menu' : '';
		SWELL_Theme::get_parts( 'parts/footer/fix_menu', null, $cache_key );
	endif;

	// 固定ボタン
	SWELL_Theme::get_parts( 'parts/footer/fix_btns' );

	// モーダル
	SWELL_Theme::get_parts( 'parts/footer/modals' );
?>
</div><!--/ #all_wrapp-->
<?php
wp_footer();
echo $SETTING['foot_code']; // phpcs:ignore
?>
<link rel='stylesheet' id='e-animations-css'  href='https://mika-ojima.com/wp-content/plugins/elementor/assets/lib/animations/animations.min.css?ver=3.6.7' media='all' />
<script id='astra-theme-js-js-extra'>
var astra = {"break_point":"921","isRtl":""};
</script>
<script src='https://mika-ojima.com/wp-content/themes/astra/assets/js/minified/frontend.min.js?ver=3.8.5' id='astra-theme-js-js'></script>
<script id='eio-lazy-load-js-before'>
var eio_lazy_vars = {"exactdn_domain":"","skip_autoscale":0,"threshold":0};
</script>
<script src='https://mika-ojima.com/wp-content/plugins/ewww-image-optimizer/includes/lazysizes.min.js?ver=670' id='eio-lazy-load-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/dist/vendor/regenerator-runtime.min.js?ver=0.13.9' id='regenerator-runtime-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/dist/vendor/wp-polyfill.min.js?ver=3.15.0' id='wp-polyfill-js'></script>
<script id='contact-form-7-js-extra'>
var wpcf7 = {"api":{"root":"https:\/\/mika-ojima.com\/wp-json\/","namespace":"contact-form-7\/v1"}};
</script>
<script src='https://mika-ojima.com/wp-content/plugins/contact-form-7/includes/js/index.js?ver=5.6.1' id='contact-form-7-js'></script>
<script src='https://www.google.com/recaptcha/api.js?render=6Lf6laQhAAAAAIU6FkbKVoA8GUcd3N83a6ZYQ6mr&#038;ver=3.0' id='google-recaptcha-js'></script>
<script id='wpcf7-recaptcha-js-extra'>
var wpcf7_recaptcha = {"sitekey":"6Lf6laQhAAAAAIU6FkbKVoA8GUcd3N83a6ZYQ6mr","actions":{"homepage":"homepage","contactform":"contactform"}};
</script>
<script src='https://mika-ojima.com/wp-content/plugins/contact-form-7/modules/recaptcha/index.js?ver=5.6.1' id='wpcf7-recaptcha-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/jquery/jquery.min.js?ver=3.6.0' id='jquery-core-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2' id='jquery-migrate-js'></script>
<script src='https://mika-ojima.com/wp-content/plugins/header-footer-elementor/inc/js/frontend.js?ver=1.6.12' id='hfe-frontend-js-js'></script>
<script src='https://mika-ojima.com/wp-content/plugins/elementor/assets/js/webpack.runtime.min.js?ver=3.6.7' id='elementor-webpack-runtime-js'></script>
<script src='https://mika-ojima.com/wp-content/plugins/elementor/assets/js/frontend-modules.min.js?ver=3.6.7' id='elementor-frontend-modules-js'></script>
<script src='https://mika-ojima.com/wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min.js?ver=4.0.2' id='elementor-waypoints-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/jquery/ui/core.min.js?ver=1.13.1' id='jquery-ui-core-js'></script>
<script id='elementor-frontend-js-before'>
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Facebook \u3067\u5171\u6709","shareOnTwitter":"Twitter \u3067\u5171\u6709","pinIt":"\u30d4\u30f3\u3059\u308b","download":"\u30c0\u30a6\u30f3\u30ed\u30fc\u30c9","downloadImage":"\u753b\u50cf\u3092\u30c0\u30a6\u30f3\u30ed\u30fc\u30c9","fullscreen":"\u30d5\u30eb\u30b9\u30af\u30ea\u30fc\u30f3","zoom":"\u30ba\u30fc\u30e0","share":"\u30b7\u30a7\u30a2","playVideo":"\u52d5\u753b\u518d\u751f","previous":"\u524d","next":"\u6b21","close":"\u9589\u3058\u308b"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"\u30e2\u30d0\u30a4\u30eb","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Extra","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"\u30bf\u30d6\u30ec\u30c3\u30c8","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Extra","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"\u30ce\u30fc\u30c8\u30d6\u30c3\u30af","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"\u30ef\u30a4\u30c9\u30b9\u30af\u30ea\u30fc\u30f3","value":2400,"default_value":2400,"direction":"min","is_enabled":false}}},"version":"3.6.7","is_static":false,"experimentalFeatures":{"e_dom_optimization":true,"e_optimized_assets_loading":true,"e_optimized_css_loading":true,"a11y_improvements":true,"e_import_export":true,"additional_custom_breakpoints":true,"e_hidden_wordpress_widgets":true,"landing-pages":true,"elements-color-picker":true,"favorite-widgets":true,"admin-top-bar":true},"urls":{"assets":"https:\/\/mika-ojima.com\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":348,"title":"Daisy%20Music%20Academy%20%E3%82%AA%E3%83%95%E3%82%A3%E3%82%B7%E3%83%A3%E3%83%AB%E3%82%B5%E3%82%A4%E3%83%88%20%E2%80%93%20%E5%B9%BC%E5%85%90%E3%81%8B%E3%82%89%E5%A4%A7%E4%BA%BA%E3%81%BE%E3%81%A7%E3%80%81%E6%9C%AC%E6%A0%BC%E7%9A%84%E3%81%AA%E9%9F%B3%E6%A5%BD%E3%82%92%E5%AD%A6%E3%81%B6%E3%83%8F%E3%82%A4%E3%82%A8%E3%83%B3%E3%83%89%E3%82%B9%E3%82%AF%E3%83%BC%E3%83%AB","excerpt":"","featuredImage":false}};
</script>
<script src='https://mika-ojima.com/wp-content/plugins/elementor/assets/js/frontend.min.js?ver=3.6.7' id='elementor-frontend-js'></script>
<script src='https://mika-ojima.com/wp-includes/js/underscore.min.js?ver=1.13.3' id='underscore-js'></script>
<script id='wp-util-js-extra'>
var _wpUtilSettings = {"ajax":{"url":"\/wp-admin\/admin-ajax.php"}};
</script>
<script src='https://mika-ojima.com/wp-includes/js/wp-util.min.js?ver=6.0.3' id='wp-util-js'></script>
<script id='wpforms-elementor-js-extra'>
var wpformsElementorVars = {"captcha_provider":"recaptcha","recaptcha_type":"v2"};
</script>
<script src='https://mika-ojima.com/wp-content/plugins/wpforms-lite/assets/js/integrations/elementor/frontend.min.js?ver=1.7.5.3' id='wpforms-elementor-js'></script>
			<script>
			/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
			</script>

</body></html>
