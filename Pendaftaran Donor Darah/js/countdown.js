document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-countdown]').forEach((element) => {
    const target = new Date(element.dataset.countdown);
    const update = () => {
      const diff = target - new Date();
      if (diff <= 0) {
        element.textContent = 'Sedang berlangsung';
        return;
      }
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((diff / (1000 * 60)) % 60);
      element.textContent = `${days}d ${hours}j ${minutes}m`;
    };
    update();
    setInterval(update, 60000);
  });
});
