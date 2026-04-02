const slides = document.querySelectorAll('.slide');
const btnPrev = document.querySelector('.slider-btn--prev');
const btnNext = document.querySelector('.slider-btn--next');
let currentIndex = 0;
function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');}
btnNext.addEventListener('click', function() {
    currentIndex++;
    if (currentIndex >= slides.length) {
        currentIndex = 0;}
    showSlide(currentIndex);});
btnPrev.addEventListener('click', function() {
    currentIndex--;
    if (currentIndex < 0) {
        currentIndex = slides.length - 1;}
    showSlide(currentIndex);
});
showSlide(0);
