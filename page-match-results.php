<?php get_header(); ?>

<main class="l-main-content">
  <section class="p-match-archive">
    <div class="l-section__match">
      <div class="c-title">
        <h2 class="c-title__en">MATCH RESULTS</h2>
        <span class="c-title__ja">試合結果一覧</span>
      </div>

      <div class="p-match-archive__tabs">
        <ul class="tab-list" id="js-tab-grade">
          <li class="tab-item active" data-grade="all">すべて</li>
          <li class="tab-item" data-grade="6">6年生</li>
          <li class="tab-item" data-grade="5">5年生</li>
          <li class="tab-item" data-grade="4">4年生</li>
          <li class="tab-item" data-grade="3">3年生</li>
          <li class="tab-item" data-grade="2">2年生</li>
        </ul>
      </div>

      <div id="js-match-archive-list" class="p-match-archive__list">
        <p class="loading-text">データを読み込んでいます...</p>
      </div>
    </div>
  </section>
</main>

<script>
(function() {
  const GAS_URL =
    "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?mode=results_api";
  let allResults = [];

  async function init() {
    try {
      const response = await fetch(GAS_URL, {
        redirect: "follow"
      });
      allResults = await response.json();
      render('all');

      // タブのクリックイベント
      const tabs = document.querySelectorAll('#js-tab-grade .tab-item');
      tabs.forEach(tab => {
        tab.addEventListener('click', function() {
          tabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');
          render(this.dataset.grade);
        });
      });
    } catch (e) {
      document.getElementById('js-match-archive-list').innerHTML = "データの取得に失敗しました。デプロイの更新を確認してください。";
    }
  }

  function render(gradeFilter) {
    const listArea = document.getElementById('js-match-archive-list');
    const filtered = allResults.filter(d => gradeFilter === 'all' || String(d.grade) === gradeFilter);

    if (filtered.length === 0) {
      listArea.innerHTML = '<p class="empty-text">該当する試合結果はありません</p>';
      return;
    }

    const html = filtered.map(d => {
      const isOfficial = d.tournament && d.tournament !== "";
      const resClass = d.result.includes("勝") ? "win" : (d.result.includes("負") ? "lose" : "draw");
      const resLabel = d.result.includes("勝") ? "★ 勝利" : (d.result.includes("負") ? "● 敗戦" : d.result);

      // 先攻・後攻の判定
      const firstTeam = d["先攻名"] || d["先行名"] || "常盤平B";
      const secondTeam = d["後攻名"] || d.opp || "相手チーム";

      // ★ここで詳細ページへのURLを作成
      const detailLink = `/match-detail/?row=${d.rowIdx}`;

      return `
        <article class="wp-card">
          <div class="wp-card-header">
            <span class="wp-card-date">📅 ${d.date}</span>
            <span class="wp-badge ${isOfficial ? 'badge-official' : 'badge-practice'}">${isOfficial ? d.tournament : '練習試合'}</span>
          </div>
          <div class="wp-card-body">
            <div class="wp-large-grade">${d.grade}年生</div>
            
            <div class="wp-match-display">
              <div class="wp-team-box first">${firstTeam}</div>
              <div class="wp-score-box">
                <span class="wp-score-num">${d.score}</span>
              </div>
              <div class="wp-team-box second">${secondTeam}</div>
            </div>
            
            <div class="wp-res-text ${resClass}">${resLabel}</div>
            
            <div class="wp-battery-center">
              <div class="battery-item"><span class="wp-b-mark">投</span>${d.pitcher || '---'}</div>
              <div class="battery-item"><span class="wp-b-mark">捕</span>${d.catcher || '---'}</div>
            </div>
          </div>
          <a href="${detailLink}" class="wp-btn-detail">試合詳細を表示 ＞</a>
        </article>
      `;
    }).join('');

    listArea.innerHTML = html;
  }

  init();
})();
</script>
<?php get_footer() ?>