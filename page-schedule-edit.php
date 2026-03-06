<?php
/* Template Name: スケジュール入力（管理画面） */

// ★ セキュリティ: もしWordPressにログインしていないユーザー弾く場合はここで行う
// if ( !is_user_logged_in() ) {
//     wp_safe_redirect( home_url() );
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>スケジュール入力 | 常盤平ボーイズ</title>
  <style>
  /* WordPress側の余白を消して、全画面表示にする */
  html,
  body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    /* 外側のスクロールバーを消す */
    background-color: #f0f2f5;
  }

  /* 埋め込みフレームの設定 */
  .gas-iframe {
    width: 100%;
    height: 100dvh;
    /* スマホのブラウザバーを考慮した全画面高さ */
    border: none;
    display: block;
  }
  </style>
</head>

<body>

  <iframe class="gas-iframe"
    src="https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?team=tokibo&mode=schedule_edit"
    allowfullscreen>
  </iframe>
</body>

</html>