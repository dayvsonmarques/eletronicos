(function () {
  // ─── Sticky header ──────────────────────────────────────────────────────────
  var masthead = document.getElementById('masthead');
  var sentinel = document.getElementById('after-banner-sentinel');

  if (masthead && sentinel) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          masthead.classList.add('is-sticky');
        } else {
          masthead.classList.remove('is-sticky');
        }
      });
    }, { threshold: 0 });

    observer.observe(sentinel);
  }

  // ─── Promo infinite slider ──────────────────────────────────────────────────
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
