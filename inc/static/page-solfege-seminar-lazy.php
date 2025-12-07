<div class="post_content mb-0">
  <div class="swell-block-fullWide alignfull pt-0 lead-bg" data-bg="<?= ASSET_URI . "/img/solfege-seminar/lead_bg.png" ?>">
    <div class="swell-block-fullWide__inner l-article">
      <div class="solfege-seminar__lead">
        <h2 class="-title"><span>感じて教える力</span>を<br class="is-sp">育てる、新しい<br><span>ソルフェージュ</span>教育</h2>
        <p class="-text">
        Daisy Method（デイジー・メソッド）は、 読譜・リズム・音感をしっかり「感じて」教えるための体系的なソルフェージュ指導法です。<br>
        うたハノン&#174;を軸に、音楽の基礎力を“楽しみながら定着させる”カリキュラムを構築しています。<br>
        この講座では、単にアプローチを学ぶだけでなく、<br>
        「なぜその順序で教えるのか」「どうすれば生徒が自ら感じ取れるのか」――<br>
        教育の根幹にある“伝え方の感性”を育てていきます。
        </p>
      </div>
    </div>
  </div>
</div>


<div class="post_content mb-0 mt-60-100">
  <div class="swell-block-fullWide alignfull pt-0 ">
    <div class="swell-block-fullWide__inner l-article">
     <div class="c-pageSubTitle">
      <h3>VOICE</h3>
      <p>受講生の声</p>
     </div>
     <div class="voice">

     <?php
              $args = [
                'post_type' => 'student-voice', // カスタム投稿名が「news」の場合
              ];
              $my_query = new WP_Query($args); ?>
            
            <?php if ($my_query->have_posts()): // 投稿がある場合 ?>
            <div class="voice-swiper">
              <div class="swiper-wrapper">
            
                <?php while ($my_query->have_posts()) : $my_query->the_post();

                // Lazy Blocksのブロックをパースして属性を取得
                $blocks = parse_blocks(get_the_content());
                $attributes = [];

                // lazyblock/student-voiceブロックを探す
                foreach ($blocks as $block) {
                  if ($block['blockName'] === 'lazyblock/student-voice') {
                    $attributes = $block['attrs'] ?? [];
                    break;
                  }
                }

                // 属性が存在する場合のみ表示
                if (!empty($attributes)) :
                  // URLエンコードされたJSONをデコード
                  $img_data = json_decode(urldecode($attributes['img']), true);
                  $meta_data = json_decode(urldecode($attributes['meta']), true);

                  // 画像データ
                  $img_url = $img_data['url'] ?? '';
                  $img_alt = $img_data['alt'] ?? '';
                  $img_thumbnail = $img_data['sizes']['thumbnail']['url'] ?? $img_url;

                  // s-voice と t-voice
                  $s_voice_title = $attributes['title'] ?? '';
                  $s_voice = $attributes['s-voice'] ?? '';
                ?>

                <div class="voice-item swiper-slide">
                  <div class="-info">
                    <span class="-img">
                      <img src="<?= esc_url($img_thumbnail) ?>" alt="<?= esc_attr($img_alt) ?>">
                    </span>
                    <?php
                    // meta は配列なのでループ
                    if (!empty($meta_data) && is_array($meta_data)) :
                      foreach ($meta_data as $meta_item) :
                        $meta_grade = $meta_item['grade'] ?? '';
                        $meta_place = $meta_item['place'] ?? '';
                        $meta_name = $meta_item['name'] ?? '';
                    ?>
                    <div class="-meta">
                      <?php if ($meta_grade) : ?>
                      <div class="-grade">
                        <span><?= esc_html($meta_grade) ?></span>
                      </div>
                      <?php endif; ?>
                      <?php if ($meta_place) : ?>
                      <p class="-place"><?= esc_html($meta_place) ?></p>
                      <?php endif; ?>
                      <?php if ($meta_name) : ?>
                      <p class="-name"><?= esc_html($meta_name) ?></p>
                      <?php endif; ?>
                    </div>
                    <?php
                      endforeach;
                    endif;
                    ?>
                  </div><!-- .-info -->

                  <div class="-body">
                  <?php if ($s_voice_title) : ?>
                    <p class="-title">
                      <?= esc_html($s_voice_title) ?>
                    </p>
                    <?php endif; ?>

                    <?php if ($s_voice) : ?>
                    <p class="-text">
                      <?= nl2br(esc_html($s_voice)) ?>
                    </p>
                    <?php endif; ?>

                  </div><!-- .-body -->
                </div><!-- .swiper-slide -->

                <?php endif; // attributes存在チェック終了 ?>


            
                <?php endwhile; ?>
            

        </div><!-- .swiper-wrapper -->
      </div><!-- .voice-swiper -->
      <div class="swiper-button-prev" data-bg="<?= ASSET_URI . "/img/swiper-left.svg" ?>"></div>
      <div class="swiper-button-next" data-bg="<?= ASSET_URI . "/img/swiper-right.svg" ?>"></div>
     </div><!-- .voice -->
     <?php else: // 投稿がない場合?>

          
<?php endif; wp_reset_postdata(); ?>


     <div class="c-pageSubTitle">
      <h3>COURSE</h3>
      <p>コース構成</p>
     </div>
     <div class="course">
      <div class="course-lead">
        <h4><span>感性</span>を育て、<br class="is-sp"><span>教える力</span>を磨く。</h4>
        <p>音楽を教えるということは、音の向こうにある“心”を育てること。<br>
          Daisy Methodの３コースは、生徒の「できた！」の笑顔を導きながら、<br>
          指導者自身の感性も育っていく、そんな学びのステップです。<br>
          基礎から実践へ、そして創造へ。<br>
          音楽の本質にふれる喜びを共に育てていきましょう。</p>
      </div><!-- .course-lead -->
      <div class="course-step">
       <div class="-box -basic">
          <p class="-type">BASIC</p>
          <p class="-message">
            <strong>レッスンの質と楽しさ<br class="is-sp">を<br class="is-pc">両立させる実践力</strong>
          </p>
          <div class="-img">
            <img src="<?= ASSET_URI . "/img/solfege-seminar/course-img01.png" ?>" alt="" srcset="">
          </div>
          <p class="-text">
            導入期からソルフェージュの基礎をわかりやすく教える力を育てます。
            うたハノン®を通して「読譜・リズム・音感」を統合的に指導できるようになり、レッスンの質と楽しさを両立させる実践力を身につけます。
          </p>
          <div class="-btn">
            <a href="" class="c-btn -arrowRight">
              <span>詳しくはこちら</span>
            </a>
          </div>
        </div>
        <div class="-box -advance">
        <p class="-type">ADVANCE</p>
          <p class="-message">
            <strong>「伝える」から<br class="is-sp">「育てる」へと<br>一歩進んだ指導力</strong>
            <span class="-label">受講資格：Basic修了者</span>
          </p>
          <div class="-img">
            <img src="<?= ASSET_URI . "/img/solfege-seminar/course-img02.png" ?>" alt="" srcset="">
          </div>
          <p class="-text">
          ソルフェージュの発展だけでなく、コーチングや指導軸を整え、<br>
          「伝える」から「育てる」へと一歩進んだ指導力を磨くコースです。<br>
          表現力と説得力のあるレッスンを目指し、これから音楽講師として活躍したい方、より深く生徒と向き合いたい方に。<br>
          修了後はオンライン講師として活動するチャンスもあります。
          </p>

        </div>
        <div class="-box -master">
        <p class="-type">MASTER</p>
          <p class="-message">
            <strong>教材開発・模擬指導・<br>カリキュラム設計<br class="is-sp">までを体験</strong>
            <span class="-label">受講資格：Advance修了者</span>
          </p>
          <div class="-img">
            <img src="<?= ASSET_URI . "/img/solfege-seminar/course-img03.png" ?>" alt="" srcset="">
          </div>
          <p class="-text">
          教育理念を共有しながら、うたハノン&#174;を自在に活用し、音感・和声・リズム・教材づくりを総合的に探究するコースです。<br>
          音楽を構造的に捉え、創造的に伝える力を磨きながら、自分だけの指導スタイルを確立していきます。<br>
          実践をもとに、教材開発・模擬指導・カリキュラム設計までを体験し、感性を育てる指導法を未来へと継承していく視点を養います。
          </p>
        </div>
      </div><!-- .course-step -->
     </div><!-- .course -->
     <div class="other">
      <p>
      ソルフェージュを知ることは、<br class="is-sp">先生自身の感性がもう一度、花ひらくこと。<br><br>
      生徒を導くその手の中に、音楽の未来があります。<br>
      教えるたびに、自分も育つ。<br class="is-sp">その喜びを、共に奏でていきましょう。
      </p>
      <picture class="-img">
        <source srcset="<?= ASSET_URI . "/img/course-piano_pc.svg" ?>" media="(min-width: 960px)">
        <img src="<?= ASSET_URI . "/img/course-piano_sp.svg" ?>" alt="">
      </picture>

     </div>
    </div>
  </div>
</div>
