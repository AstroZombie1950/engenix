<?php

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$system = [
    'wp-admin',
    'wp-login.php',
    'wp-json',
    'wp-content',
    'wp-includes',
    'xmlrpc.php'
];

foreach ($system as $path) {
    if (str_starts_with($uri, $path)) {
        return;
    }
}

$file = get_template_directory() . '/' . $uri . '/index.php';

if ($uri && file_exists($file)) {
    require $file;
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- SEO -->
	<title>Купить Аккаунты Авито | Магазин Аккаунтов Авито</title>
	<meta name="description" content="Купить аккаунты Авито – физические и бизнес с гарантией. Быстрая передача данных, безопасная покупка. Начните работать уже сегодня!">
	<meta name="author" content="Engenix">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="https://engenix.ru/">
	<!-- OG -->
	<meta property="og:locale" content="ru_RU">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Home">
	<meta property="og:description" content="Купить аккаунты Авито – физические и бизнес с гарантией. Быстрая передача данных, безопасная покупка. Начните работать уже сегодня!">
	<meta property="og:site_name" content="Купить Аккаунты Авито — Магазин Аккаунтов Авито">
	<meta property="og:image" content="https://engenix.ru/source/img/info_two.png">
	<meta property="og:image:width" content="2200">
	<meta property="og:image:height" content="1926">
	<meta property="og:image:type" content="image/png">
	<meta property="og:url" content="https://engenix.ru/">
	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="Engenix">
	<meta name="twitter:description" content="Купить аккаунты Авито – физические и бизнес с гарантией. Быстрая передача данных, безопасная покупка. Начните работать уже сегодня!">
	<meta name="twitter:image" content="/og-image.jpg">
	<!-- Favicon -->
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.webp" type="image/x-icon">
	<!-- Шрифты -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>
    <!-- ========== HEADER ========== -->
    <?php include get_template_directory() . '/source/php/header.php'; ?>
    <!-- ========== SEO H1 ========== -->
    <section class="seo-hero">
        <div class="container seo-hero__inner">
            <h1 class="seo-hero__title">Купить аккаунты Авито. Авито верификация</h1>
            <p class="seo-hero__text">Ищете, где купить аккаунты Авито с гарантией? У нас вы найдёте физические и бизнес-аккаунты для любых задач — от единичных продаж до масштабного бизнеса. Платформа полностью готова к работе: пройденные верификации, стабильная активность и надёжность.</p>
        </div>
    </section>
    <!-- ========== CATALOG ========== -->
    <section class="section" id="catalog">
        <div class="container">

            <!-- Заголовок секции -->
            <div style="text-align: center; margin-bottom: var(--sp-12);">
                <h2>Сравните с другими типами верификации и выберите свой</h2>
            </div>

            <div class="products-grid">

                <!-- Паспорт -->
                <div class="product-card">
                    <h3>ПО ПАСПОРТУ</h3>
                    <span class="badge">Физлицо</span>
                    <p style="margin-top: var(--sp-4);">Классический способ верификации для частных лиц. Надёжно, быстро и без лишних сложностей.</p>

                    <div class="product-price-block">
                        <div class="product-price-row">
                            <span class="label">На вашу симку:</span>
                            <span class="value">4 000 ₽</span>
                        </div>
                        <div class="product-price-row">
                            <span class="label">На нашу симку:</span>
                            <span class="value">5 000 ₽</span>
                        </div>
                    </div>

                    <ul class="product-features">
                        <li>Стандартный уровень доверия</li>
                        <li>Подходит для большинства задач</li>
                        <li>Быстрое оформление</li>
                        <li>Полный функционал площадки</li>
                    </ul>

                    <a href="https://t.me/engenixbot" class="btn btn-outline" style="width: 100%; justify-content: center;">Выбрать в Telegram →</a>
                </div>

                <!-- Госуслуги -->
                <div class="product-card">
                    <h3>ЧЕРЕЗ ГОСУСЛУГИ</h3>
                    <span class="badge">Максимальный статус</span>
                    <p style="margin-top: var(--sp-4);">Для тех, кому нужен максимальный статус доверия. Авито считает этот способ самым надёжным.</p>

                    <div class="product-price-block">
                        <div class="product-price-row">
                            <span class="label">На вашу симку:</span>
                            <span class="value">2 500 ₽</span>
                        </div>
                        <div class="product-price-row">
                            <span class="label">На нашу симку:</span>
                            <span class="value">3 500 ₽</span>
                        </div>
                    </div>

                    <ul class="product-features">
                        <li>Самый высокий статус профиля</li>
                        <li>Приоритет в выдаче объявлений</li>
                        <li>Максимальное доверие покупателей</li>
                        <li>Эталонная верификация</li>
                    </ul>

                    <a href="https://t.me/engenixbot" class="btn btn-outline" style="width: 100%; justify-content: center;">Выбрать в Telegram →</a>
                </div>

                <!-- Реквизиты -->
                <div class="product-card">
                    <h3>ПО РЕКВИЗИТАМ</h3>
                    <span class="badge">ООО / ИП</span>
                    <p style="margin-top: var(--sp-4);">Для бизнеса, компаний, ИП. Если нужно размещать вакансии, работать с высокими бюджетами или получить статус «Проверенный партнёр».</p>

                    <div class="product-price-block">
                        <div class="product-price-row">
                            <span class="label">На вашу симку:</span>
                            <span class="value">12 500 ₽</span>
                        </div>
                        <div class="product-price-row">
                            <span class="label">На нашу симку:</span>
                            <span class="value">13 500 ₽</span>
                        </div>
                    </div>

                    <ul class="product-features">
                        <li>Возможность публиковать вакансии</li>
                        <li>Отсутствие повторных проверок</li>
                        <li>Высокий кредит доверия</li>
                        <li>Авито закрывает глаза на мелкие ошибки</li>
                    </ul>

                    <a href="https://t.me/engenixbot" class="btn btn-outline" style="width: 100%; justify-content: center;">Выбрать в Telegram →</a>
                </div>

                <!-- Банк -->
                <div class="product-card">
                    <h3>ЧЕРЕЗ БАНК</h3>
                    <span class="badge">Tinkoff / Сбер ID</span>
                    <p style="margin-top: var(--sp-4);">Для тех, кто хочет быстро пройти верификацию без загрузки сканов паспорта.</p>

                    <div class="product-price-block">
                        <div class="product-price-row">
                            <span class="label">На вашу симку:</span>
                            <span class="value">2 500 ₽</span>
                        </div>
                        <div class="product-price-row">
                            <span class="label">На нашу симку:</span>
                            <span class="value">3 500 ₽</span>
                        </div>
                    </div>

                    <ul class="product-features">
                        <li>Не нужны сканы документов</li>
                        <li>Подтверждение через Tinkoff ID / Сбер ID</li>
                        <li>Быстрый старт</li>
                        <li>Полный функционал площадки</li>
                    </ul>

                    <a href="https://t.me/engenixbot" class="btn btn-outline" style="width: 100%; justify-content: center;">Выбрать в Telegram →</a>
                </div>

            </div>
        </div>
    </section>
    <!-- ========== SERVICES ========== -->
    <section class="section services">
        <div class="container services__inner">

            <div class="services__header">
                <h2>Услуги по Avito</h2>
                <p class="text-large">Помимо готовых аккаунтов, мы предлагаем профессиональные услуги авитолога для тех, кто хочет делегировать работу с площадкой или получить экспертную помощь.</p>
            </div>

            <div class="services__list">

                <!-- Консультация -->
                <div class="services__item">
                    <div class="services__item-content">
                        <h3 class="services__item-title">Консультация авитолога</h3>
                        <p class="services__item-desc">Разбор блокировок, аудит объявлений, план действий на 7–14 дней.</p>
                    </div>
                    <a href="/uslugi/konsultaciya-avitologa" class="services__item-link">
                        Подробнее о консультации
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/></svg>
                    </a>
                </div>

                <div class="services__divider"></div>

                <!-- Ведение -->
                <div class="services__item">
                    <div class="services__item-content">
                        <h3 class="services__item-title">Ведение рекламной кампании под ключ</h3>
                        <p class="services__item-desc">Стратегия, антибан-практики, оптимизация CPL/ROI, регулярная отчётность.</p>
                    </div>
                    <a href="/uslugi/avitolog-prodvizhenie" class="services__item-link">
                        Подробнее о ведении
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/></svg>
                    </a>
                </div>

            </div>

            <!-- Футер блока -->
            <p class="services__footer">
                Все услуги авитолога — в соответствующем разделе.
                <a href="/uslugi" class="services__footer-link">Перейти в раздел услуг →</a>
            </p>

        </div>
    </section>
    <!-- ========== HOW IT WORKS ========== -->
    <section class="section section-alt how">
        <div class="container">

            <h2 class="how__title">Ход работы:</h2>

            <div class="how__grid">

                <!-- Шаг 1 -->
                <div class="card how__card">
                    <div class="how__step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        <span>1</span>
                    </div>
                    <h3 class="how__card-title">Выбираете тип верификации</h3>
                    <p class="how__card-desc">Просмотрите доступные аккаунты и напишите нам.</p>
                </div>

                <!-- Шаг 2 -->
                <div class="card how__card">
                    <div class="how__step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <span>2</span>
                    </div>
                    <h3 class="how__card-title">Оплачиваете покупку</h3>
                    <p class="how__card-desc">Выбирайте удобный способ оплаты — карта, СБП, USDT.</p>
                </div>

                <!-- Шаг 3 -->
                <div class="card how__card">
                    <div class="how__step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.42 2 2 0 0 1 3.6 1.24h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.84a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.54 16z"/></svg>
                        <span>3</span>
                    </div>
                    <h3 class="how__card-title">Получаете данные для авторизации</h3>
                    <p class="how__card-desc">Мы отправим логин и пароль в Telegram или WhatsApp.</p>
                </div>

                <!-- Шаг 4 -->
                <div class="card how__card">
                    <div class="how__step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                        <span>4</span>
                    </div>
                    <h3 class="how__card-title">Настраиваете профиль под себя</h3>
                    <p class="how__card-desc">Меняйте данные, входите в аккаунт согласно инструкции.</p>
                </div>

                <!-- Шаг 5 -->
                <div class="card how__card">
                    <div class="how__step">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>5</span>
                    </div>
                    <h3 class="how__card-title">Размещаете объявления</h3>
                    <p class="how__card-desc">Используйте аккаунт для бизнеса и рекламы.</p>
                </div>

            </div>
        </div>
    </section>
    <!-- ========== INFO: ВЕРИФИКАЦИЯ ========== -->
    <section class="section info-verify">
        <div class="container info-verify__inner">

            <!-- Изображение -->
            <div class="info-verify__media">
                <img src="<?php echo get_template_directory_uri(); ?>/source/img/info_one.png" alt="Верификация Авито">
            </div>

            <!-- Текст -->
            <div class="info-verify__content">
                <h2>Верификация Авито — Самые популярные способы</h2>
                <p class="text-large">Верификация через Госуслуги — самый удобный и доступный способ подтверждения для физических лиц. Бизнес-верификация через проверку реквизитов — наиболее надежный вариант из всех возможных.</p>
                <a href="https://t.me/engenixbot" class="btn btn-primary" target="_blank" rel="noopener">Заказать</a>
            </div>

        </div>
    </section>
    <!-- ========== INFO: КУПИТЬ АККАУНТ ========== -->
    <section class="section section-alt info-buy">
        <div class="container info-buy__inner">

            <!-- Текст -->
            <div class="info-buy__content">
                <h2>Купить аккаунт авито?</h2>
                <div class="info-buy__text-block">
                    <p>Если вы хотите купить аккаунт Авито и начать продавать на Авито прямо сейчас, но не хотите тратить время на создание, верификацию и «прокачку» нового аккаунта — покупка готового аккаунта Авито сэкономит ваше время и увеличит продажи.</p>
                    <p>Вы можете купить аккаунты Авито — как обычные, так и бизнес-аккаунты, чтобы избежать ограничений, получить доступ ко всем функциям платформы и сразу размещать объявления без задержек. Мы предлагаем проверенные аккаунты Авито с гарантией, быстрым получением и безопасной передачей данных.</p>
                </div>
                <ul class="info-buy__features">
                    <li class="info-buy__feature">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                        Экономия времени и сил
                    </li>
                    <li class="info-buy__feature">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                        Безопасность и надежность
                    </li>
                    <li class="info-buy__feature">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                        Обход ограничений
                    </li>
                    <li class="info-buy__feature">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                        Поддержка и консультации
                    </li>
                </ul>
            </div>

            <!-- Изображение -->
            <div class="info-buy__media">
                <img src="<?php echo get_template_directory_uri(); ?>/source/img/info_two.png" alt="Купить аккаунты авито">
            </div>

        </div>
    </section>
    <!-- ========== INFO: ВЕРИФИКАЦИЯ АВИТО ========== -->
    <section class="section info-avito">
        <div class="container info-avito__inner">

            <!-- Изображение -->
            <div class="info-avito__media">
                <img src="<?php echo get_template_directory_uri(); ?>/source/img/info_three.png" alt="Плашки верификации в аккаунте Авито">
            </div>

            <!-- Текст -->
            <div class="info-avito__content">
                <h2>Верификация Авито</h2>
                <div class="info-avito__quote">
                    <p>Плашки в аккаунте Авито — это не просто «наклейки», а мощный инструмент доверия. Каждая из них помогает покупателю принять решение быстрее и чаще — в твою пользу.</p>
                </div>
                <ul class="info-avito__features">
                    <li class="info-avito__feature">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Надежный продавец
                    </li>
                    <li class="info-avito__feature">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Продажи с Авито Доставкой
                    </li>
                    <li class="info-avito__feature">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Документы проверены
                    </li>
                </ul>
            </div>

        </div>
    </section>
    <!-- ========== SEO: АККАУНТЫ АВИТО ========== -->
    <section class="section section-alt seo-block">
        <div class="container seo-block__inner">

            <h2 class="seo-block__title">Аккаунты Авито</h2>

            <div class="seo-block__content">

                <p class="seo-block__lead">Купить аккаунты Авито — лучшее решение для бизнеса и продаж</p>
                <p>Если вам нужен <strong>аккаунт Авито</strong> для публикации объявлений, продвижения товаров или развития бизнеса, у нас вы можете <strong>купить аккаунты Авито</strong> по выгодным ценам. В наличии <strong>авито аккаунты с объявлениями</strong>, <strong>бизнес-аккаунты Авито</strong>, а также <strong>прокачанные аккаунты с отзывами</strong>.</p>

                <h3 class="seo-block__h3">Почему стоит купить аккаунт Авито у нас?</h3>
                <ul class="seo-block__checklist">
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><strong>Гарантия</strong> — предоставляем только проверенные аккаунты.</span>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><strong>Большой выбор</strong> — от новых аккаунтов до бизнес-страниц с активной историей.</span>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><strong>Безопасность</strong> — даем рекомендации по использованию, чтобы избежать блокировок.</span>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <span><strong>Аренда аккаунтов</strong> — если не хотите покупать, можно взять аккаунт в аренду.</span>
                    </li>
                </ul>

                <h3 class="seo-block__h3">Как купить аккаунт Авито?</h3>
                <ol class="seo-block__numlist">
                    <li>
                        <span class="seo-block__num">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            1
                        </span>
                        Выберите подходящий аккаунт.
                    </li>
                    <li>
                        <span class="seo-block__num">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            2
                        </span>
                        Оформите заказ и произведите оплату.
                    </li>
                    <li>
                        <span class="seo-block__num">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            3
                        </span>
                        Получите данные для входа и инструкции.
                    </li>
                </ol>

                <p>Вы также можете <strong>купить аккаунт Авито с объявлениями</strong>, если вам важно сразу начать продажи. Мы предлагаем аккаунты с размещёнными объявлениями, которые уже прошли модерацию.</p>

                <div class="seo-block__faq-item">
                    <p class="seo-block__faq-q">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Где купить аккаунт Авито?
                    </p>
                    <p>Просто оставьте заявку на нашем сайте или свяжитесь с нами для консультации. Мы поможем вам выбрать <strong>лучший аккаунт для работы на Авито</strong>!</p>
                </div>

            </div>
        </div>
    </section>
    <!-- ========== FOOTER ========== -->
    <?php include get_template_directory() . '/source/php/footer.php'; ?>
    <!-- ========== POPUP ========== -->
    <div class="popup" id="popup">
        <button class="popup__close" id="popupClose" aria-label="Закрыть">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="popup__icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L8.32 13.617l-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.828.942z"/></svg>
        </div>
        <p class="popup__title">Помощь от профессионального авитолога</p>
        <p class="popup__text">Защищу от банов, настрою поток лидов и <strong>сэкономлю бюджет</strong> 🚀</p>
        <a href="https://t.me/engenixbot" class="btn btn-primary popup__btn" target="_blank" rel="noopener">
            Написать в Telegram
        </a>
    </div>
    <!-- ========== SCROLL TO TOP ========== -->
    <button class="scroll-top" id="scrollTop" aria-label="Наверх">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
    <?php wp_footer(); ?>
</body>
</html>