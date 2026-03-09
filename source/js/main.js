
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

/* --- FAQ аккордеон --- */
document.addEventListener('click', e => {
	const btn = e.target.closest('[class*="__question"]');
	if (!btn) return;

	const item   = btn.closest('[class*="__item"]');
	const answer = item?.querySelector('[class*="__answer"]');
	if (!item || !answer) return;

	const isOpen = btn.getAttribute('aria-expanded') === 'true';

	/* закрываем все в пределах того же списка */
	const list = item.closest('[class*="__list"]');
	list?.querySelectorAll('[class*="__item"]').forEach(i => {
		i.querySelector('[class*="__question"]')?.setAttribute('aria-expanded', 'false');
		const a = i.querySelector('[class*="__answer"]');
		if (a) a.hidden = true;
		i.classList.remove('is-open');
	});

	/* переключаем текущий */
	if (!isOpen) {
		btn.setAttribute('aria-expanded', 'true');
		answer.hidden = false;
		item.classList.add('is-open');
	}
});