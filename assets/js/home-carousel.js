document.addEventListener('DOMContentLoaded', function () {
  const trendingSwiper = new Swiper('.trending-products-swiper', {
    slidesPerView: 1,
    spaceBetween: 10,
    navigation: {
      nextEl: '.trending-swiper-next',
      prevEl: '.trending-swiper-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 40,
      },
    }
  });
});
