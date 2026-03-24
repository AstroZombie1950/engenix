    <header class="header">
        <div class="container header__inner">

            <!-- Лого -->
            <a href="/" class="header__logo">
                <img src="/source/img/logo_white.png" alt="Engenix">
            </a>

            <!-- Навигация -->
            <nav class="header__nav" id="headerNav">
                <ul class="header__nav-list">

                    <!-- КАТАЛОГ -->
                    <li class="header__nav-item header__nav-item--dropdown">
                        <a href="#" class="header__nav-link">
                            Каталог
                            <svg class="header__nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </a>
                        <ul class="header__dropdown">
							<li><a href="/verifikaciya-po-rekvizitam" class="header__dropdown-link">Верификация по реквизитам</a></li>
							<li><a href="/verifikaciya-po-banku" class="header__dropdown-link">Верификация по банку</a></li>
							<li><a href="/verifikaciya-po-pasportu" class="header__dropdown-link">Верификация по паспорту</a></li>
							<li><a href="/verifikaciya-po-gosuslugam" class="header__dropdown-link">Верификация по Госуслугам</a></li>
                        </ul>
                    </li>

                    <!-- УСЛУГИ -->
                    <li class="header__nav-item header__nav-item--dropdown">
                        <a href="#" class="header__nav-link">
                            Услуги
                            <svg class="header__nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </a>
                        <ul class="header__dropdown">
                            <li><a href="/uslugi/avitolog-prodvizhenie" class="header__dropdown-link">Авитолог продвижение</a></li>
                            <li><a href="/uslugi/konsultaciya-avitologa" class="header__dropdown-link">Консультация авитолога</a></li>
                        </ul>
                    </li>

                    <!-- ИНФО -->
                    <li class="header__nav-item header__nav-item--dropdown">
                        <a href="#" class="header__nav-link">
                            Инфо
                            <svg class="header__nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </a>
                        <ul class="header__dropdown">
                            <li><a href="/cases" class="header__dropdown-link">Кейсы</a></li>
                            <li><a href="/reviews" class="header__dropdown-link">Отзывы</a></li>
                            <li><a href="/faq" class="header__dropdown-link">FAQ</a></li>
                            <li><a href="/blog" class="header__dropdown-link">Блог</a></li>
                        </ul>
                    </li>

                    <!-- КОМПАНИЯ -->
                    <li class="header__nav-item header__nav-item--dropdown">
                        <a href="#" class="header__nav-link">
                            Компания
                            <svg class="header__nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </a>
                        <ul class="header__dropdown">
                            <li><a href="/history" class="header__dropdown-link">История</a></li>
                            <li><a href="/team" class="header__dropdown-link">Команда</a></li>
                            <li><a href="/requisites" class="header__dropdown-link">Реквизиты</a></li>
                        </ul>
                    </li>

                    <!-- ЕЩЕ -->
                    <li class="header__nav-item header__nav-item--dropdown">
                        <a href="#" class="header__nav-link">
                            Ещё
                            <svg class="header__nav-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </a>
                        <ul class="header__dropdown">
                            <li><a href="/partners" class="header__dropdown-link">Партнёрам</a></li>
                            <li><a href="/contacts" class="header__dropdown-link">Контакты</a></li>
                            <li><a href="/policy" class="header__dropdown-link">Политика</a></li>
                            <li><a href="/cookie" class="header__dropdown-link">Cookie</a></li>
                        </ul>
                    </li>

                </ul>
            </nav>

            <!-- Кнопки связи + бургер -->
            <div class="header__actions">
                <a href="https://t.me/engenixbot" class="header__contact-btn" target="_blank" rel="noopener" aria-label="Telegram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.32 13.617l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.828.942z"/></svg>
                    <span>Telegram</span>
                </a>
				<a href="mailto:reserve@engenix.ru" class="header__contact-btn header__contact-btn--outline" aria-label="Email">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="2,4 12,13 22,4"/></svg>
					<span>Email</span>
				</a>
                <button class="header__burger" id="headerBurger" aria-label="Открыть меню">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </header>