const slides = document.querySelectorAll('.slide');
const btnPrev = document.querySelector('.slider-btn--prev');
const btnNext = document.querySelector('.slider-btn--next');
// Текущий индекс — начинаем с первого слайда
let currentIndex = 0;
// Функция показа нужного слайда
function showSlide(index) {
// Убираем класс active у всех слайдов
    slides.forEach(slide => slide.classList.remove('active'));
// Добавляем active только нужному
    slides[index].classList.add('active');}
// Кнопка ВПЕРЁД
btnNext.addEventListener('click', function() {
    currentIndex++;
// Если дошли до конца — возвращаемся на первый
    if (currentIndex >= slides.length) {
        currentIndex = 0;}
    showSlide(currentIndex);});
// Кнопка НАЗАД
btnPrev.addEventListener('click', function() {
    currentIndex--;
// Если ушли за начало — переходим на последний
    if (currentIndex < 0) {
        currentIndex = slides.length - 1;}
    showSlide(currentIndex);
});
// Показываем первый слайд при загрузке страницы
showSlide(0);
