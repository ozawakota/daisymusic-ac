<?php get_header(); ?>

<main id="main_content" class="l-main">
    <div class="l-maininner">
        <div class="p-page__wrap">
            
            <?php get_template_part('parts/top_title_area'); ?>
            
            <div class="post_content">
                <div class="swell-block-fullWide alignfull">
                    <div class="swell-block-fullWide__inner l-article">
                        <div class="container">
                            
                            <div class="text_box_style">
                                <p class="main_font">
                                    YAMAHAから出版予定の公式教材をはじめ、
                                </p>
                                <p class="main_font">
                                    <span class="main_font_color">Daisy Music Academy</span>の想いが形になった作品をご紹介します。
                                </p>
                            </div>
                            
                            <?php if (have_posts()) : ?>
                                <div class="media-archive">
                                    <?php while (have_posts()) : the_post(); ?>
                                        <article class="media-item">
                                            <div class="media-item__image">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <div class="media-image-wrapper">
                                                        <?php the_post_thumbnail('large'); ?>
                                                        <div class="flower-decoration flower-top-left"></div>
                                                        <div class="flower-decoration flower-bottom-right"></div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="media-item__content">
                                                <h2 class="media-item__title"><?php the_title(); ?></h2>
                                                
                                                <div class="media-item__description">
                                                    <?php the_content(); ?>
                                                </div>
                                                
                                                <?php 
                                                $media_link = get_post_meta(get_the_ID(), '_media_link', true);
                                                if ($media_link) : 
                                                ?>
                                                    <div class="media-item__link">
                                                        <a href="<?php echo esc_url($media_link); ?>" target="_blank" rel="noopener noreferrer" class="c-btn -arrowRight">
                                                            <span>詳しくはこちら</span>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="media-item__date">
                                                    <?php echo get_the_date('Y年n月j日'); ?>
                                                </div>
                                            </div>
                                        </article>
                                    <?php endwhile; ?>
                                </div>
                                
                                <?php
                                // ページネーション
                                the_posts_pagination(array(
                                    'mid_size' => 2,
                                    'prev_text' => '← 前のページ',
                                    'next_text' => '次のページ →',
                                ));
                                ?>
                                
                            <?php else : ?>
                                <p class="no-posts">メディア掲載情報はまだありません。</p>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

<?php get_footer(); ?>