<?php get_header(); ?>

<section class="p-hero">
  <div class="p-hero__bg">
    <div class="p-hero__content l-inner">
      <h2 class="p-hero__copy">
        野球を通じて一生の仲間と出会おう<br />
        地域に愛される常盤平ボーイズ
      </h2>
    </div>
  </div>
</section>

<div class="l-main-container">
  <main class="l-main-content">
    <section class="p-instagram l-inner__section">
      <div class="c-title">
        <h2 class="c-title__en">INSTAGRAM</h2>
        <span class="c-title__ja">各学年の活動風景</span>
      </div>

      <div class="p-instagram__slider swiper js-insta-swiper">
        <ul class="p-instagram__list swiper-wrapper">
          <?php
          // 取得したい学年のスラッグを配列にする
          $grades = array(
            'all'    => '全体',
            'grade6' => '6年生',
            'grade5' => '5年生',
            'grade4' => '4年生',
            'grade3' => '3年生',
            'grade2' => '2年生以下'
          );

          foreach ($grades as $slug => $label) :
            // 各学年の最新1件だけを取得するクエリ
            $args = array(
              'post_type'      => 'post',
              'category_name'  => $slug, // 管理画面で作ったカテゴリースラッグ
              'posts_per_page' => 1,      // 最新の1枚だけ
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
          ?>
                <li class="p-instagram__item swiper-slide">
                  <a href="https://www.instagram.com/tokiwadairaboys/" target="_blank" class="p-instagram__link">
                    <div class="p-instagram__img-wrap">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('class' => 'p-instagram__img')); ?>
                      <?php endif; ?>
                      <span class="p-instagram__label"><?php echo $label; ?></span>
                    </div>
                    <div class="p-instagram__body">
                      <time class="p-instagram__date"><?php echo get_the_date('Y.m.d'); ?></time>
                      <p class="p-instagram__text"><?php the_title(); ?></p>
                    </div>
                  </a>
                </li>
          <?php
              endwhile;
              wp_reset_postdata();
            endif;
          endforeach;
          ?>
        </ul>
      </div>

      <div class="p-instagram__btn-wrap">
        <a href="https://www.instagram.com/tokiwadairaboys/" target="_blank"
          class="c-button p-instagram__btn">INSTAGRAMを詳しく見る</a>
      </div>
    </section>

    <section class="p-movie" id="movie">
      <div class="l-inner__section-movie">
        <div class="c-title">
          <h2 class="c-title__en">MOVIE</h2>
          <span class="c-title__ja">チーム紹介動画</span>
        </div>

        <div class="p-movie__list">
          <?php
          $args = array(
            'post_type'      => 'movie',
            'posts_per_page' => 3,
          );
          $movie_query = new WP_Query($args);
          if ($movie_query->have_posts()) :
            while ($movie_query->have_posts()) : $movie_query->the_post();
          ?>
              <article class="p-movie__item">
                <div class="p-movie__video">
                  <?php the_content(); ?>
                </div>
                <div class="p-movie__body">
                  <h3 class="p-movie__title"><?php the_title(); ?></h3>
                  <div class="p-movie__text">
                    <?php the_excerpt(); ?>
                  </div>
                </div>
              </article>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
      </div>
    </section>

    <section class="p-faq" id="faq">
      <div class="l-inner__section-faq p-faq__inner">
        <div class="p-faq__header">
          <h2 class="p-faq__title-en">FAQ</h2>
          <p class="p-faq__title-ja">入団前の不安を解決します</p>
        </div>

        <div class="p-faq__list">
          <?php
          $args = array(
            'post_type'      => 'faq',
            'posts_per_page' => -1, // 全件表示
            'orderby'        => 'menu_order', // 順序指定ができるように
            'order'          => 'ASC',
          );
          $faq_query = new WP_Query($args);
          if ($faq_query->have_posts()) :
            while ($faq_query->have_posts()) : $faq_query->the_post();
          ?>
              <div class="p-faq__item js-faq-accordion">
                <button class="p-faq__question js-faq-trigger">
                  <span><?php the_title(); // ここが質問になります 
                        ?></span>
                </button>
                <div class="p-faq__answer js-faq-content">
                  <div class="p-faq__answer-inner">
                    <?php the_content(); // ここが回答になります 
                    ?>
                  </div>
                </div>
              </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
      </div>
    </section>

    <section class="p-match" id="match-results">
      <div class="l-inner__section-match">
        <div class="c-title">
          <h2 class="c-title__en">MATCH RESULTS</h2>
          <span class="c-title__ja">最新の試合結果</span>
        </div>

        <ul class="p-match__tab-list">
          <li class="p-match__tab-item is-active" data-grade="6">6年生</li>
          <li class="p-match__tab-item" data-grade="5">5年生</li>
          <li class="p-match__tab-item" data-grade="4">4年生</li>
          <li class="p-match__tab-item" data-grade="4">3年生</li>
          <li class="p-match__tab-item" data-grade="4">2年生</li>
        </ul>

        <div id="js-match-results" class="p-match__list">
          <p class="p-match__loading">Loading...</p>
        </div>

        <div class="p-match__btn-wrap">
          <a href="<?php echo esc_url(home_url('/match-results/')); ?>" class="c-button p-match__btn">試合結果一覧</a>
        </div>
      </div>
    </section>
  </main>

  <aside class="l-sidebar">
    <section class="p-ground__sidebar">
      <div class="l-inner__section-sidebar">
        <h2 class="c-title__sidebar">
          <span class="c-title__ja">活動場所</span>
        </h2>
        <ul class="p-ground__sidebar-list">
          <li class="p-ground__sidebar-item">
            <div class="p-ground__sidebar-desc">
              <a href="<?php echo esc_url(home_url('/location/')); ?>">
                <h3 class="p-ground__sidebar-name">江戸川グランド</h3>
                <p class="p-ground__sidebar-address">松戸市古ケ崎５９９</p>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </section>

    <section class="p-sponsor__sidebar">
      <div class="l-inner__section-sidebar">
        <h2 class="p-sponsor__sidebar-title">LOCAL SPONSOR</h2>
        <div class="p-sponsor__sidebar-list">
          <div class="p-sponsor__sidebar-item">SPONSOR 1</div>
          <div class="p-sponsor__sidebar-item">SPONSOR 2</div>
        </div>
      </div>
    </section>

    <div class="p-sidebar__btn-wrap">
      <p class="c-button p-sidebar__btn">FOLLOW US</p>
      <ul class="p-sidebar__btn-sns">
        <li>
          <a href="https://www.instagram.com/あなたのチーム/" target="_blank"><img
              src="<?php echo get_template_directory_uri(); ?>/assets/images/sidebar-icon1.png" alt="Instagram" /></a>
        </li>
        <li>
          <a href="https://www.youtube.com/@あなたのチーム/" target="_blank"><img
              src="<?php echo get_template_directory_uri(); ?>/assets/images/youtube-icon.png" alt="YouTube" /></a>
        </li>
      </ul>
    </div>
  </aside>
</div>

<section class="p-cta">
  <div class="p-cta__bg">
    <div class="p-cta__inner">
      <h2 class="p-cta__title">
        まずは体験会・見学へ<br class="no-pc" />お越しください
      </h2>
      <div class="p-cta__btn-wrap">
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="c-button p-cta__btn">体験会のお申し込みはこちら</a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>