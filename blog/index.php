<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Блог — Engenix</title>
	<meta name="description" content="Полезные статьи об аккаунтах Авито, верификации, продвижении и работе с площадкой. Советы от профессиональных авитологов Engenix.">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="https://engenix.ru/blog">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Блог — Engenix">
	<meta property="og:description" content="Полезные статьи об аккаунтах Авито, верификации, продвижении и работе с площадкой.">
	<meta property="og:url" content="https://engenix.ru/blog">
	<link rel="icon" href="/favicon.webp" type="image/x-icon">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/source/css/style_main.css">
	<link rel="stylesheet" href="/source/css/style_info.css">
<style>
/* ========== BLOG PAGINATION ========== */
.blog-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--sp-2);
    padding: var(--sp-12) 0 0;
    grid-column: 1 / -1;
}

.blog-pagination__btn,
.blog-pagination__page {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: var(--radius-tab);
    font-size: var(--fs-small);
    font-weight: var(--fw-body-medium);
    color: var(--text-muted);
    border: 1px solid var(--border);
    background: var(--card-base);
    transition: color 0.2s, border-color 0.2s, background 0.2s;
}

.blog-pagination__btn:hover,
.blog-pagination__page:hover {
    color: var(--text-primary);
    border-color: var(--border-strong);
    background: var(--card-solid);
}

.blog-pagination__page.is-active {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
    pointer-events: none;
}
/* ========== ARTICLE ========== */
.article {
    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: var(--sp-6);
}

.article__meta {
    display: flex;
    align-items: center;
    gap: var(--sp-4);
}

.article__date {
    font-size: var(--fs-caption);
    color: var(--text-muted);
}

.article__title {
    font-size: var(--fs-h1);
    font-weight: var(--fw-h1);
    line-height: 1.15;
}

/* Типографика контента из WP */
.article__content {
    display: flex;
    flex-direction: column;
    gap: var(--sp-5);
    color: var(--text-secondary);
    font-size: var(--fs-body);
    line-height: 1.75;
}

.article__content h2 {
    font-size: var(--fs-h2);
    margin-top: var(--sp-4);
}

.article__content h3 {
    font-size: var(--fs-h3);
    margin-top: var(--sp-3);
}

.article__content h4 {
    font-size: var(--fs-h4);
}

.article__content p {
    color: var(--text-secondary);
}

.article__content ul,
.article__content ol {
    display: flex;
    flex-direction: column;
    gap: var(--sp-2);
    padding-left: var(--sp-6);
}

.article__content ul { list-style: disc; }
.article__content ol { list-style: decimal; }

.article__content li {
    font-size: var(--fs-body);
    color: var(--text-secondary);
    line-height: 1.65;
}

.article__content a {
    color: var(--accent-light);
    text-decoration: underline;
    text-underline-offset: 3px;
}

.article__content a:hover {
    color: var(--text-primary);
}

.article__content strong {
    color: var(--text-primary);
    font-weight: var(--fw-body-medium);
}

.article__content blockquote {
    padding-left: var(--sp-4);
    border-left: 3px solid var(--accent);
    color: var(--text-muted);
    font-style: italic;
}

.article__content code {
    background: var(--card-solid);
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 2px 6px;
    font-size: var(--fs-small);
    color: var(--accent-light);
}

.article__content img {
    width: 100%;
    border-radius: var(--radius-card);
}

/* CTA после статьи */
.article__cta {
    max-width: 760px;
    margin: var(--sp-12) auto 0;
    background: var(--card-base);
    border: 1px solid var(--border-accent);
    border-radius: var(--radius-card);
    padding: var(--sp-10) var(--card-padding);
    backdrop-filter: blur(var(--blur-card));
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: var(--sp-4);
}

.article__cta-title {
    font-family: var(--font-heading);
    font-size: var(--fs-h3);
    font-weight: var(--fw-h3);
    color: var(--text-primary);
}

.article__cta-text {
    color: var(--text-muted);
    max-width: 480px;
}

.article__cta .btn:hover { color: #fff; }
</style>
</head>
<body>
	<!-- ========== HEADER ========== -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/source/php/header.php'; ?>
	<!-- ========== BREADCRUMBS ========== -->
	<nav class="breadcrumbs" aria-label="Хлебные крошки">
		<div class="container breadcrumbs__inner">
			<a href="/" class="breadcrumbs__link">Главная</a>
			<span class="breadcrumbs__sep">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
			</span>
			<span class="breadcrumbs__current">Блог</span>
		</div>
	</nav>
	<!-- ========== PAGE HERO ========== -->
	<section class="page-hero">
		<div class="container page-hero__inner">
			<h1 class="page-hero__title">Блог</h1>
			<p class="page-hero__desc">Полезные статьи об аккаунтах Авито, верификации, продвижении и работе с площадкой.</p>

		</div>
	</section>
	<!-- ========== BLOG GRID ========== -->
	<main class="section">
		<div class="container">
			<div class="blog-grid" id="blogGrid">
				<?php include $_SERVER['DOCUMENT_ROOT'] . '/source/php/blog-api.php'; ?>
			</div>
		</div>
	</main>
	<!-- ========== FOOTER ========== -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/source/php/footer.php'; ?>
	<!-- ========== SCROLL TO TOP ========== -->
	<button class="scroll-top" id="scrollTop" aria-label="Наверх">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
	</button>
	<script src="/source/js/main.js"></script>
</body>
</html>