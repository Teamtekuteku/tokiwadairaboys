<?php
/* Template Name: 試合中継ポータル */
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>試合中継ポータル | 常盤平ボーイズ</title>
  <style>
    /* ご提示いただいたスタイルそのまま */
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .header {
      background: #d32f2f;
      color: white;
      padding: 15px;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header h1 {
      margin: 0;
      font-size: 18px;
    }

    .container {
      padding: 15px 20px;
      max-width: 600px;
      margin: 0 auto;
    }

    .section-title {
      font-size: 16px;
      color: #d32f2f;
      font-weight: bold;
      margin: 20px 0 10px 5px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-title::before {
      content: "";
      width: 4px;
      height: 16px;
      background: #d32f2f;
      border-radius: 2px;
    }

    .grade-slot {
      background: white;
      border-radius: 12px;
      margin-bottom: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border: 1px solid #eee;
      overflow: hidden;
    }

    .slot-header {
      padding: 10px 15px;
      background: #f8f9fa;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .grade-label {
      font-size: 14px;
      font-weight: bold;
      color: #555;
    }

    .status-badge {
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: bold;
    }

    .badge-live {
      background: #ffebee;
      color: #d32f2f;
      border: 1px solid #d32f2f;
      animation: blink 1.5s infinite;
    }

    @keyframes blink {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }

      100% {
        opacity: 1;
      }
    }

    .card-body {
      padding: 15px;
      display: block;
      color: inherit;
      cursor: pointer;
      transition: background 0.2s;
    }

    .card-body:active {
      background: #f8f9fa;
    }

    .match-up-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      white-space: nowrap;
    }

    .team-name {
      font-size: 15px;
      font-weight: bold;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .score-area {
      font-size: 24px;
      font-weight: 800;
      color: #d32f2f;
      min-width: 70px;
      text-align: center;
    }

    .inning-label {
      font-size: 12px;
      color: #d32f2f;
      font-weight: bold;
      margin-top: 2px;
    }

    .battery-info {
      margin-top: 12px;
      padding-top: 8px;
      border-top: 1px dashed #eee;
      display: flex;
      justify-content: center;
      gap: 15px;
      font-size: 13px;
      color: #666;
    }

    .b-label {
      color: #d32f2f;
      font-weight: bold;
      margin-right: 3px;
    }

    .btn-archive-box {
      margin: 30px 0;
      text-align: center;
    }

    .btn-archive {
      background: #444;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
    }

    .empty-slot {
      padding: 20px;
      text-align: center;
      color: #bbb;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>常盤平ボーイズ 試合中継</h1>
  </div>
  <div class="container">
    <div class="section-title">本日の試合速報</div>

    <div id="mainContainer">
      <div style="text-align:center; padding:20px; color:#888; font-weight:bold;">データを読み込み中...</div>
    </div>

    <div class="btn-archive-box">
      <button onclick="goToArchive()" class="btn-archive">📚 過去の試合結果を見る</button>
    </div>
  </div>

  <script>
    // ⚠️ あなたのGAS URLをここに貼り付け（末尾は ?mode=portal_data）
    const GAS_URL =
      "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?mode=portal_data";

    window.onload = function() {
      loadData();
    };

    // WordPressのビューワー画面へ遷移
    function goToGame(grade) {
      // ⚠️ ビューワー画面のURLスラッグ（例: /live/）を指定
      window.location.href = "/live/?grade=" + grade;
    }

    // WordPressのアーカイブ画面へ遷移
    function goToArchive() {
      // ⚠️ アーカイブ画面のURLスラッグ（例: /results/）を指定
      window.location.href = "/match-results/";
    }

    async function loadData() {
      try {
        const response = await fetch(GAS_URL, {
          redirect: "follow"
        });
        const res = await response.json();

        let mainHtml = "";
        [6, 5, 4, 3, 2, 1].forEach(g => {
          let d = res.live[String(g)];

          if (d && d.active) {
            mainHtml += `
              <div class="grade-slot">
                <div class="slot-header">
                  <span class="grade-label">${g}年生</span>
                  <span class="status-badge badge-live">● 速報中</span>
                </div>
                <div class="card-body" onclick="goToGame(${g})">
                  <div class="match-up-row">
                    <div class="team-name">${d.away}</div>
                    <div class="score-area">${d.awayScore} - ${d.homeScore}<div class="inning-label">${d.inn}</div></div>
                    <div class="team-name">${d.home}</div>
                  </div>
                  <div class="battery-info">
                    <span><span class="b-label">投</span>${d.pitcher || '---'}</span>
                    <span><span class="b-label">捕</span>${d.catcher || '---'}</span>
                  </div>
                </div>
              </div>`;
          } else {
            mainHtml += `
              <div class="grade-slot">
                <div class="slot-header"><span class="grade-label">${g}年生</span></div>
                <div class="empty-slot">予定なし</div>
              </div>`;
          }
        });
        document.getElementById('mainContainer').innerHTML = mainHtml;
      } catch (e) {
        document.getElementById('mainContainer').innerHTML =
          '<div style="text-align:center; color:red; padding:20px;">読み込みエラーが発生しました。</div>';
        console.error("Fetch Error:", e);
      }
    }
  </script>
</body>

</html>