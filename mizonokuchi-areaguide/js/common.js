/* =================================================================
   MIZONOKUCHI AREA GUIDE / common.js
   依存ライブラリなし（jQuery 不要）
================================================================= */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ---------- ヒーローの画像クロスフェード ---------- */
    var heroes = document.querySelectorAll('.hero-image');
    if (heroes.length > 1) {
      var cur = 0;
      setInterval(function () {
        heroes[cur].classList.remove('is-active');
        cur = (cur + 1) % heroes.length;
        heroes[cur].classList.add('is-active');
      }, 5500);
    }

    /* ---------- スクロールプログレス / ページトップ ---------- */
    var bar  = document.getElementById('scrollbar');
    var pTop = document.querySelector('.pTop');

    function onScroll() {
      if (bar) {
        var h = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = (h > 0 ? (window.scrollY / h) * 100 : 0) + '%';
      }
      if (pTop) {
        pTop.classList.toggle('is-visible', window.scrollY > 400);
      }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* ---------- ページ内リンクのスムーススクロール（ヘッダー分オフセット） ---------- */
    var header = document.querySelector('.site-header');
    document.addEventListener('click', function (e) {
      var a = e.target.closest ? e.target.closest('a[href^="#"]') : null;
      if (!a) return;
      var href = a.getAttribute('href');
      if (!href || href === '#') return;
      var target;
      try { target = document.querySelector(href); } catch (err) { return; }
      if (!target) return;
      e.preventDefault();
      var offset = header ? header.offsetHeight : 0;
      var y = target.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({ top: y, behavior: 'smooth' });
    });

    /* ---------- ドロワーメニュー ---------- */
    var toggle = document.getElementById('menu-navibtn');
    if (toggle) {
      var navBtn = document.getElementById('navibtn');

      var sync = function () {
        document.body.classList.toggle('is-nav-open', toggle.checked);
        if (navBtn) navBtn.setAttribute('aria-expanded', toggle.checked ? 'true' : 'false');
      };
      toggle.addEventListener('change', sync);
      sync();

      // メニュー内のリンクを踏んだら閉じる
      var menu = document.getElementById('menu');
      if (menu) {
        menu.addEventListener('click', function (e) {
          if (e.target.closest('a')) { toggle.checked = false; sync(); }
        });
      }

      // Esc で閉じる
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && toggle.checked) { toggle.checked = false; sync(); }
      });

      // ハンバーガーを Enter / Space で操作できるように
      if (navBtn) {
        navBtn.setAttribute('role', 'button');
        navBtn.setAttribute('tabindex', '0');
        navBtn.setAttribute('aria-controls', 'menu');
        navBtn.addEventListener('keydown', function (e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle.checked = !toggle.checked;
            sync();
          }
        });
      }

      // PC 幅（横並びナビ）に戻ったら閉じる
      var mq = window.matchMedia('(min-width:1280px)');
      var onMq = function (ev) { if (ev.matches && toggle.checked) { toggle.checked = false; sync(); } };
      if (mq.addEventListener) { mq.addEventListener('change', onMq); }
      else if (mq.addListener) { mq.addListener(onMq); }
    }
  });
})();
