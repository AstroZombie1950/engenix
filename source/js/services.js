/* ========== CTA Form ========== */
const ctaForm = document.getElementById('ctaForm');
const ctaSubmit = document.getElementById('ctaFormSubmit');
const ctaStatus = document.getElementById('ctaFormStatus');

if (ctaForm && ctaSubmit) {
	ctaSubmit.addEventListener('click', () => {
		const name = ctaForm.querySelector('[name="name"]');
		const contact = ctaForm.querySelector('[name="contact"]');
		const message = ctaForm.querySelector('[name="message"]');

		/* Простая валидация */
		if (!name.value.trim() || !contact.value.trim()) {
			showStatus('Заполните имя и контакт', 'error');
			return;
		}

		ctaSubmit.disabled = true;
		ctaSubmit.textContent = 'Отправка...';

		const data = new FormData();
		data.append('name', name.value.trim());
		data.append('contact', contact.value.trim());
		data.append('message', message.value.trim());

		fetch('/source/php/send.php', {
			method: 'POST',
			body: data
		})
		.then(res => res.json())
		.then(res => {
			if (res.success) {
				showStatus('Заявка отправлена! Скоро свяжемся.', 'success');
				name.value = '';
				contact.value = '';
				message.value = '';
			} else {
				showStatus(res.error || 'Ошибка отправки', 'error');
			}
		})
		.catch(() => {
			showStatus('Ошибка сети. Попробуйте позже.', 'error');
		})
		.finally(() => {
			ctaSubmit.disabled = false;
			ctaSubmit.textContent = 'Отправить заявку';
		});
	});
}

function showStatus(text, type) {
	if (!ctaStatus) return;
	ctaStatus.textContent = text;
	ctaStatus.className = 's-cta__form-status';
	ctaStatus.classList.add(type === 'success' ? 'is-success' : 'is-error');
}