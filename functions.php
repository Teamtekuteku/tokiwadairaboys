<?php

/**
 * 常盤平ボーイズ オリジナルテーマ用関数
 */


function tokiwadaira_setup()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}

add_action('after_setup_theme', 'tokiwadaira_setup');

/**
 * CSS・JavaScript（外部ライブラリ含む）の読み込みを一括管理
 */
function tokiwadaira_enqueue_scripts()
{
  // 1. Google Fonts の読み込み
  wp_enqueue_style(
    'tokiwadaira-google-fonts',
    'https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&family=Noto+Sans+JP:wght@400;500;700&family=Zen+Old+Mincho:wght@400;700&display=swap',
    array(),
    null
  );

  // 2. Swiper CSS (CDN)
  wp_enqueue_style(
    'swiper-css',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
    array(),
    '11.0.0'
  );

  // 3. メインのSassコンパイル済みCSS
  // 第3引数に array('swiper-css') を入れることで、Swiperより後に読み込まれます
  wp_enqueue_style(
    'tokiwadaira-main-style',
    get_template_directory_uri() . '/assets/css/main.css',
    array('swiper-css'),
    filemtime(get_template_directory() . '/assets/css/main.css') // ファイル更新日時をVerにする（キャッシュ対策）
  );

  // 4. Swiper JS (CDN) - footerで読み込む
  wp_enqueue_script(
    'swiper-js',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
    array(),
    '11.0.0',
    true
  );

  // 5. メインのJS（main.js）
  // Swiperに依存しているため array('swiper-js') を指定
  wp_enqueue_script(
    'tokiwadaira-main-js',
    get_template_directory_uri() . '/assets/js/main.js',
    array('swiper-js'),
    filemtime(get_template_directory() . '/assets/js/main.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'tokiwadaira_enqueue_scripts');

/**
 * お問い合わせやフォームの送信先、GASのAPI URLなどをJSに渡す設定（将来用）
 * これにより、JS側で WordPress のパスや GAS URL を変数として使えるようになります
 */
function tokiwadaira_js_vars()
{
  wp_localize_script('tokiwadaira-main-js', 'wpVars', array(
    'homeUrl' => home_url('/'),
    'themeUrl' => get_template_directory_uri(),
    // ここにGASのURLを貼る準備をしておくと便利です
    'gasApiUrl' => 'https://script.google.com/macros/s/XXXXX/exec'
  ));
}
add_action('wp_enqueue_scripts', 'tokiwadaira_js_vars');
/**
 * カスタム投稿タイプ「動画」を追加
 */
function create_post_type_movie()
{
  register_post_type(
    'movie',
    array(
      'labels' => array(
        'name' => '動画',
        'singular_name' => '動画'
      ),
      'public' => true,
      'has_archive' => true,
      'menu_icon' => 'dashicons-video-alt3', // ビデオアイコン
      'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    )
  );
}
add_action('init', 'create_post_type_movie');
/**
 * メニューの li と a に独自のクラス名を追加する
 */
// liタグへのクラス追加
function add_additional_class_on_li($classes, $item, $args)
{
  if (isset($args->add_li_class)) {
    $classes[] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

// aタグへのクラス追加
function add_additional_class_on_a($attrs, $item, $args)
{
  if (isset($args->add_a_class)) {
    $attrs['class'] = $args->add_a_class;
  }
  return $attrs;
}
add_filter('nav_menu_link_attributes', 'add_additional_class_on_a', 1, 3);

// メニュー機能を有効化
add_action('after_setup_theme', function () {
  register_nav_menus([
    'global-navigation' => 'グローバルナビゲーション',
  ]);
});
/**
 * サブメニュー（ul）のクラス名を書き換える
 */
add_filter('nav_menu_submenu_css_class', function ($classes) {
  $classes[] = 'p-global-nav__sub-list'; // ここにサブメニューulのクラス名
  return $classes;
});

/**
 * サブメニュー内の li と a にクラスを追加する
 */
function add_submenu_class_attribute($atts, $item, $args)
{
  // global-navigation の位置にあるメニューが対象
  if ($args->theme_location === 'global-navigation') {
    // もし親要素（depth > 0）がある項目ならサブメニュー用クラスを付与
    if ($item->menu_item_parent != 0) {
      $atts['class'] = 'p-global-nav__sub-link';
    }
  }
  return $atts;
}
add_filter('nav_menu_link_attributes', 'add_submenu_class_attribute', 10, 3);
