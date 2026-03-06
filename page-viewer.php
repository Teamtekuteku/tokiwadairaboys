<?php
/* Template Name: 試合速報 Viewer（一般公開用） */

// URLのパラメータから学年を取得（指定がなければデフォルトで6年生）
$grade = isset($_GET['grade']) ? sanitize_text_field($_GET['grade']) : '6';

// ⚠️ あなたのGASウェブアプリのURLに書き換えてください
$gas_base_url = "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec";

// iframeに渡すURLを生成（mode=viewer と grade を付与）
$iframe_src = $gas_base_url . "?team=tokibo&mode=viewer&grade=" . urlencode($grade);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title><?php echo esc_html($grade); ?>年生 試合速報 | 常盤平ボーイズ</title>
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background-color: #f0f2f5;
    }

    .gas-iframe {
      width: 100%;
      height: 100dvh;
      border: none;
      display: block;
    }
  </style>
</head>

<body>

  <iframe class="gas-iframe" src="<?php echo esc_url($iframe_src); ?>" allowfullscreen>
  </iframe>

</body>

</html>