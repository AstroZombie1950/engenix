/* Бургер */
const burger = document.getElementById('headerBurger');
const nav    = document.getElementById('headerNav');

burger.addEventListener('click', () => {
	nav.classList.toggle('is-open');
});

// закрыть при скролле
window.addEventListener('scroll', () => {
	if (nav.classList.contains('is-open')) {
		nav.classList.remove('is-open');
	}
}, { passive: true });

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

if (popup && popupClose) {
	setTimeout(() => {
		popup.classList.add('is-visible');
	}, 5000);

	popupClose.addEventListener('click', () => {
		popup.classList.remove('is-visible');
	});
}

/* --- FAQ аккордеон --- */
document.addEventListener('click', e => {
	const btn = e.target.closest('[class*="__question"]');
	if (!btn) return;

	const item   = btn.closest('[class*="__item"]');
	const answer = item?.querySelector('[class*="__answer"]');
	if (!item || !answer) return;

	const isOpen = item.classList.contains('is-open');

	/* Закрываем все элементы в том же списке */
	const list = item.closest('[class*="__list"]');
	list?.querySelectorAll('[class*="__item"]').forEach(i => {
		i.querySelector('[class*="__question"]')?.setAttribute('aria-expanded', 'false');
		i.classList.remove('is-open');
	});

	/* Открываем текущий, если был закрыт */
	if (!isOpen) {
		btn.setAttribute('aria-expanded', 'true');
		item.classList.add('is-open');
	}
});