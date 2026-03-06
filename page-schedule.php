<?php
/* Template Name: LINEスケジュール表示 */
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>チームスケジュール | 常盤平ボーイズ</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f0f2f5;
      margin: 0;
      padding: 15px;
      color: #333;
    }

    h1 {
      text-align: center;
      color: #1a73e8;
      font-size: 22px;
      border-bottom: 2px solid #1a73e8;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }

    /* 週間カレンダー */
    .week-container {
      display: flex;
      gap: 5px;
      overflow-x: auto;
      padding-bottom: 10px;
      margin-bottom: 20px;
      -webkit-overflow-scrolling: touch;
    }

    .week-day {
      flex: 0 0 60px;
      background: white;
      border-radius: 8px;
      padding: 10px 5px;
      text-align: center;
      border: 1px solid #ddd;
    }

    .week-day.active {
      background: #1a73e8;
      color: white;
      border-color: #1a73e8;
    }

    .w-date {
      font-size: 14px;
      font-weight: bold;
    }

    .w-day {
      font-size: 11px;
      margin-top: 2px;
    }

    .w-dots {
      display: flex;
      justify-content: center;
      gap: 2px;
      margin-top: 5px;
      height: 6px;
    }

    .w-dot {
      width: 5px;
      height: 5px;
      border-radius: 50%;
    }

    /* 大会情報ボタン */
    .btn-tourn-view {
      width: 100%;
      background: #7b1fa2;
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
      cursor: pointer;
    }

    /* スケジュールリスト */
    .day-block {
      margin-bottom: 25px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      background: white;
    }

    .date-header {
      background: #1a73e8;
      color: white;
      font-size: 18px;
      font-weight: bold;
      padding: 12px 15px;
    }

    .day-content {
      padding: 15px;
    }

    .grade-section {
      margin-bottom: 20px;
      border-bottom: 1px dashed #eee;
      padding-bottom: 15px;
    }

    .grade-section:last-child {
      border-bottom: none;
    }

    .grade-badge {
      color: white;
      font-size: 13px;
      padding: 4px 12px;
      border-radius: 15px;
      font-weight: bold;
      display: inline-block;
    }

    /* イベント行 */
    .event-row {
      display: flex;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 8px;
      background: #f8f9fa;
      border: 1px solid #eee;
    }

    .event-row.is-game {
      background: #e8f5e9;
      border: 1px solid #c8e6c9;
    }

    .ev-time {
      width: 65px;
      font-weight: bold;
      font-size: 13px;
      color: #555;
      flex-shrink: 0;
      line-height: 1.2;
    }

    .ev-info {
      flex: 1;
      margin-left: 10px;
    }

    .ev-title {
      font-weight: bold;
      font-size: 15px;
      margin-bottom: 4px;
      display: block;
    }

    .ev-place {
      font-size: 13px;
      color: #1a73e8;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-youtube {
      background: #ff0000;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 5px;
    }

    .note-box {
      margin-top: 10px;
      background: #fff9c4;
      padding: 10px;
      border-radius: 6px;
      font-size: 13px;
      color: #444;
      border-left: 4px solid #fbc02d;
      white-space: pre-wrap;
    }

    .weather-box {
      background: #fffde7;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
      font-size: 13px;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* モーダル */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 2000;
      display: none;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .modal-content {
      background: white;
      width: 100%;
      max-width: 350px;
      border-radius: 12px;
      padding: 20px;
      max-height: 80vh;
      overflow-y: auto;
    }

    .grade-select-btn {
      width: 100%;
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 8px;
      border: 1px solid #ddd;
      background: #f8f9fa;
      font-weight: bold;
      cursor: pointer;
    }

    .tourn-item {
      border-left: 4px solid #7b1fa2;
      background: #f3e5f5;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .tourn-link {
      display: inline-block;
      margin-top: 5px;
      margin-right: 10px;
      color: #1a73e8;
      font-size: 13px;
      font-weight: bold;
      text-decoration: none;
    }

    .loading {
      text-align: center;
      color: #888;
      margin-top: 40px;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <h1>チームスケジュール</h1>

  <div class="week-container" id="weekCalendar"></div>

  <div id="scheduleContainer">
    <div class="loading">予定を読み込み中...</div>
  </div>

  <div id="gradeModal" class="modal-overlay">
    <div class="modal-content">
      <h3 style="margin-top:0; color:#7b1fa2; text-align:center;">学年を選択してください</h3>
      <div id="gradeList">
        <button class="grade-select-btn" onclick="showTournaments('6年生')">6年生</button>
        <button class="grade-select-btn" onclick="showTournaments('5年生')">5年生</button>
        <button class="grade-select-btn" onclick="showTournaments('4年生')">4年生</button>
        <button class="grade-select-btn" onclick="showTournaments('3年生以下')">3年生以下</button>
        <button class="grade-select-btn" onclick="showTournaments('全学年')">全学年</button>
      </div>
      <button onclick="closeGradeModal()"
        style="width:100%; margin-top:10px; border:none; background:none; color:#888;">閉じる</button>
    </div>
  </div>
  <button class="btn-tourn-view" onclick="openGradeSelectModal()">🏆 大会日程・結果を見る</button>

  <div id="tournInfoModal" class="modal-overlay">
    <div class="modal-content">
      <h3 id="tournModalTitle"
        style="margin-top:0; color:#7b1fa2; border-bottom:2px solid #7b1fa2; padding-bottom:10px;">大会情報</h3>
      <div id="tournModalBody" style="margin-top:15px;"></div>
      <button onclick="closeTournInfoModal()"
        style="width:100%; margin-top:20px; padding:12px; background:#f0f2f5; border:none; border-radius:8px; font-weight:bold;">閉じる</button>
    </div>
    <div class="footer-credit">Product by Team てくてく</div>
  </div>

  <script>
    const GAS_URL =
      "https://script.google.com/macros/s/AKfycbynEWVzWql1hxIWn4yi6Z9JasR_uQ6Ct4F_JCdvuElP7t34NrCdtq4DzQpZH-quJKTz/exec?mode=schedule_all";

    let weatherData = {};
    let allSchedules = [];
    let allCalEvents = [];
    let allTournaments = [];
    const gradeOrder = {
      "全学年": 7,
      "6": 6,
      "5": 5,
      "4": 4,
      "3": 3,
      "2": 2,
      "1": 1
    };

    window.onload = async function() {
      // 天気予報（松戸市）と注意喚起メッセージ生成
      try {
        const res = await fetch(
          'https://api.open-meteo.com/v1/forecast?latitude=35.7897&longitude=139.9022&daily=weathercode,temperature_2m_max,temperature_2m_min&timezone=Asia%2FTokyo'
        );
        const data = await res.json();
        for (let i = 0; i < data.daily.time.length; i++) {
          let dateStr = data.daily.time[i].split('-').join('/');
          let maxTemp = Math.round(data.daily.temperature_2m_max[i]);
          let code = data.daily.weathercode[i];

          let alertMsg = "";
          if (maxTemp >= 30) alertMsg = "⚠️【熱中症警戒】30℃超えの予報です。多めの水分と塩分を持参してください。";
          else if (maxTemp <= 8) alertMsg = "❄️【防寒対策】最高気温が低いです。ベンチコート等の防寒着を準備してください。";
          else if ([51, 53, 55, 61, 63, 65, 80, 81, 82].includes(code)) alertMsg =
            "☔【雨天注意】雨の予報が出ています。着替えと雨具の用意をしてください。";

          weatherData[dateStr] = {
            max: maxTemp,
            min: Math.round(data.daily.temperature_2m_min[i]),
            code: code,
            alert: alertMsg
          };
        }
      } catch (e) {
        console.error("Weather Error:", e);
      }
      loadData();
    };

    async function loadData() {
      try {
        const res = await fetch(GAS_URL, {
          redirect: "follow"
        });
        const data = await res.json();
        allSchedules = data.schedules || [];
        allCalEvents = data.calendars || [];
        allTournaments = data.tournaments || [];

        renderWeekCalendar();
        renderList();
      } catch (e) {
        document.getElementById('scheduleContainer').innerHTML = "データの読み込みに失敗しました。";
      }
    }

    function getDotColor(grade) {
      if (grade === '6') return '#f57c00';
      if (grade === '5') return '#388e3c';
      if (grade === '4') return '#d32f2f';
      return '#1a73e8';
    }

    function renderWeekCalendar() {
      const container = document.getElementById('weekCalendar');
      container.innerHTML = "";
      const days = ["日", "月", "火", "水", "木", "金", "土"];
      const today = new Date();

      for (let i = 0; i < 7; i++) {
        let d = new Date();
        d.setDate(today.getDate() + i);
        let m = (d.getMonth() + 1).toString().padStart(2, '0');
        let dNum = d.getDate().toString().padStart(2, '0');
        let dateStr = `${d.getFullYear()}/${m}/${dNum}`;

        let dots = "";
        let daySchs = allSchedules.filter(s => (s['日付'] || s.date) === dateStr);
        let uniqueGrades = [...new Set(daySchs.map(e => e['学年'] || e.grade))];
        uniqueGrades.forEach(g => {
          dots += `<div class="w-dot" style="background:${getDotColor(g)}"></div>`;
        });

        const div = document.createElement('div');
        div.className = `week-day ${i === 0 ? 'active' : ''}`;
        div.innerHTML =
          `<div class="w-date">${d.getDate()}</div><div class="w-day">${days[d.getDay()]}</div><div class="w-dots">${dots}</div>`;
        container.appendChild(div);
      }
    }

    function renderList() {
      let container = document.getElementById('scheduleContainer');
      let html = "";
      const days = ["日", "月", "火", "水", "木", "金", "土"];
      let today = new Date();
      today.setHours(0, 0, 0, 0);

      let futureSchedules = allSchedules.filter(s => {
        let sDateStr = s['日付'] || s.date;
        if (!sDateStr) return false;
        let d = new Date(sDateStr);
        d.setHours(0, 0, 0, 0);
        return d >= today;
      });

      if (futureSchedules.length === 0) {
        container.innerHTML = '<div class="loading">直近のスケジュールはありません。</div>';
        return;
      }

      const grouped = {};
      futureSchedules.forEach(s => {
        let sDate = s['日付'] || s.date;
        if (!grouped[sDate]) grouped[sDate] = [];
        grouped[sDate].push(s);
      });

      const uniqueDates = Object.keys(grouped).sort((a, b) => new Date(a) - new Date(b));

      uniqueDates.forEach(dateStr => {
        let d = new Date(dateStr);
        let displayDate = `${d.getMonth()+1}月${d.getDate()}日(${days[d.getDay()]})`;

        // 天気予報エリア
        let wHtml = "";
        let w = weatherData[dateStr];
        if (w) {
          let icon = w.code <= 1 ? "☀️" : ([2, 3].includes(w.code) ? "☁️" : "☔");
          wHtml =
            `<div class="weather-box"><div style="font-size:24px;">${icon}</div><div>松戸市: ${w.max}℃ / ${w.min}℃</div></div>`;
          if (w.alert) wHtml +=
            `<div style="background:#fff5f5; border:1px solid #feb2b2; color:#c53030; padding:10px; border-radius:6px; margin-bottom:15px; font-size:12px; font-weight:bold; line-height:1.4;">${w.alert}</div>`;
        }

        // 学年順ソート
        grouped[dateStr].sort((a, b) => (gradeOrder[b['学年'] || b.grade] || 0) - (gradeOrder[a['学年'] || a.grade] ||
          0));

        let gradesHtml = "";
        grouped[dateStr].forEach(s => {
          let sGrade = s['学年'] || s.grade;
          let pd = {};
          try {
            pd = JSON.parse(s['予定データ'] || s.scheduleData || "{}");
          } catch (e) {}
          let evs = pd.events || [];

          let evHtml = evs.length > 0 ? evs.map(ev => {
              let isGame = ev.type === '試合';
              return `
              <div class="event-row ${isGame ? 'is-game' : ''}">
                <div class="ev-time">${ev.time ? ev.time.replace('〜', '<br>〜') : ""}</div>
                <div class="ev-info">
                  <span class="ev-title">${isGame ? '⚾️ ' + ev.title : '🏃 ' + ev.title}</span>
                  ${ev.place ? `<span class="ev-place" onclick="window.open('${ev.url}')">📍 ${ev.place}</span>` : ""}
                  ${ev.youtube ? `<br><button onclick="window.open('${ev.youtube}')" class="btn-youtube">📺 配信</button>` : ""}
                </div>
              </div>`;
            }).join("") :
            `<div class="event-row"><div class="ev-info"><span class="ev-title">${s['内容'] || s.content}</span></div></div>`;

          gradesHtml += `
            <div class="grade-section">
              <span class="grade-badge" style="background:${getDotColor(sGrade)}">${sGrade}年生</span>
              <div style="margin-top:10px;">${evHtml}</div>
              ${(s['備考'] || s.note) ? `<div class="note-box">${s['備考'] || s.note}</div>` : ''}
            </div>`;
        });

        html +=
          `<div class="day-block"><div class="date-header">📅 ${displayDate}</div><div class="day-content">${wHtml}${gradesHtml}</div></div>`;
      });
      container.innerHTML = html;
    }

    function openGradeSelectModal() {
      document.getElementById('gradeModal').style.display = 'flex';
    }

    function closeGradeModal() {
      document.getElementById('gradeModal').style.display = 'none';
    }

    function closeTournInfoModal() {
      document.getElementById('tournInfoModal').style.display = 'none';
    }

    function showTournaments(grade) {
      closeGradeModal();
      const body = document.getElementById('tournModalBody');
      document.getElementById('tournModalTitle').innerText = grade + " の大会情報";
      const filtered = allTournaments.filter(t => t['学年'] === grade || t['学年'] === '全学年');
      body.innerHTML = filtered.length === 0 ? '<div style="text-align:center;padding:20px;">登録なし</div>' : filtered.map(
        t => `
        <div class="tourn-item">
          <div style="font-weight:bold;">${t['大会名']}</div>
          <div style="font-size:12px; color:#666;">📅 ${t['開始日']} 〜 ${t['終了予定日']}</div>
          ${t.URL ? `<a href="${t.URL}" target="_blank" class="tourn-link">🌐 公式HP</a>` : ''}
          ${t.PDF_URL ? `<a href="${t.PDF_URL}" target="_blank" class="tourn-link">📄 PDF</a>` : ''}
        </div>`).join("");
      document.getElementById('tournInfoModal').style.display = 'flex';
    }
  </script>
</body>

</html>