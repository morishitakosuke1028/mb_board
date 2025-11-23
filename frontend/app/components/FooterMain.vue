<template>
  <footer class="footer">
    <div class="footer-inner">

      <div class="footer-section">
        <h4>自治体掲示板</h4>
        <p class="desc">
          宇治市の学びの情報を発信するポータルサイトです。
        </p>
      </div>

      <div class="footer-section">
        <h4>メニュー</h4>
        <ul>
          <li><NuxtLink to="/courses">講座・教室をさがす</NuxtLink></li>
          <li><NuxtLink to="/news">お知らせ</NuxtLink></li>
          <li><NuxtLink to="/talent">人材データバンク</NuxtLink></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>運営</h4>
        <p>宇治市　教育委員会</p>
        <p>〒000-0000 京都府宇治市〇〇〇〇</p>
        <p>TEL：000-000-0000</p>
      </div>
    </div>

    <div class="copy">
      © 2025 MBBoard-example. All Rights Reserved.
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #2a2a2a;
  color: #fff;
  padding: 50px 20px 20px;
  margin-top: 80px;
}

.footer-inner {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  justify-content: space-between;
}

.footer-section {
  flex: 1 1 200px;
}

.footer-section h4 {
  font-size: 1.2rem;
  margin-bottom: 16px;
}

.footer-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-section ul li {
  margin-bottom: 8px;
}

.footer-section ul li a {
  color: #ddd;
  text-decoration: none;
}

.footer-section ul li a:hover {
  text-decoration: underline;
}

.desc {
  line-height: 1.6;
  color: #ddd;
  font-size: 0.9rem;
}

.copy {
  margin-top: 30px;
  text-align: center;
  padding-top: 10px;
  border-top: 1px solid #444;
  font-size: 0.85rem;
}
</style>
