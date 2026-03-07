
/* Бургер */
const burger = document.getElementById('headerBurger');
const nav    = document.getElementById('headerNav');

burger.addEventListener('click', () => {
    nav.classList.toggle('is-open');
});

/* Дропдауны по клику */
document.querySelectorAll('.header__nav-item--dropdown').forEach(item => {
    item.querySelector('.header__nav-link').addEventListener('click', e => {
        e.preventDefault();
        const isOpen = item.classList.contains('is-open');
        /* Закрываем все остальные */
        document.querySelectorAll('.header__nav-item--dropdown').forEach(i => i.classList.remove('is-open'));
        /* Открываем текущий если был закрыт */
        if (!isOpen) item.classList.add('is-open');
    });
});

/* Клик вне — закрыть все */
document.addEventListener('click', e => {
    if (!e.target.closest('.header__nav-item--dropdown')) {
        document.querySelectorAll('.header__nav-item--dropdown').forEach(i => i.classList.remove('is-open'));
    }
});

/* Scroll to top */
const scrollBtn = document.getElementById('scrollTop');

window.addEventListener('scroll', () => {
	scrollBtn.classList.toggle('is-visible', window.scrollY > 200);
});

scrollBtn.addEventListener('click', () => {
	window.scrollTo({ top: 0, behavior: 'smooth' });
});

/* Попап — появляется через 5 сек, закрывается крестиком */
const popup      = document.getElementById('popup');
const popupClose = document.getElementById('popupClose');

setTimeout(() => {
	popup.classList.add('is-visible');
}, 5000);

popupClose.addEventListener('click', () => {
	popup.classList.remove('is-visible');
});