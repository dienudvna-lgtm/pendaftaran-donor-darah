// Shared site behavior for BloodConnect
const loadingScreen = document.querySelector('.loading-screen');
const backToTop = document.querySelector('.back-to-top');
const progressBar = document.querySelector('.scroll-progress');
const toggle = document.getElementById('themeToggle');
const mobileToggle = document.querySelector('.mobile-toggle');
const navLinks = document.querySelector('.nav-links');

if (loadingScreen) {
  window.addEventListener('load', () => {
    loadingScreen.classList.add('hidden');
    setTimeout(() => loadingScreen.remove(), 500);
  });
}

if (toggle) {
  const savedTheme = localStorage.getItem('bloodconnect-theme');
  if (savedTheme === 'dark') document.body.classList.add('dark');
  toggle.addEventListener('click', () => {
    document.body.classList.toggle('dark');
    const isDark = document.body.classList.contains('dark');
    localStorage.setItem('bloodconnect-theme', isDark ? 'dark' : 'light');
    showToast(isDark ? 'Mode gelap aktif' : 'Mode terang aktif');
  });
}

if (mobileToggle && navLinks) {
  mobileToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
  navLinks.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => navLinks.classList.remove('open')));
}

window.addEventListener('scroll', () => {
  const scrollTop = window.scrollY;
  const maxHeight = document.documentElement.scrollHeight - window.innerHeight;
  const progress = maxHeight > 0 ? scrollTop / maxHeight : 0;
  if (progressBar) progressBar.style.transform = `scaleX(${progress})`;
  if (backToTop) backToTop.classList.toggle('show', scrollTop > 600);
});

if (backToTop) {
  backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

function showToast(message) {
  const existing = document.querySelector('.toast');
  if (existing) existing.remove();
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.textContent = message;
  document.body.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 300);
  }, 2400);
}

function animateCounters() {
  const counters = document.querySelectorAll('[data-counter]');
  counters.forEach((counter) => {
    const target = Number(counter.dataset.counter);
    const duration = 1400;
    const start = performance.now();
    const step = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const value = Math.floor(progress * target);
      counter.textContent = `${value}+`;
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  });
}

if (document.querySelector('[data-counter]')) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animateCounters();
        observer.disconnect();
      }
    });
  });
  observer.observe(document.querySelector('[data-counter]'));
}

if (window.AOS) {
  AOS.init({ duration: 800, once: true, offset: 80 });
}
