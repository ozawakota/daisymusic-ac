<!-- blog -->
<div class="post_content mb-0">
  <div class="swell-block-fullWide alignfull pt-0" style="background-color:#fff4e8">
    <div class="swell-block-fullWide__inner l-article">
			<div class="home-blog">
				<!-- 新着情報 -->
				<div class="c-tabBody p-postListTabBody">
					<div class="c-tabBody__item" aria-hidden="false">
						<?php
						// 新着記事の取得（seminarカテゴリーのみ）
						$recent_posts = new WP_Query([
							'post_type' => 'post',
							'posts_per_page' => 5,
							'post_status' => 'publish',
							'no_found_rows' => true,
							'ignore_sticky_posts' => 1,
							'orderby' => 'date',
							'order' => 'DESC',
							'category_name' => 'seminar'
						]);

						if ($recent_posts->have_posts()) :
							// SWELLのリストスタイルを使用
							$SETTING = SWELL_Theme::get_setting();
							$list_type = \SWELL_Theme::$list_type ?: 'card';
							
							// リストデータを準備
							$list_args = ['type' => $list_type];
							$list_data = \SWELL_THEME\Parts\Post_List::get_list_data($list_args);
							$ul_class = $list_data['ul_class'];
							$parts_name = $list_data['parts_name'];
							$li_args = $list_data['li_args'];
						?>
							<ul class="<?php echo esc_attr($ul_class); ?>">
								<?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
									<?php SWELL_Theme::get_parts("parts/post_list/{$parts_name}", $li_args); ?>
								<?php endwhile; ?>
							</ul>
						<?php 
						else :
							echo '<p>' . __('記事が見つかりませんでした。', 'swell') . '</p>';
						endif;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- blog -->
