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