<?php
// data-page="{home}"に使用するページタイプ取得用関数
function get_data_page_type() {
    if (is_front_page()) {
        return 'home';
    // } elseif (is_home()) {
    //     return 'blog';
    // } elseif (is_single()) {
    //     return 'single';
    } elseif (is_page()) {
        return 'page';
    // } elseif (is_archive()) {
    //     return 'archive';
    // } elseif (is_category()) {
    //     return 'category';
    // } elseif (is_tag()) {
    //     return 'tag';
    // } elseif (is_author()) {
    //     return 'author';
    // } elseif (is_search()) {
    //     return 'search';
    // } elseif (is_404()) {
    //     return '404';
    } else {
        return '404';
    }
}