document.addEventListener("DOMContentLoaded", () => {
  // --- 共通変数 ---
  const body = document.body;

  // ==========================================
  // 1. ハンバーガーメニュー・ドロワー制御
  // ==========================================
  const hamburger = document.querySelector(".js-hamburger");
  const drawer = document.querySelector(".js-drawer");
  const closeBtn = document.querySelector(".js-drawer-close");
  const overlay = document.querySelector(".js-overlay");

  const toggleMenu = (e) => {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    if (!drawer) return;

    const isActive = drawer.classList.contains("is-active");

    if (!isActive) {
      hamburger?.classList.add("is-active");
      drawer.classList.add("is-active");
      overlay?.classList.add("is-active");
      hamburger?.setAttribute("aria-expanded", "true");
      drawer.setAttribute("aria-hidden", "false");
      body.style.overflow = "hidden";
    } else {
      hamburger?.classList.remove("is-active");
      drawer.classList.remove("is-active");
      overlay?.classList.remove("is-active");
      hamburger?.setAttribute("aria-expanded", "false");
      drawer.setAttribute("aria-hidden", "true");
      body.style.overflow = "";
    }
  };

  if (hamburger) hamburger.addEventListener("click", toggleMenu);
  if (closeBtn) closeBtn.addEventListener("click", toggleMenu);
  if (overlay) overlay.addEventListener("click", toggleMenu);

  // ==========================================
  // 2. Instagramスライダー (Swiper)
  // ==========================================
  const instaSwiperElement = document.querySelector(".js-insta-swiper");
  if (instaSwiperElement) {
    const swiper = new Swiper(".js-insta-swiper", {
      loop: true,
      speed: 600,
      slidesPerView: 1,
      spaceBetween: 16,
      navigation: {
        nextEl: ".js-insta-next",
        prevEl: ".js-insta-prev",
      },
      breakpoints: {
        768: { slidesPerView: 3, spaceBetween: 24 },
        1024: { slidesPerView: 3, spaceBetween: 30 },
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

  iframe.onload = function () {
    loadCount++;
    if (loadCount > 1) {
      // 2回目の読み込み＝送信完了後の画面
      showModal();
    }
  };

  function showModal() {
    document.getElementById("js-modal").classList.add("is-show");
  }
});
