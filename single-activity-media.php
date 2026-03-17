<?php get_header(); ?>

<main id="main_content" class="l-main">
    <div class="l-maininner">
        <div class="p-page__wrap">
            
            <?php get_template_part('parts/top_title_area'); ?>
            
            <div class="post_content">
                <div class="swell-block-fullWide alignfull">
                    <div class="swell-block-fullWide__inner l-article">
                        <div class="container">
                            
                            <?php if (have_posts()) : ?>
                                <?php while (have_posts()) : the_post(); ?>
                                    <article class="single-media">
                                        <header class="single-media__header">
                                            <h1 class="single-media__title"><?php the_title(); ?></h1>
                                            <div class="single-media__date">
                                                <?php echo get_the_date('Y年n月j日'); ?>
                                            </div>
                                        </header>
                                        
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="single-media__image">
                                                <div class="media-image-wrapper">
                                                    <?php the_post_thumbnail('large'); ?>
                                                    <div class="flower-decoration flower-top-left"></div>
                                                    <div class="flower-decoration flower-bottom-right"></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="single-media__content">
                                            <?php the_content(); ?>
                                        </div>
                                        
                                        <?php 
                                        $media_link = get_post_meta(get_the_ID(), '_media_link', true);
                                        if ($media_link) : 
                                        ?>
                                            <div class="single-media__link">
                                                <a href="<?php echo esc_url($media_link); ?>" target="_blank" rel="noopener noreferrer" class="c-btn -arrowRight">
                                                    <span>詳しくはこちら</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <nav class="single-media__nav">
                                            <div class="nav-links">
                                                <div class="nav-previous">
                                                    <?php previous_post_link('%link', '← %title', true); ?>
                                                </div>
                                                <div class="nav-next">
                                                    <?php next_post_link('%link', '%title →', true); ?>
                                                </div>
                                            </div>
                                        </nav>
                                    </article>
                                <?php endwhile; ?>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

<?php get_footer(); ?>