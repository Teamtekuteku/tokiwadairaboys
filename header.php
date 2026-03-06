<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <?php if (is_front_page()): ?>
  <?php endif; ?>

  <link rel="canonical" href="<?php echo home_url('/'); ?>" />

  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?php echo home_url('/'); ?>" />
  <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
  <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/ogp.jpg" />
  <meta property="og:locale" content="ja_JP" />

  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" sizes="any" />
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.svg" type="image/svg+xml" />
  <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SportsTeam",
      "name": "常盤平ボーイズ",
      "sport": "Baseball",
      "description": "千葉県松戸市で活動する少年野球チーム",
      "image": "<?php echo get_template_directory_uri(); ?>/assets/images/logo.png",
      "url": "<?php echo home_url('/'); ?>",
      "location": {
        "@type": "Place",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "松戸市",
          "addressRegion": "千葉県",
          "addressCountry": "JP"
        }
      }
    }
  </script>
  <?php wp_head(); ?>
</head>

<header class="l-header">
  <div class="l-header__inner">
    <h1 class="c-logo">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="c-logo__link">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_tokiwadairaboys.png" alt="常盤平ボーイズ"
          class="c-logo__img" />
      </a>
      <span class="c-logo__text">常盤平ボーイズ</span>
    </h1>

    <nav class="p-global-nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'global-navigation',
        'container'      => false,
        'menu_class'     => 'p-global-nav__list', // ulのクラス
        'add_li_class'   => 'p-global-nav__item', // liのクラス
        'add_a_class'    => 'p-global-nav__link', // aのクラス
        'fallback_cb'    => false,
      ]);
      ?>
      <a href="<?php echo esc_url('https://t-boys.sakura.ne.jp/index.html'); ?>"
        class="c-button c-button--green p-global-nav__button">
        旧サイトはこちら
      </a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="c-button c-button--red p-global-nav__button">
        体験・見学
      </a>
    </nav>

    <button type="button" class="c-hamburger js-hamburger" aria-controls="drawer-nav" aria-expanded="false">
      <span class="c-hamburger__line"></span>
      <span class="c-hamburger__line"></span>
      <span class="c-hamburger__line"></span>
    </button>
  </div>
</header>

<nav id="drawer-nav" class="p-drawer js-drawer" aria-hidden="true">
  <div class="p-drawer__inner">
    <div class="p-drawer__header">
      <div class="p-drawer__logo">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo_drawer.png" alt="常盤平ボーイズ" />
      </div>
      <button type="button" class="p-drawer__close js-drawer-close" aria-label="閉じる">
        <span></span><span></span>
      </button>
    </div>

    <ul class="p-drawer__list">
      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/home/')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">TOPへ戻る
          </span>
          <span class="p-drawer__link-en">TOP</span>
        </a>
      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/#movie')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">チーム紹介動画</span>
          <span class="p-drawer__link-en">MOVIE</span>
        </a>
      </li>
      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/#faq')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">常盤平ボーイズについて</span>
          <span class="p-drawer__link-en">ABOUT</span>
        </a>
      </li>
      <li class="p-drawer__item p-drawer__item--has-child js-drawer-accordion">
        <button type="button" class="p-drawer__link js-drawer-trigger">
          <span class="p-drawer__link-ja">メンバー紹介</span>
          <span class="p-drawer__link-en">MEMBERS</span>
        </button>

        <ul class="p-drawer__sub-list">
          <li class="p-drawer__sub-item">
            <a href="<?php echo esc_url(home_url('/players/')); ?>" class="p-drawer__link">
              <span class="p-drawer__link-ja">選手紹介</span>
              <span class="p-drawer__link-en">players</span>
            </a>
          </li>
          <li class="p-drawer__sub-item">
            <a href="<?php echo esc_url(home_url('/staff/')); ?>" class="p-drawer__link">
              <span class="p-drawer__link-ja">スタッフ紹介</span>
              <span class="p-drawer__link-en">staff</span>
            </a>
        </ul>
      </li>
      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/match-results/')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">試合結果</span>
          <span class="p-drawer__link-en">MATCH RESULTS</span>
        </a>
      </li>
      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/#location')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">活動場所</span>
          <span class="p-drawer__link-en">LOCATION</span>
        </a>
      </li>

      <li class="p-drawer__item">
        <a href="<?php echo esc_url(home_url('/#faq')); ?>" class="p-drawer__link">
          <span class="p-drawer__link-ja">よくある質問</span>
          <span class="p-drawer__link-en">FAQ</span>
        </a>
      </li>
    </ul>

    <div class="p-drawer__button-wrap">
      <a href="<?php echo esc_url(home_url('/contact/')); ?>"
        class="c-button c-button--red p-drawer__button">旧サイトへはこちら</a>
    </div>
    <div class="p-drawer__button-wrap">
      <a href="<?php echo esc_url(home_url('/contact/')); ?>"
        class="c-button c-button--red p-drawer__button">見学・体験予約</a>
    </div>
    <div class="p-drawer__link-sns">
      <a href="https://www.instagram.com/あなたのチームのアカウント/" class="p-drawer__instagram" target="_blank" rel="noopener">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/instagram_icon.png" alt="インスタグラム"
          class="p-drawer__instagram-icon" /></a>
    </div>
  </div>
  </div>
</nav>
<div class="c-overlay js-overlay"></div>