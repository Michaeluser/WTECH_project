document.querySelectorAll('[data-product-gallery]').forEach((gallery) => {
  const mainImage = gallery.querySelector('[data-gallery-main]');
  const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
  const prevButton = gallery.querySelector('.product-gallery-nav-prev');
  const nextButton = gallery.querySelector('.product-gallery-nav-next');

  if (!mainImage || thumbs.length === 0) {
    return;
  }

  let activeIndex = thumbs.findIndex((thumb) => thumb.classList.contains('product-gallery-thumb-active'));

  if (activeIndex < 0) {
    activeIndex = 0;
    thumbs[0].classList.add('product-gallery-thumb-active');
  }

  const setActiveImage = (index) => {
    const normalizedIndex = (index + thumbs.length) % thumbs.length;

    activeIndex = normalizedIndex;
    mainImage.src = thumbs[normalizedIndex].dataset.image;

    thumbs.forEach((thumb, thumbIndex) => {
      thumb.classList.toggle('product-gallery-thumb-active', thumbIndex === normalizedIndex);
    });
  };

  thumbs.forEach((thumb, index) => {
    thumb.addEventListener('click', () => {
      setActiveImage(index);
    });
  });

  if (prevButton) {
    prevButton.addEventListener('click', () => {
      setActiveImage(activeIndex - 1);
    });
  }

  if (nextButton) {
    nextButton.addEventListener('click', () => {
      setActiveImage(activeIndex + 1);
    });
  }
});
