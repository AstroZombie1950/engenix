<?php
/* Пагинация через GET-параметр ?page=N */

$per_page    = 6;
$current     = max(1, (int)($_GET['page'] ?? 1));

$api_url = "https://engenix.ru/wp-json/wp/v2/posts"
         . "?per_page={$per_page}"
         . "&page={$current}"
         . "&status=publish"
         . "&_fields=date,title,excerpt,link";

/* Запрос с получением заголовков для пагинации */
$context  = stream_context_create(['http' => ['ignore_errors' => true]]);
$response = @file_get_contents($api_url, false, $context);

/* Ошибка запроса */
if ($response === false) {
    echo '<p class="text-muted" style="text-align:center; grid-column:1/-1;">Не удалось загрузить статьи. Попробуйте позже.</p>';
    return;
}

/* Общее кол-во страниц из заголовков WP API */
$total_pages = 1;
foreach ($http_response_header as $h) {
    if (stripos($h, 'X-WP-TotalPages:') === 0) {
        $total_pages = (int)trim(explode(':', $h, 2)[1]);
        break;
    }
}

$posts = json_decode($response, true);

/* Пустой ответ */
if (empty($posts)) {
    echo '<p class="text-muted" style="text-align:center; grid-column:1/-1;">Статьи не найдены.</p>';
    return;
}

/* Русские месяцы */
$months = [
    1  => 'января',   2  => 'февраля',  3  => 'марта',
    4  => 'апреля',   5  => 'мая',      6  => 'июня',
    7  => 'июля',     8  => 'августа',  9  => 'сентября',
    10 => 'октября',  11 => 'ноября',   12 => 'декабря',
];

/* Карточки */
foreach ($posts as $post) {

    $timestamp = strtotime($post['date']);
    $date_fmt  = date('j', $timestamp) . ' ' . $months[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);

    $title   = html_entity_decode(strip_tags($post['title']['rendered']), ENT_QUOTES, 'UTF-8');
    $excerpt = trim(html_entity_decode(strip_tags($post['excerpt']['rendered']), ENT_QUOTES, 'UTF-8'));
    $link    = htmlspecialchars(filter_var($post['link'], FILTER_SANITIZE_URL), ENT_QUOTES, 'UTF-8');

    echo <<<HTML
    <article class="blog-card">
        <div class="blog-card__body">
            <time class="blog-card__date">{$date_fmt}</time>
            <h2 class="blog-card__title">{$title}</h2>
            <p class="blog-card__excerpt">{$excerpt}</p>
        </div>
        <a href="{$link}" class="blog-card__link" target="_blank" rel="noopener">
            Читать далее
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/></svg>
        </a>
    </article>
    HTML;
}
?>

<!-- Пагинация — выводим только если страниц больше одной -->
<?php if ($total_pages > 1): ?>
<nav class="blog-pagination" aria-label="Пагинация блога">
    <?php if ($current > 1): ?>
        <a href="/blog?page=<?= $current - 1 ?>" class="blog-pagination__btn" aria-label="Предыдущая страница">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="/blog?page=<?= $i ?>"
           class="blog-pagination__page <?= $i === $current ? 'is-active' : '' ?>"
           <?= $i === $current ? 'aria-current="page"' : '' ?>>
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($current < $total_pages): ?>
        <a href="/blog?page=<?= $current + 1 ?>" class="blog-pagination__btn" aria-label="Следующая страница">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    <?php endif; ?>
</nav>
<?php endif; ?>