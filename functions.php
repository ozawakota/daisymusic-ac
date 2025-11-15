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
	
	

}, 11);
