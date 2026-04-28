(function () {
  var masthead = document.getElementById('masthead');
  if (!masthead) return;
  function updateOffset() {
    document.documentElement.style.setProperty('--header-h', masthead.offsetHeight + 'px');
  }
  updateOffset();
  window.addEventListener('resize', updateOffset);
})();

(function () {
  var btn = document.getElementById('search-toggle');
  var panel = document.getElementById('header-search');
  if (!btn || !panel) return;

  btn.addEventListener('click', function () {
    var open = panel.hasAttribute('hidden') ? true : false;
    if (open) {
      panel.removeAttribute('hidden');
      btn.setAttribute('aria-expanded', 'true');
      var input = panel.querySelector('input[type="search"]');
      if (input) input.focus();
    } else {
      panel.setAttribute('hidden', '');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !panel.hasAttribute('hidden')) {
      panel.setAttribute('hidden', '');
      btn.setAttribute('aria-expanded', 'false');
      btn.focus();
    }
  });

  document.addEventListener('click', function (e) {
    if (!panel.hasAttribute('hidden') && !panel.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
      panel.setAttribute('hidden', '');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();

(function () {
  var toggle = document.getElementById('nav-toggle');
  var nav = document.getElementById('site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open);
      toggle.classList.toggle('is-active', open);
    });
  }
})();

(function () {
  var track = document.getElementById('promo-track');
  if (!track) return;

  var clones = Array.from(track.children).map(function (el) {
    return el.cloneNode(true);
  });
  clones.forEach(function (clone) { track.appendChild(clone); });

  var speed = 0.5;
  var pos = 0;
  var isDragging = false;
  var dragStartX = 0;
  var dragStartPos = 0;
  var paused = false;

  function getHalfWidth() {
    return track.scrollWidth / 2;
  }

  function step() {
    if (!paused && !isDragging) {
      pos += speed;
      if (pos >= getHalfWidth()) pos -= getHalfWidth();
      track.style.transform = 'translateX(-' + pos + 'px)';
    }
    requestAnimationFrame(step);
  }

  requestAnimationFrame(step);

  track.addEventListener('mouseenter', function () { paused = true; });
  track.addEventListener('mouseleave', function () { paused = false; });

  track.addEventListener('mousedown', function (e) {
    isDragging = true;
    dragStartX = e.clientX;
    dragStartPos = pos;
  });

  document.addEventListener('mousemove', function (e) {
    if (!isDragging) return;
    var delta = dragStartX - e.clientX;
    pos = dragStartPos + delta;
    if (pos < 0) pos += getHalfWidth();
    if (pos >= getHalfWidth()) pos -= getHalfWidth();
    track.style.transform = 'translateX(-' + pos + 'px)';
  });

  document.addEventListener('mouseup', function () {
    isDragging = false;
    paused = false;
  });

  track.addEventListener('touchstart', function (e) {
    dragStartX = e.touches[0].clientX;
    dragStartPos = pos;
    paused = true;
  }, { passive: true });

  track.addEventListener('touchmove', function (e) {
    var delta = dragStartX - e.touches[0].clientX;
    pos = dragStartPos + delta;
    if (pos < 0) pos += getHalfWidth();
    if (pos >= getHalfWidth()) pos -= getHalfWidth();
    track.style.transform = 'translateX(-' + pos + 'px)';
  }, { passive: true });

  track.addEventListener('touchend', function () { paused = false; });
})();
