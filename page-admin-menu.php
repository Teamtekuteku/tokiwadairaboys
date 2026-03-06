<?php
/* Template Name: LINE管理者メニュー */
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>管理者メニュー | 常盤平ボーイズ</title>
  <style>
    /* ご提示いただいたスタイルを適用 */
    body {
      font-family: sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 20px;
      color: #333;
    }

    #loginScreen {
      background: white;
      padding: 30px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      margin-top: 50px;
    }

    #loginScreen h2 {
      color: #1a73e8;
      margin-top: 0;
    }

    .pw-input {
      width: 100%;
      padding: 15px;
      font-size: 18px;
      border: 2px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
      text-align: center;
      margin: 20px 0;
      letter-spacing: 5px;
    }

    .btn-login {
      background: #1a73e8;
      color: white;
      border: none;
      padding: 15px;
      width: 100%;
      font-weight: bold;
      border-radius: 8px;
      font-size: 18px;
      cursor: pointer;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    #menuScreen {
      display: none;
    }

    .menu-header {
      background: #1a73e8;
      color: white;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .menu-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 15px;
    }

    .menu-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
      text-decoration: none;
      color: #333;
      display: flex;
      align-items: center;
      border-left: 6px solid #1a73e8;
      transition: 0.2s;
    }

    .menu-card:active {
      transform: scale(0.97);
    }

    .menu-icon {
      font-size: 30px;
      margin-right: 15px;
      width: 40px;
      text-align: center;
    }

    .menu-text {
      flex: 1;
    }

    .menu-title {
      font-size: 16px;
      font-weight: bold;
      color: #1a73e8;
      margin-bottom: 4px;
    }

    .menu-desc {
      font-size: 11px;
      color: #666;
    }

    /* カテゴリ別の色分け */
    .c-score {
      border-left-color: #d32f2f;
    }

    .c-score .menu-title {
      color: #d32f2f;
    }

    .c-sch {
      border-left-color: #f57c00;
    }

    .c-sch .menu-title {
      color: #f57c00;
    }

    .c-mem {
      border-left-color: #388e3c;
    }

    .c-mem .menu-title {
      color: #388e3c;
    }

    .c-res {
      border-left-color: #7b1fa2;
    }

    .c-res .menu-title {
      color: #7b1fa2;
    }

    .c-stats {
      border-left-color: #2962ff;
    }

    .c-stats .menu-title {
      color: #2962ff;
    }

    .footer-credit {
      text-align: center;
      padding: 20px 0 30px;
      font-size: 11px;
      color: #888;
      letter-spacing: 1px;
    }
  </style>
</head>

<body>

  <div id="loginScreen">
    <h2>🔒 事務局・幹部専用</h2>
    <div style="font-size:13px; color:#666;">管理用パスワードを入力してください</div>
    <input type="password" id="pwInput" class="pw-input" placeholder="****" inputmode="numeric">
    <button class="btn-login" onclick="checkPassword()">ログイン</button>
  </div>

  <div id="menuScreen">
    <div class="menu-header">⚙️ 管理者ダッシュボード</div>

    <div class="menu-grid" id="menuGrid">
    </div>

    <div style="text-align:center; margin-top:30px;">
      <button onclick="logout()"
        style="background:none; border:none; color:#888; text-decoration:underline; font-size:12px;">ログアウト</button>
    </div>
  </div>

  <div class="footer-credit">Product by Team てくてく</div>

  <script>
    // ⚠️ あなたのGASウェブアプリURL
    const SCRIPT_URL =
      "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?team=tokibo";
    const SECRET_PASSWORD = "1234"; // 必要に応じて変更してください

    window.onload = function() {
      if (sessionStorage.getItem('admin_logged_in') === 'true') {
        showMenu();
      }
    };

    function checkPassword() {
      const input = document.getElementById('pwInput').value;
      if (input === SECRET_PASSWORD) {
        sessionStorage.setItem('admin_logged_in', 'true');
        showMenu();
      } else {
        alert("パスワードが違います。");
        document.getElementById('pwInput').value = "";
      }
    }

    function showMenu() {
      // メニュー項目を動的に生成（URLを正しく結合するため）
      const menuItems = [{
          mode: 'portal',
          title: '試合速報・スコア入力',
          desc: '試合当日のスタメンやスコア中継を入力',
          icon: '⚾️',
          class: 'c-score'
        },
        {
          mode: 'schedule_edit',
          title: 'スケジュールの登録',
          desc: '週末の予定や、大会・合宿の予定を登録',
          icon: '📅',
          class: 'c-sch'
        },
        {
          mode: 'member_edit',
          title: '選手・メンバーの登録',
          desc: '新入部員の追加や卒団データの管理',
          icon: '👤',
          class: 'c-mem'
        },
        {
          mode: 'result_edit',
          title: '過去の試合結果の登録',
          desc: '過去のスコアをアーカイブに手動追加',
          icon: '📚',
          class: 'c-res'
        },
        {
          mode: 'stats_admin',
          title: '選手成績の分析・出力',
          desc: '打率、防御率の集計やCSVのダウンロード',
          icon: '📈',
          class: 'c-stats'
        }
      ];

      const grid = document.getElementById('menuGrid');
      grid.innerHTML = menuItems.map(item => `
        <a href="${SCRIPT_URL}&mode=${item.mode}" class="menu-card ${item.class}">
          <div class="menu-icon">${item.icon}</div>
          <div class="menu-text">
            <div class="menu-title">${item.title}</div>
            <div class="menu-desc">${item.desc}</div>
          </div>
        </a>
      `).join('');

      document.getElementById('loginScreen').style.display = 'none';
      document.getElementById('menuScreen').style.display = 'block';
    }

    function logout() {
      sessionStorage.removeItem('admin_logged_in');
      document.getElementById('pwInput').value = "";
      document.getElementById('menuScreen').style.display = 'none';
      document.getElementById('loginScreen').style.display = 'block';
    }
  </script>
</body>

</html>