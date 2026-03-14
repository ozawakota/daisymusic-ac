<div class="post_content mb-0">
    <div class="swell-block-fullWide alignfull pt-0">
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

                <div class="main_title_style media__title">
                    <div class="flex_style ">
                        <p class="main_title_h2">新しい</p>
                        <p class="main_title_h2">ソルフェージュ</p>
                    </div>
                    <p class="main_title_h2">指導の教科書</p>
                    <img class="media__title--img" src="<?= ASSET_URI . "/img/media/icon_01.png" ?>" alt="">
                </div>

                <div class="text_box_style main">
                    <p class="main_font">
                        ソルフェージュレッスンをもっと楽しくする、100以上のレッスン実践例掲載
                        !
                    </p>
                    <p class="main_font">
                        「生徒の成長が遅い」「なかなか暗譜できず、自信なさそう」「譜面を読むのが苦手」…
                        … etc
                    </p>
                    <p class="main_font">
                        先生と生徒のソルフェージュに関するお悩みを解決します。
                    </p>
                    <p class="main_font">
                        ソルフェージュレッスンを工夫することで、生徒のやる気と自主性を引き出し、
                    </p>
                    <p class="main_font">
                        「もっとやりたい ! 」「音楽が楽しい !
                        」の声があふれてきます。
                    </p>
                    <p class="main_font">
                        この本で、生徒も先生も楽しくソルフェージュに取り組みましょう
                        !
                    </p>
                </div>
                <div class="sample_box_area">
                    <div class="-img">
                        <img src="<?= ASSET_URI . "/img/media/book_img01.png" ?>" alt="">
                    </div>
                    <div class="-contents">

                        <div class="sample_box_style media-swiper">
                            <ul class="sample_box swiper-wrapper">
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-01.jpg" ?>" alt="">
                                </li>
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-02.jpg" ?>" alt="">
                                </li>
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-03.jpg" ?>" alt="">
                                </li>
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-04.jpg" ?>" alt="">
                                </li>
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-05.jpg" ?>" alt="">
                                </li>
                                <li class="sample_box_item swiper-slide">
                                    <img class="book_sample" src="<?= ASSET_URI . "/img/media/sample-06.jpg" ?>" alt="">
                                </li>
                            </ul>
                        </div>
                        <div class="swiper-button-prev" data-bg="<?= ASSET_URI . "/img/swiper-left.svg" ?>"></div>
                        <div class="swiper-button-next" data-bg="<?= ASSET_URI . "/img/swiper-right.svg" ?>"></div>

                    </div>

                </div>
                <div class="btn">
                    <a href="https://amzn.asia/d/7RcMNEH" target="_blank" class="c-btn -arrowRight">
                        <span>ご購入はこちら</span>
                    </a>
                </div>


            </div>
        </div>
    </div>
    <div class="swell-block-fullWide alignfull pt-0">
        <div class="swell-block-fullWide__inner l-article">
            <div class="activity">
                <img class="icon-radio is-sp" src="<?= ASSET_URI . "/img/media/icon-radio-sp.png" ?>" alt="">

                <div class="activity__inner">
                    <div class="-body">
                        <?php
                            $args = [
                                'post_type' => 'activity-media', // カスタム投稿名が「news」の場合
                              ];
                            $my_query = new WP_Query($args); ?>
                        <?php if ($my_query->have_posts()): // 投稿がある場合 ?>

                            <?php while ($my_query->have_posts()) : $my_query->the_post();
                            // Lazy Blocksのブロックをパースして属性を取得
                                $blocks = parse_blocks(get_the_content());
                                $attributes = [];

                            // lazyblock/student-voiceブロックを探す
                            foreach ($blocks as $block) {
                            if ($block['blockName'] === 'lazyblock/activity-media') {
                                $attributes = $block['attrs'] ?? [];
                                break;
                            }
                            }

                // 属性が存在する場合のみ表示
                if (!empty($attributes)) :

                    // アイキャッチ画像を取得
                    $thumbnail_2x = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');

               
                  $title = $attributes['title'] ?? '';
                  $description = $attributes['description'] ?? '';
                  $link = $attributes['link'] ?? '';
                ?>
                <div class="-box">
                    <h4 class="-title">
                        <?php echo wp_kses($title, array('br' => array('class' => array()), 'span' => array('class' => array()))); ?>
                    </h4>
                    <div class="-img">
                        <img src="<?php echo esc_url($thumbnail_2x); ?>" srcset="<?php echo esc_url($thumbnail); ?> 1x, <?php echo esc_url($thumbnail_2x); ?> 2x" alt="<?php echo esc_attr(strip_tags($title)); ?>">
                    </div>
                    <p class="-text">
                        <?php echo wp_kses($description, array('br' => array('class' => array()), 'span' => array('class' => array()), 'strong' => array(), 'em' => array())); ?>
                    </p>
                    <?php if (!empty($link)) : ?>
                        <a class="-link" href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
                            <span>詳しくはこちら</span>
                        </a>
                    <?php endif; ?>
                    </div>
                <?php endif; // attributes存在チェック終了 ?>
                <?php endwhile; ?>
                <?php else: // 投稿がない場合?>
                    <p>現在、メディア掲載はありません</p>
                <?php endif; wp_reset_postdata(); ?>

                    </div><!-- .-body -->
                </div>
                <img class="icon-radio is-pc" src="<?= ASSET_URI . "/img/media/icon-radio.png" ?>" alt="">

            </div>
        </div>
    </div>
</div>