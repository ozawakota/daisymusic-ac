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

/**
 * メニュー項目にアイキャッチ画像フィールドを追加
 */
class Menu_Item_Featured_Image {

    public function __construct() {
        add_action('wp_nav_menu_item_custom_fields', [$this, 'add_featured_image_field'], 10, 2);
        add_action('wp_update_nav_menu_item', [$this, 'save_featured_image'], 10, 2);
        add_filter('nav_menu_css_class', [$this, 'add_menu_item_class'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_media_uploader']);
    }

    /**
     * メニュー編集画面にアイキャッチ画像アップロードフィールドを追加
     */
    public function add_featured_image_field($item_id, $item) {
        $featured_image_id = get_post_meta($item_id, '_menu_item_featured_image', true);
        $image_url = '';

        if ($featured_image_id) {
            $image_url = wp_get_attachment_image_url($featured_image_id, 'thumbnail');
        }
        ?>
        <p class="field-featured-image description description-wide">
            <label for="edit-menu-item-featured-image-<?php echo esc_attr($item_id); ?>">
                <?php _e('アイキャッチ画像', 'swell'); ?><br>
                <input type="hidden"
                       id="edit-menu-item-featured-image-<?php echo esc_attr($item_id); ?>"
                       class="widefat menu-item-featured-image"
                       name="menu-item-featured-image[<?php echo esc_attr($item_id); ?>]"
                       value="<?php echo esc_attr($featured_image_id); ?>">

                <button type="button"
                        class="button menu-featured-image-upload"
                        data-item-id="<?php echo esc_attr($item_id); ?>">
                    <?php _e('画像を選択', 'swell'); ?>
                </button>

                <button type="button"
                        class="button menu-featured-image-remove"
                        data-item-id="<?php echo esc_attr($item_id); ?>"
                        style="<?php echo $featured_image_id ? '' : 'display:none;'; ?>">
                    <?php _e('画像を削除', 'swell'); ?>
                </button>

                <div class="menu-featured-image-preview" data-item-id="<?php echo esc_attr($item_id); ?>">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; height: auto; margin-top: 10px;">
                    <?php endif; ?>
                </div>
            </label>
        </p>
        <?php
    }

    /**
     * アイキャッチ画像を保存
     */
    public function save_featured_image($menu_id, $menu_item_db_id) {
        if (isset($_POST['menu-item-featured-image'][$menu_item_db_id])) {
            $featured_image_id = sanitize_text_field($_POST['menu-item-featured-image'][$menu_item_db_id]);

            if (!empty($featured_image_id)) {
                update_post_meta($menu_item_db_id, '_menu_item_featured_image', $featured_image_id);
            } else {
                delete_post_meta($menu_item_db_id, '_menu_item_featured_image');
            }
        }
    }

    /**
     * アイキャッチ画像があるメニュー項目にクラスを追加
     */
    public function add_menu_item_class($classes, $item) {
        $featured_image_id = get_post_meta($item->ID, '_menu_item_featured_image', true);

        if ($featured_image_id) {
            $classes[] = 'has-featured-image';
        }

        return $classes;
    }

    /**
     * メディアアップローダーのスクリプトを読み込み
     */
    public function enqueue_media_uploader($hook) {
        if ('nav-menus.php' !== $hook) {
            return;
        }

        wp_enqueue_media();

        // インラインスクリプトでメディアアップローダーを初期化
        $script = "
        jQuery(document).ready(function($) {
            var mediaUploader;

            $(document).on('click', '.menu-featured-image-upload', function(e) {
                e.preventDefault();

                var button = $(this);
                var itemId = button.data('item-id');
                var inputField = $('#edit-menu-item-featured-image-' + itemId);
                var previewContainer = $('.menu-featured-image-preview[data-item-id=\"' + itemId + '\"]');
                var removeButton = $('.menu-featured-image-remove[data-item-id=\"' + itemId + '\"]');

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: '画像を選択',
                    button: {
                        text: 'この画像を使用'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    inputField.val(attachment.id);
                    previewContainer.html('<img src=\"' + attachment.url + '\" style=\"max-width: 200px; height: auto; margin-top: 10px;\">');
                    removeButton.show();
                });

                mediaUploader.open();
            });

            $(document).on('click', '.menu-featured-image-remove', function(e) {
                e.preventDefault();

                var button = $(this);
                var itemId = button.data('item-id');
                var inputField = $('#edit-menu-item-featured-image-' + itemId);
                var previewContainer = $('.menu-featured-image-preview[data-item-id=\"' + itemId + '\"]');

                inputField.val('');
                previewContainer.html('');
                button.hide();
            });
        });
        ";

        wp_add_inline_script('jquery', $script);
    }
}

// クラスを初期化
new Menu_Item_Featured_Image();

/**
 * アイキャッチ画像付きメニュー表示用Walkerクラス
 */
class Menu_Featured_Image_Walker extends Walker_Nav_Menu {

    /**
     * メニュー項目の開始タグを出力
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // アイキャッチ画像IDを取得
        $featured_image_id = get_post_meta($item->ID, '_menu_item_featured_image', true);
        if ($featured_image_id) {
            $classes[] = 'has-featured-image';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = [];
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target)     ? $item->target     : '';
        $atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
        $atts['href']   = ! empty($item->url)        ? $item->url        : '';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (! empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // リンクの前のコンテンツ
        $link_before = $args->link_before ?? '';
        $link_after = $args->link_after ?? '';

        // アイキャッチ画像を取得
        $featured_image_html = '';
        if ($featured_image_id) {
            $image_url = wp_get_attachment_image_url($featured_image_id, 'medium');
            if ($image_url) {
                $image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
                $image_alt = $image_alt ?: $item->title;
                $featured_image_html = '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="menu-item-featured-image">';
            }
        }

        $item_output = $args->before ?? '';
        $item_output .= '<a'. $attributes .'>';

        // アイキャッチ画像がある場合は画像を表示
        if ($featured_image_html) {
            $item_output .= $featured_image_html;
        }

        $item_output .= $link_before . apply_filters('the_title', $item->title, $item->ID) . $link_after;
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}