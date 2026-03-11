<?php
/* Получаем slug из URL */
$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
    header('Location: /blog/');
    exit;
}

/* Запрос к WP API */
$slug_safe = urlencode($slug);
$api_url   = "https://engenix.ru/wp-json/wp/v2/posts?slug={$slug_safe}&status=publish&_fields=date,title,excerpt,content,slug";

$response = @file_get_contents($api_url);

if ($response === false) {
    header('HTTP/1.0 503 Service Unavailable');
    $error = 'Не удалось загрузить статью. Попробуйте позже.';
}

$posts = json_decode($response, true);

if (empty($posts)) {
    header('HTTP/1.0 404 Not Found');
    $error = 'Статья не найдена.';
}

/* Данные статьи */
if (empty($error)) {
    $post = $posts[0];

    $months = [
        1  => 'января',   2  => 'февраля',  3  => 'марта',
        4  => 'апреля',   5  => 'мая',      6  => 'июня',
        7  => 'июля',     8  => 'августа',  9  => 'сентября',
        10 => 'октября',  11 => 'ноября',   12 => 'декабря',
    ];

    $timestamp = strtotime($post['date']);
    $date_fmt  = date('j', $timestamp) . ' ' . $months[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);

    $title   = html_entity_decode(strip_tags($post['title']['rendered']), ENT_QUOTES, 'UTF-8');
    $excerpt = html_entity_decode(strip_tags($post['excerpt']['rendered']), ENT_QUOTES, 'UTF-8');
    $content = $post['content']['rendered']; /* полный HTML — не чистим */
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($error) ? 'Ошибка' : htmlspecialchars($title) ?> — Блог Engenix</title>
    <?php if (empty($error)): ?>
    <meta name="description" content="<?= htmlspecialchars($excerpt) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://engenix.ru/blog/?slug=<?= htmlspecialchars($slug) ?>">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($excerpt) ?>">
    <meta property="og:url" content="https://engenix.ru/blog/?slug=<?= htmlspecialchars($slug) ?>">
    <?php endif; ?>
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

    <?php if (isset($error)): ?>

    <!-- ========== ОШИБКА ========== -->
    <main class="section">
        <div class="container">
            <p class="text-muted" style="text-align:center;"><?= $error ?></p>
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

                <!-- Мета -->
                <div class="article__meta">
                    <time class="article__date"><?= $date_fmt ?></time>
                </div>

                <!-- Заголовок -->
                <h1 class="article__title"><?= htmlspecialchars($title) ?></h1>

                <!-- Контент из WP -->
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
    <!-- ========== SCROLL TO TOP ========== -->
    <button class="scroll-top" id="scrollTop" aria-label="Наверх">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
    <script src="/source/js/main.js"></script>
</body>
</html>