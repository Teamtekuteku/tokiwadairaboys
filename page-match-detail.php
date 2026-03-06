<?php
/*
Template Name: Match Detail
*/
get_header(); ?>

<main class="l-main-content">
  <style>
  .p-match-detail {
    padding: 20px 15px;
    max-width: 800px;
    margin: 0 auto;
    font-family: sans-serif;
  }

  .match-header {
    text-align: center;
    margin-bottom: 25px;
  }

  .match-date {
    font-size: 14px;
    color: #666;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .match-title {
    font-size: 22px;
    font-weight: 800;
    color: #1a73e8;
    margin-bottom: 8px;
  }

  .match-ground {
    font-size: 12px;
    color: #1a73e8;
    background: #e3f2fd;
    padding: 5px 15px;
    border-radius: 20px;
    display: inline-block;
  }

  /* スコアボード */
  .score-table-wrap {
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow-x: auto;
  }

  .score-table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
  }

  .score-table th {
    font-size: 12px;
    color: #888;
    padding: 8px;
    border-bottom: 1px solid #eee;
  }

  .score-table td {
    padding: 12px 8px;
    border-bottom: 1px solid #eee;
    font-weight: bold;
  }

  .team-name {
    text-align: left;
    min-width: 120px;
    color: #333;
  }

  .total-score {
    font-size: 20px;
    color: #d32f2f;
    background: #fff5f5;
  }

  /* ハイライトエリア */
  .highlight-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
    margin-bottom: 25px;
  }

  .info-box {
    background: white;
    border-radius: 10px;
    padding: 15px;
    border-left: 5px solid #1a73e8;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  }

  .info-title {
    font-size: 12px;
    color: #888;
    font-weight: bold;
    margin-bottom: 8px;
  }

  .battery-row {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
    font-weight: bold;
  }

  .b-badge {
    background: #d32f2f;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    margin-right: 10px;
  }

  /* 個人成績タブ */
  .tab-container {
    display: flex;
    background: #eee;
    border-radius: 8px;
    padding: 4px;
    margin-bottom: 20px;
  }

  .tab {
    flex: 1;
    text-align: center;
    padding: 12px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 6px;
    transition: 0.3s;
  }

  .tab.is-active {
    background: white;
    color: #1a73e8;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .box-score-wrap {
    overflow-x: auto;
    background: white;
    border-radius: 10px;
    border: 1px solid #eee;
    margin-bottom: 25px;
  }

  .bs-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  .bs-table th {
    background: #f8f9fa;
    padding: 10px;
    font-size: 11px;
    color: #666;
  }

  .bs-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
  }

  .sticky-col {
    position: sticky;
    left: 0;
    background: white;
    font-weight: bold;
    border-right: 1px solid #eee;
    text-align: left;
  }

  .loading-msg {
    text-align: center;
    padding: 50px;
    font-weight: bold;
    color: #999;
  }
  </style>

  <div class="p-match-detail" id="js-match-detail">
    <div class="loading-msg">データを読み込み中...</div>
  </div>
</main>

<script>
(function() {
  const params = new URLSearchParams(window.location.search);
  const rowIdx = params.get('row');
  const GAS_URL =
    "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?mode=results_api";

  let gameStatsObj = {};

  if (!rowIdx) {
    document.getElementById('js-match-detail').innerHTML = '<p class="loading-msg">試合データが指定されていません。</p>';
    return;
  }

  async function fetchDetail() {
    try {
      const response = await fetch(GAS_URL, {
        redirect: "follow"
      });
      const allData = await response.json();
      const d = allData.find(item => String(item.rowIdx) === String(rowIdx));

      if (!d) {
        document.getElementById('js-match-detail').innerHTML = '<p class="loading-msg">試合データが見つかりません。</p>';
        return;
      }
      render(d);
    } catch (e) {
      console.error(e);
      document.getElementById('js-match-detail').innerHTML = '<p class="loading-msg">データの取得に失敗しました。</p>';
    }
  }

  function render(d) {
    try {
      gameStatsObj = JSON.parse(d["詳細データ(JSON)"] || "{}");
    } catch (e) {
      gameStatsObj = {};
    }

    const container = document.getElementById('js-match-detail');

    // 1. イニングデータの抽出
    var awayInnings = [];
    var homeInnings = [];
    if (d.innings) {
      if (d.innings.away) awayInnings = d.innings.away;
      if (d.innings.home) homeInnings = d.innings.home;
    }
    var maxInn = Math.max(awayInnings.length, homeInnings.length, 7);

    // 2. イニングセルのHTML作成
    var headInningCells = "";
    var awayInningCells = "";
    var homeInningCells = "";
    for (var i = 0; i < maxInn; i++) {
      headInningCells += "<th>" + (i + 1) + "</th>";
      awayInningCells += "<td>" + (awayInnings[i] !== undefined ? awayInnings[i] : '') + "</td>";
      homeInningCells += "<td>" + (homeInnings[i] !== undefined ? homeInnings[i] : '') + "</td>";
    }

    // 3. ハイライト/AI総評の作成
    var sluggerHtml = "";
    if (d.長打者) {
      sluggerHtml = '<div class="info-box" style="border-left-color: #f57c00;">' +
        '<div class="info-title">💥 長打・本塁打</div>' +
        '<div class="battery-row">' + d.長打者 + '</div>' +
        '</div>';
    }

    var aiReviewHtml = "";
    if (d.AI総評) {
      aiReviewHtml = '<div class="info-box" style="border-left: none; background: #fffde7; margin-bottom: 25px;">' +
        '<div class="info-title">🤖 AI 試合サマリー</div>' +
        '<div style="font-size: 14px; line-height: 1.6;">' + d.AI総評.replace(/\n/g, '<br>') + '</div>' +
        '</div>';
    }

    // 4. HTML組み立て (バッククォート内を極力シンプルに)
    var scoreAwayTotal = d.score ? d.score.split('-')[0].trim() : "0";
    var scoreHomeTotal = d.score ? d.score.split('-')[1].trim() : "0";

    var html = '<div class="match-header">' +
      '<div class="match-date">📅 ' + (d.date || '') + '</div>' +
      '<div class="match-title">' + (d.tournament || '練習試合') + '</div>' +
      '<div class="match-ground">📍 ' + (d.グランド名 || '場所未定') + '</div>' +
      '</div>' +

      '<div class="score-table-wrap">' +
      '<table class="score-table">' +
      '<thead><tr><th class="team-name">チーム</th>' + headInningCells + '<th>計</th></tr></thead>' +
      '<tbody>' +
      '<tr><td class="team-name">' + (d.先攻名 || '常盤平B') + '</td>' + awayInningCells + '<td class="total-score">' +
      scoreAwayTotal + '</td></tr>' +
      '<tr><td class="team-name">' + (d.opp || '相手チーム') + '</td>' + homeInningCells + '<td class="total-score">' +
      scoreHomeTotal + '</td></tr>' +
      '</tbody></table></div>' +

      '<div class="highlight-grid">' +
      '<div class="info-box">' +
      '<div class="info-title">⚾️ バッテリー</div>' +
      '<div class="battery-row"><span class="b-badge">投</span>' + (d.pitcher || '---') + '</div>' +
      '<div class="battery-row"><span class="b-badge">捕</span>' + (d.catcher || '---') + '</div>' +
      '</div>' +
      sluggerHtml +
      '</div>' +
      aiReviewHtml +

      '<div class="section-title" style="font-weight:bold; margin-bottom:10px; border-bottom:2px solid #1a73e8; padding-bottom:5px;">📊 個人成績</div>' +
      '<div class="tab-container">' +
      '<div id="tab-own" class="tab is-active" onclick="window.switchTeam(\'own\')">常盤平B</div>' +
      '<div id="tab-opp" class="tab" onclick="window.switchTeam(\'opp\')">相手チーム</div>' +
      '</div>' +
      '<div id="js-box-score"></div>';

    container.innerHTML = html;
    window.switchTeam('own');
  }

  window.switchTeam = function(type) {
    var tabOwn = document.getElementById('tab-own');
    var tabOpp = document.getElementById('tab-opp');
    if (tabOwn && tabOpp) {
      tabOwn.classList.toggle('is-active', type === 'own');
      tabOpp.classList.toggle('is-active', type === 'opp');
    }
    renderBoxScore(type);
  };

  function renderBoxScore(type) {
    const area = document.getElementById('js-box-score');
    const stats = Object.values(gameStatsObj).filter(function(st) {
      const isOpp = st.name && st.name.includes("相手");
      return type === 'own' ? !isOpp : isOpp;
    });

    if (stats.length === 0) {
      area.innerHTML = '<p style="text-align:center; padding:20px; color:#999;">成績データがありません。</p>';
      return;
    }

    var battingRows = "";
    for (var i = 0; i < stats.length; i++) {
      var st = stats[i];
      battingRows += '<tr>' +
        '<td class="sticky-col">' + (st.name || "") + '</td>' +
        '<td>' + (st.ab || 0) + '</td>' +
        '<td style="font-weight:bold; color:#d32f2f;">' + (st.h || 0) + '</td>' +
        '<td>' + (st.rbi || 0) + '</td>' +
        '<td>' + (st.bb || 0) + '</td>' +
        '<td>' + (st.k || 0) + '</td>' +
        '<td>' + (st.sb || 0) + '</td>' +
        '</tr>';
    }

    let bHtml = '<div style="font-size:13px; font-weight:bold; color:#1a73e8; margin-bottom:5px;">【打撃】</div>' +
      '<div class="box-score-wrap"><table class="bs-table"><thead>' +
      '<tr><th class="sticky-col">選手</th><th>打数</th><th>安打</th><th>打点</th><th>四死</th><th>三振</th><th>盗塁</th></tr>' +
      '</thead><tbody>' + battingRows + '</tbody></table></div>';

    const pitchers = stats.filter(function(st) {
      return st.ip > 0;
    });
    if (pitchers.length > 0) {
      var pRows = "";
      for (var j = 0; j < pitchers.length; j++) {
        var pst = pitchers[j];
        pRows += '<tr>' +
          '<td class="sticky-col">' + (pst.name || "") + '</td>' +
          '<td>' + (pst.ip || 0) + '</td>' +
          '<td>' + (pst.pitches || 0) + '</td>' +
          '<td>' + (pst.k_p || 0) + '</td>' +
          '<td>' + (pst.bb_p || 0) + '</td>' +
          '<td>' + (pst.r || 0) + '</td>' +
          '</tr>';
      }
      bHtml += '<div style="font-size:13px; font-weight:bold; color:#d32f2f; margin-bottom:5px;">【投手】</div>' +
        '<div class="box-score-wrap"><table class="bs-table"><thead>' +
        '<tr><th class="sticky-col">選手</th><th>回</th><th>球数</th><th>三振</th><th>四死</th><th>失点</th></tr>' +
        '</thead><tbody>' + pRows + '</tbody></table></div>';
    }
    area.innerHTML = bHtml;
  }

  fetchDetail();
})();
</script>s

<?php get_footer(); ?>