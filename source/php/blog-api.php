<?php
/* Читаем локальный каталог статей */
$catalog_path = $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts.json';

if (!file_exists($catalog_path)) {
	echo '<p class="text-muted" style="text-align:center; grid-column:1/-1;">Статьи не найдены.</p>';
	return;
}

$all_posts = json_decode(file_get_contents($catalog_path), true);

if (empty($all_posts)) {
	echo '<p class="text-muted" style="text-align:center; grid-column:1/-1;">Статьи не найдены.</p>';
	return;
}

/* Пагинация */
$per_page    = 6;
$total       = count($all_posts);
$total_pages = (int)ceil($total / $per_page);
$current     = max(1, min((int)($_GET['page'] ?? 1), $total_pages));
$offset      = ($current - 1) * $per_page;

$posts = array_slice($all_posts, $offset, $per_page);

/* Карточки */
foreach ($posts as $post) {
	$slug    = htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8');
	$title   = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
	$excerpt = htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8');
	$date    = htmlspecialchars($post['date_fmt'], ENT_QUOTES, 'UTF-8');
	$image   = htmlspecialchars($post['image'] ?? '', ENT_QUOTES, 'UTF-8');
	$link    = "/blog/{$slug}/";

	echo <<<HTML
	<article class="blog-card">
		<div class="blog-card__body">
			<time class="blog-card__date">{$date}</time>
			<h2 class="blog-card__title">{$title}</h2>
			<p class="blog-card__excerpt">{$excerpt}</p>
		</div>
		<a href="{$link}" class="blog-card__link">
			Читать далее
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/></svg>
		</a>
	</article>
	HTML;
}
?>

<?php if ($total_pages > 1): ?>
<nav class="blog-pagination" aria-label="Пагинация блога">
	<?php if ($current > 1): ?>
		<a href="/blog/?page=<?= $current - 1 ?>" class="blog-pagination__btn" aria-label="Предыдущая страница">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
		</a>
	<?php endif; ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>
		<a href="/blog/?page=<?= $i ?>"
		   class="blog-pagination__page <?= $i === $current ? 'is-active' : '' ?>"
		   <?= $i === $current ? 'aria-current="page"' : '' ?>>
			<?= $i ?>
		</a>
	<?php endfor; ?>

	<?php if ($current < $total_pages): ?>
		<a href="/blog/?page=<?= $current + 1 ?>" class="blog-pagination__btn" aria-label="Следующая страница">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
		</a>
	<?php endif; ?>
</nav>
<?php endif; ?>