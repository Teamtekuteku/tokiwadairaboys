document.addEventListener("DOMContentLoaded", () => {
  // --- 共通変数 ---
  const body = document.body;

  // ==========================================
  // 1. ハンバーガーメニュー・ドロワー制御
  // ==========================================
  // WordPressのjQuery干渉を避け、DOMの読み込みを待つ
  (function () {
    const initMenu = () => {
      const body = document.body;
      const hamburger = document.querySelector(".js-hamburger");
      const drawer = document.querySelector(".js-drawer");
      const closeBtn = document.querySelector(".js-drawer-close");
      const overlay = document.querySelector(".js-overlay");

      // 要素が一つでも見つからなければエラーを防ぐために中断
      if (!hamburger || !drawer) {
        console.warn(
          "Tokiwadaira Boys: Hamburger or Drawer elements not found."
        );
        return;
      }

      // --- [1] ドロワー開閉 ---
      const toggleMenu = (e) => {
        // 念のためクリック時のバブリングを抑制
        if (e) e.stopPropagation();

        const isActive = drawer.classList.contains("is-active");

        if (!isActive) {
          hamburger.classList.add("is-active");
          drawer.classList.add("is-active");
          overlay?.classList.add("is-active");
          hamburger.setAttribute("aria-expanded", "true");
          drawer.setAttribute("aria-hidden", "false");
          body.style.overflow = "hidden";
        } else {
          hamburger.classList.remove("is-active");
          drawer.classList.remove("is-active");
          overlay?.classList.remove("is-active");
          hamburger.setAttribute("aria-expanded", "false");
          drawer.setAttribute("aria-hidden", "true");
          body.style.overflow = "";
        }
      };

      // イベント登録
      hamburger.addEventListener("click", toggleMenu);
      if (closeBtn) closeBtn.addEventListener("click", toggleMenu);
      if (overlay) overlay.addEventListener("click", toggleMenu);

      // --- [2] アコーディオン（メンバー紹介） ---
      const accordionTriggers = document.querySelectorAll(".js-drawer-trigger");

      accordionTriggers.forEach((trigger) => {
        trigger.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation(); // ドロワー自体のクリックイベントを発火させない

          const parent = this.closest(".js-drawer-accordion");
          const subList = this.nextElementSibling;

          if (!subList) return;

          parent.classList.toggle("is-open");

          if (parent.classList.contains("is-open")) {
            subList.style.height = subList.scrollHeight + "px";
            subList.style.opacity = "1";
            subList.style.visibility = "visible";
          } else {
            subList.style.height = "0";
            subList.style.opacity = "0";
            subList.style.visibility = "hidden";
          }
        });
      });
    };

    // DOMContentLoadedと同時に実行
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initMenu);
    } else {
      initMenu();
    }
  })();
  // ==========================================
  // 2. Instagramスライダー (Swiper)
  // ==========================================
  const instaSwiperElement = document.querySelector(".js-insta-swiper");

  if (instaSwiperElement) {
    // スライドの枚数を数える
    const slideCount =
      instaSwiperElement.querySelectorAll(".swiper-slide").length;

    // PCで3枚表示する場合、最低でも6枚程度（または3枚より多ければ）あればループ可能です
    // 安全を見て「表示枚数より多ければループする」設定にします
    const shouldLoop = slideCount > 3;

    const swiper = new Swiper(".js-insta-swiper", {
      loop: shouldLoop, // 枚数が足りる時だけループ有効
      speed: 600,
      slidesPerView: 1.2, // スマホで少し次が見えるようにすると「スワイプできる」ことが伝わりやすいです
      centeredSlides: true, // スマホでは中央寄せ
      spaceBetween: 16,

      // スライドが足りなくてループしない場合、矢印ボタンを消すか無効にする設定
      watchOverflow: true,

      navigation: {
        nextEl: ".js-insta-next",
        prevEl: ".js-insta-prev",
      },
      breakpoints: {
        768: {
          slidesPerView: 3,
          spaceBetween: 24,
          centeredSlides: false, // PCでは左詰めに
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
          centeredSlides: false,
        },
      },
    });
  }

  // ==========================================
  // 3. FAQアコーディオン (修正ポイント)
  // ==========================================
  const accordionItems = document.querySelectorAll(".js-faq-accordion");

  accordionItems.forEach((item) => {
    const trigger = item.querySelector(".js-faq-trigger");
    const content = item.querySelector(".js-faq-content");

    if (!trigger || !content) return;

    // 初期化：高さを0にしておく
    content.style.height = "0px";

    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      const isActive = item.classList.contains("is-active");

      if (isActive) {
        item.classList.remove("is-active");
        content.style.height = "0px";
      } else {
        item.classList.add("is-active");
        const scrollHeight = content.scrollHeight;
        content.style.height = scrollHeight + "px";
      }
    });
  });

  // ==========================================
  // 4. 試合結果 (JSON読み込み)
  // ==========================================
  const matchContainer = document.getElementById("js-match-results");

  if (matchContainer) {
    fetch("./assets/json/match-data.json")
      .then((response) => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
      })
      .then((data) => {
        let html = "";
        data.forEach((item) => {
          const resultClass = item.result.toLowerCase();
          html += `
          <div class="p-match__card">
            <div class="p-match__info">
              <span class="p-match__info-date">${item.date} | ${item.type}</span>
              <p class="p-match__info-opponent">vs ${item.opponent}</p>
            </div>
            <div class="p-match__score-wrap">
              <span class="p-match__score">${item.score}</span>
              <span class="p-match__label p-match__label--${resultClass}">${item.result}</span>
            </div>
          </div>
        `;
        });
        matchContainer.innerHTML = html;
      })
      .catch((error) => {
        console.error("Match data error:", error);
      });
  }
  // 簡易版：iframeが2回読み込まれたら「送信後」と判断するロジック
  const iframe = document.getElementById("js-form-iframe");
  let loadCount = 0;

  // 修正後：要素が存在するか確認してから実行する
  const contactIframe = document.querySelector("iframe"); // もしくは適切なID名
  if (contactIframe) {
    contactIframe.onload = function () {
      loadCount++;
      if (loadCount > 1) {
        // 2回目の読み込み＝送信完了後の画面
        showModal();
      }
    };
  }

  function showModal() {
    document.getElementById("js-modal").classList.add("is-show");
  }
});
document.addEventListener("DOMContentLoaded", () => {
  // main.js の中身
  const GAS_URL =
    "https://script.google.com/a/team-tekuteku.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?mode=results&key=team";
  const resultContainer = document.getElementById("js-match-results");
  const tabItems = document.querySelectorAll(".p-match__tab-item");

  if (!resultContainer) return;

  let allMatchData = []; // 全データを保持する変数

  // 1. GASからデータを取得
  fetch(GAS_URL)
    .then((response) => response.json())
    .then((data) => {
      allMatchData = data;
      // 最初に表示するのは「6年生（デフォルト）」のデータ
      updateMatchResults("6");
    })
    .catch((error) => {
      resultContainer.innerHTML = "<p>データが取得できませんでした。</p>";
      console.error("Error:", error);
    });

  // 2. タブ切り替えイベント
  tabItems.forEach((tab) => {
    tab.addEventListener("click", () => {
      // タブの見た目切り替え
      tabItems.forEach((item) => item.classList.remove("is-active"));
      tab.classList.add("is-active");

      // データの絞り込み表示
      const grade = tab.dataset.grade;
      updateMatchResults(grade);
    });
  });

  // 3. 表示を更新する関数
  function updateMatchResults(grade) {
    resultContainer.innerHTML = "";

    // 学年フィルタリング
    const filteredData = allMatchData.filter((item) => {
      const itemGrade = String(item.学年 || "");
      return itemGrade.includes(grade);
    });

    if (filteredData.length === 0) {
      resultContainer.innerHTML = `<p class="p-match__empty">${grade}年生の試合結果はまだありません。</p>`;
      return;
    }

    // 最新3件（またはPC2列を意識して4件など）を表示
    filteredData
      .reverse()
      .slice(0, 4)
      .forEach((match) => {
        const enemyName = match.相手チーム || "相手チーム";
        const scoreStr = match.スコア || "0-0";
        const resultMark = match.勝敗 || "-";

        // スコア抽出
        const scoreNumbers = scoreStr.match(/\d+/g) || [0, 0];
        let myScore = scoreNumbers[0];
        let enemyScore = scoreNumbers[1];

        // 自チーム（常盤平）が後攻だった場合の入れ替え判定
        if (scoreStr.indexOf("常盤平") > scoreStr.indexOf("-")) {
          myScore = scoreNumbers[1];
          enemyScore = scoreNumbers[0];
        }

        // --- 勝敗ラベルの判定 ---
        let labelClass = "";
        let labelText = "";

        if (resultMark === "勝" || resultMark === "○") {
          labelClass = "p-match__label--win";
          labelText = "WIN";
        } else if (resultMark === "負" || resultMark === "●") {
          labelClass = "p-match__label--lose";
          labelText = "LOSE";
        } else {
          labelClass = "p-match__label--draw";
          labelText = "DRAW";
        }

        // デザイナーさんのHTML構造へ
        const html = `
      <div class="p-match__card">
        <div class="p-match__info">
          <span class="p-match__info-date">${
            match.日付 || ""
          } <span class="p-match__separator">|</span> ${
          match.大会名 || "公式戦"
        }</span>
          <span class="p-match__info-opponent">vs ${enemyName}</span>
        </div>
        
        <div class="p-match__score-wrap">
          <div class="p-match__score">${myScore} - ${enemyScore}</div>
          <span class="p-match__label ${labelClass}">${labelText}</span>
        </div>
      </div>
    `;
        resultContainer.insertAdjacentHTML("beforeend", html);
      });
  }
});

// ==========================================
// 5.  メンバー読み込み (JSON読み込み)
// ==========================================

const GAS_API_URL =
  "https://script.google.com/a/team-tekuteku.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec";

async function loadMembers() {
  const playerContainer = document.getElementById("js-player-list");
  const staffContainer = document.getElementById("js-staff-list");
  if (!playerContainer && !staffContainer) return;

  try {
    const response = await fetch(`${GAS_API_URL}?mode=member_list&key=team`);
    const data = await response.json();

    if (playerContainer) {
      // 1. データを学年ごとにグループ化（数字だけで判定するように強化）
      const groupedPlayers = {};
      data.players.forEach((p) => {
        // 学年文字列から数字だけを抽出（例：「6年生」→「6」）
        const gradeNum = p.grade ? p.grade.replace(/[^0-9]/g, "") : "";
        const key = gradeNum ? gradeNum + "年生" : "その他";

        if (!groupedPlayers[key]) groupedPlayers[key] = [];
        groupedPlayers[key].push(p);
      });

      // 2. 各学年内を背番号順にソート
      Object.keys(groupedPlayers).forEach((key) => {
        groupedPlayers[key].sort(
          (a, b) => (parseInt(a.number) || 999) - (parseInt(b.number) || 999)
        );
      });

      // 3. ナビゲーションと出力エリアの作成
      const grades = [
        "全員",
        "6年生",
        "5年生",
        "4年生",
        "3年生",
        "2年生",
        "1年生",
      ];
      const navHtml = `
        <div class="p-members__nav">
          ${grades
            .map(
              (g) =>
                `<button class="p-members__nav-btn js-member-filter ${
                  g === "全員" ? "is-active" : ""
                }" data-grade="${g}">${g}</button>`
            )
            .join("")}
        </div>
        <div id="js-members-output"></div>
      `;
      playerContainer.innerHTML = navHtml;

      const output = document.getElementById("js-members-output");

      // 4. 描画関数
      const renderPlayers = (targetGrade) => {
        output.innerHTML = "";
        // 6年生から順にループして表示
        [
          "6年生",
          "5年生",
          "4年生",
          "3年生",
          "2年生",
          "1年生",
          "その他",
        ].forEach((gradeKey) => {
          if (
            (targetGrade === "全員" || targetGrade === gradeKey) &&
            groupedPlayers[gradeKey]
          ) {
            let sectionHtml = `
              <section class="p-members__grade-section">
                <h3 class="p-members__grade-title">${gradeKey}</h3>
                <div class="p-members__grid">
                  ${groupedPlayers[gradeKey]
                    .map(
                      (p) => `
                    <div class="p-members__card">
                      <div class="p-members__number">${p.number || "無"}</div>
                      <div class="p-members__info">
                        <h4 class="p-members__name">${
                          p.name
                        }<span class="p-members__kana">${
                        p.nickname ? "（" + p.nickname + "）" : ""
                      }</span></h4>
                        <p class="p-members__school">${p.school || ""}</p>
                      </div>
                    </div>
                  `
                    )
                    .join("")}
                </div>
              </section>`;
            output.insertAdjacentHTML("beforeend", sectionHtml);
          }
        });
      };

      // 初期表示
      renderPlayers("全員");

      // 5. フィルターボタンのイベント（委譲を使って確実にする）
      playerContainer.addEventListener("click", (e) => {
        const btn = e.target.closest(".js-member-filter");
        if (!btn) return;

        const targetGrade = btn.dataset.grade;
        renderPlayers(targetGrade);

        // ボタンの見た目更新
        document
          .querySelectorAll(".js-member-filter")
          .forEach((b) => b.classList.remove("is-active"));
        btn.classList.add("is-active");
      });
    }

    // スタッフ表示（変更なし）
    if (staffContainer) {
      staffContainer.innerHTML =
        `<div class="p-members__grid">` +
        data.staff
          .map(
            (s) => `
          <div class="p-members__card p-members__card--staff">
            <div class="p-members__info">
              <p class="p-members__role">${s.role || "スタッフ"}</p>
              <h4 class="p-members__name">${s.name}</h4>
            </div>
          </div>`
          )
          .join("") +
        `</div>`;
    }
  } catch (error) {
    console.error("メンバー読み込みエラー:", error);
  }
}

loadMembers();

// ==========================================
// 6. 試合アーカイブ (JSON読み込み)
// ==========================================

/**
 * 試合結果一覧（アーカイブ）をGASから読み込んで表示する
 */
async function loadMatchArchive() {
  const archiveContainer = document.getElementById("js-match-archive-list");
  const filterSelect = document.getElementById("js-filter-grade");

  // この要素がないページ（他ページ）では実行しない
  if (!archiveContainer) return;

  try {
    // GASからJSONデータを取得
    const response = await fetch(`${GAS_API_URL}?mode=results&key=team`);
    const allMatches = await response.json();

    // 描画用サブ関数
    const renderMatches = (filterGrade) => {
      archiveContainer.innerHTML = "";

      // フィルター処理
      const filtered = allMatches.filter((m) => {
        if (filterGrade === "all") return true;
        // 学年（m.grade）に選択された数字が含まれているか
        return m.grade && m.grade.includes(filterGrade);
      });

      if (filtered.length === 0) {
        archiveContainer.innerHTML =
          "<p class='p-match-archive__empty'>該当する試合結果がありません。</p>";
        return;
      }

      // カードを生成して追加
      filtered.forEach((m) => {
        // 勝敗に応じたクラス（○＝勝ち、●＝負け、その他＝引き分け）
        const isWin = m.result === "○" || m.result === "勝";
        const isLose = m.result === "●" || m.result === "負";
        const resultClass = isWin ? "is-win" : isLose ? "is-lose" : "is-draw";

        const html = `
          <div class="p-match-card ${resultClass}">
            <div class="p-match-card__meta">
              <span class="p-match-card__date">${m.date || ""}</span>
              <span class="p-match-card__grade">${m.grade || ""}</span>
            </div>
            <div class="p-match-card__tournament">${
              m.tournament || "練習試合"
            }</div>
            
            <div class="p-match-card__content">
              
              <div class="p-match-card__score">
                <span class="p-match-card__score-num">${m.score_own || 0}</span>
                <span class="p-match-card__score-hyphen">-</span>
                <span class="p-match-card__score-num">${m.score_opp || 0}</span>
              </div>
              
            </div>

            <div class="p-match-card__footer">
              ${
                m.pitcher
                  ? `<span class="p-match-card__player">投：${m.pitcher}</span>`
                  : ""
              }
              ${
                m.catcher
                  ? `<span class="p-match-card__player">捕：${m.catcher}</span>`
                  : ""
              }
            </div>
          </div>
        `;
        archiveContainer.insertAdjacentHTML("beforeend", html);
      });
    };

    // 初回実行（全件）
    renderMatches("all");

    // フィルターのセレクトボックスが変わった時の処理
    filterSelect?.addEventListener("change", (e) => {
      renderMatches(e.target.value);
    });
  } catch (error) {
    console.error("試合結果読み込みエラー:", error);
    archiveContainer.innerHTML = "<p>データの読み込みに失敗しました。</p>";
  }
}

// 最後に1回だけ呼び出し
loadMatchArchive();

// ==========================================
// 7.  スケジュール読み込み (JSON読み込み)
// ==========================================

function showDetails(dateStr) {
  // 画面上の日付表示を yyyy/mm/dd 形式に
  const displayDate = dateStr.replace(/-/g, "/");
  document.getElementById("selectedDateText").innerText =
    displayDate + " の予定";

  // シートのラベル「日付」でフィルタリング
  const dayData = allSchedules.filter(
    (s) => (s["日付"] || s.date) === displayDate
  );

  if (dayData.length === 0) {
    document.getElementById("detailsContent").innerHTML =
      "<p style='text-align:center; padding:20px; color:#999;'>予定はありません。</p>";
    return;
  }

  let html = "";
  dayData.forEach((s) => {
    // ラベル名「予定データ」をパース
    let pd = {};
    try {
      const jsonStr = s["予定データ"] || s.scheduleData || "{}";
      pd = JSON.parse(jsonStr);
    } catch (e) {
      console.error("JSONパースエラー:", e);
    }

    html += `<div style="margin-bottom:20px; border-bottom:2px solid #eee; padding-bottom:15px;">`;
    // ラベル名「学年」を表示
    const grade = s["学年"] || s.grade || "全学年";
    html += `<span class="info-label" style="background:#1a73e8; color:#fff; margin-bottom:10px;">${grade}年生</span>`;

    // 予定データ内のタイムラインを表示
    if (pd.events && pd.events.length > 0) {
      pd.events.forEach((ev) => {
        html += `
              <div class="detail-card">
                <div class="detail-time">${ev.time || ""}</div>
                <div class="detail-title">${
                  ev.type === "試合" ? "⚾️ " + ev.title : "🏃 " + ev.title
                }</div>
                ${
                  ev.place
                    ? `<a href="${ev.url}" class="detail-place" target="_blank">📍 ${ev.place}</a>`
                    : ""
                }
              </div>`;
      });
    } else {
      // 予定データがない場合、シートの「内容」を簡易表示
      const content = s["内容"] || s.content || "練習";
      html += `<div class="detail-card"><div class="detail-title">${content}</div></div>`;
    }

    // ラベル名「備考」を表示
    const note = s["備考"] || s.note;
    if (note) {
      html += `<div style="background:#f0f2f5; padding:10px; border-radius:5px; font-size:12px; margin-top:10px;">
                    <strong>📝 備考・持ち物:</strong><br>${note.replace(
                      /\n/g,
                      "<br>"
                    )}
                  </div>`;
    }
    html += `</div>`;
  });
  document.getElementById("detailsContent").innerHTML = html;
}

// カレンダーのドット判定も修正
function drawCalendar() {
  // ... (前略: カレンダー描画のループ内) ...
  let inputDate = `${currentYear}-${(currentMonth + 1)
    .toString()
    .padStart(2, "0")}-${day.toString().padStart(2, "0")}`;
  let checkDate = inputDate.replace(/-/g, "/");

  let dots = "";
  // シートのラベル「日付」で判定
  let daySchs = allSchedules.filter((s) => (s["日付"] || s.date) === checkDate);

  if (daySchs.length > 0) {
    // 学年ごとにドットを打つ
    let grades = [...new Set(daySchs.map((s) => s["学年"] || s.grade))];
    grades.forEach((g) => {
      dots += `<div class="cal-dot" style="background:${getDotColor(
        g
      )}"></div>`;
    });
  }
  // ... (後略) ...
}

function getDotColor(g) {
  if (g == "6") return "#f57c00";
  if (g == "5") return "#388e3c";
  if (g == "4") return "#d32f2f";
  return "#1a73e8"; // デフォルト
}
// ==========================================
// 7.  スケジュール読み込み (JSON読み込み)
// ==========================================
