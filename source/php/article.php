<?php
$COMMON_url = "https://engenix.ru";

/* Slug передаётся из /blog/[slug]/index.php напрямую через $slug */
/* Если вдруг зашли через GET — берём оттуда (обратная совместимость) */
if (empty($slug)) {
	$slug = trim($_GET['slug'] ?? '');
}

if ($slug === '') {
	header('Location: /blog/');
	exit;
}

/* Читаем локальный JSON статьи */
$json_path = $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts/' . $slug . '.json';

if (!file_exists($json_path)) {
	$error = 'Статья не найдена.';
} else {
	$post = json_decode(file_get_contents($json_path), true);
	if (!$post) {
		$error = 'Не удалось загрузить статью.';
	}
}

/* Данные статьи */
if (empty($error)) {
	$title    = $post['title'];
	$excerpt  = $post['excerpt'];
	$content  = $post['content'];
	$date_fmt = $post['date_fmt'];
	$image    = $post['image'] ?? '';
	$og_image = $image ? $COMMON_url . $image : $COMMON_url . '/source/img/og-default.png';

	/* SEO-поля — если заполнены в админке, иначе fallback на excerpt */
	$meta_desc     = !empty($post['meta_desc'])     ? $post['meta_desc']     : $excerpt;
	$meta_keywords = !empty($post['meta_keywords']) ? $post['meta_keywords'] : '';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= !empty($error) ? 'Статья не найдена' : htmlspecialchars($title) ?> — Блог Engenix</title>
	<?php if (empty($error)): ?>
	<meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
	<?php if ($meta_keywords): ?>
	<meta name="keywords" content="<?= htmlspecialchars($meta_keywords) ?>">
	<?php endif; ?>
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?= $COMMON_url ?>/blog/<?= htmlspecialchars($slug) ?>/">
	<!-- OG -->
	<meta property="og:type" content="article">
	<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
	<meta property="og:description" content="<?= htmlspecialchars($meta_desc) ?>">
	<meta property="og:url" content="<?= $COMMON_url ?>/blog/<?= htmlspecialchars($slug) ?>/">
	<meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
	<meta property="og:locale" content="ru_RU">
	<meta property="og:site_name" content="Engenix">
	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?= htmlspecialchars($title) ?>">
	<meta name="twitter:description" content="<?= htmlspecialchars($meta_desc) ?>">
	<meta name="twitter:image" content="<?= htmlspecialchars($og_image) ?>">
	<?php endif; ?>
	<link rel="icon" href="/favicon.webp" type="image/x-icon">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="/source/css/style_main.css">
	<link rel="stylesheet" href="/source/css/style_info.css">
	<link rel="stylesheet" href="/source/css/style_article.css">
</head>
<body>
	<!-- ========== HEADER ========== -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/source/php/header.php'; ?>

	<?php if (!empty($error)): ?>

	<!-- ========== ОШИБКА ========== -->
	<main class="section">
		<div class="container">
			<p class="text-muted" style="text-align:center;"><?= htmlspecialchars($error) ?></p>
			<div style="text-align:center; margin-top: var(--sp-6);">
				<a href="/blog/" class="btn btn-outline">← Вернуться в блог</a>
			</div>
		</div>
	</main>

	<?php else: ?>

	<!-- ========== BREADCRUMBS ========== -->
	<nav class="breadcrumbs" aria-label="Хлебные крошки">
		<div class="container breadcrumbs__inner">
			<a href="/" class="breadcrumbs__link">Главная</a>
			<span class="breadcrumbs__sep">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
			<a href="/blog/" class="breadcrumbs__link">Блог</a>
			<span class="breadcrumbs__sep">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
			</span>
			<span class="breadcrumbs__current"><?= htmlspecialchars($title) ?></span>
		</div>
	</nav>

	<!-- ========== ARTICLE ========== -->
	<main class="section">
		<div class="container">
			<div class="article">

				<div class="article__meta">
					<time class="article__date"><?= htmlspecialchars($date_fmt) ?></time>
				</div>

				<h1 class="article__title"><?= htmlspecialchars($title) ?></h1>

				<?php if ($image): ?>
				<div class="article__cover">
					<img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>">
				</div>
				<?php endif; ?>

				<div class="article__content">
					<?= $content ?>
				</div>

			</div>

			<!-- CTA -->
			<div class="article__cta">
				<p class="article__cta-title">Остались вопросы?</p>
				<p class="article__cta-text">Напишите нам — профессиональный авитолог ответит и поможет с вашей задачей.</p>
				<a href="https://t.me/engenixbot" class="btn btn-primary" target="_blank" rel="noopener">
					Написать в Telegram
				</a>
			</div>

		</div>
	</main>

	<?php endif; ?>

	<!-- ========== FOOTER ========== -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/source/php/footer.php'; ?>
	<button class="scroll-top" id="scrollTop" aria-label="Наверх">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
	</button>
	<script src="/source/js/main.js"></script>
</body>
</html>