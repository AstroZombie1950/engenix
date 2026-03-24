<?php
/* ========== КОНФИГ ========== */
define('ADMIN_PASSWORD', 'privet');
define('DATA_DIR',     $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts');
define('CATALOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts.json');
define('IMG_DIR',      $_SERVER['DOCUMENT_ROOT'] . '/source/img/blog');
define('BLOG_DIR',     $_SERVER['DOCUMENT_ROOT'] . '/blog');

/* ========== СЕССИЯ ========== */
session_start();

/* Логин */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	if ($_POST['password'] === ADMIN_PASSWORD) {
		$_SESSION['admin'] = true;
	} else {
		$login_error = 'Неверный пароль';
	}
}

/* Выход */
if (isset($_GET['logout'])) {
	session_destroy();
	header('Location: /blog/admin/');
	exit;
}

$authed = !empty($_SESSION['admin']);

/* ========== ПУБЛИКАЦИЯ ========== */
$pub_success = '';
$pub_error   = '';

if ($authed && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {

	$slug     = trim($_POST['slug'] ?? '');
	$title    = trim($_POST['title'] ?? '');
	$h1       = trim($_POST['h1'] ?? '');
	$desc     = trim($_POST['meta_desc'] ?? '');
	$keywords = trim($_POST['meta_keywords'] ?? '');
	$excerpt  = trim($_POST['excerpt'] ?? '');
	$content  = trim($_POST['content'] ?? '');

	if (!$slug || !$title || !$content) {
		$pub_error = 'Заполните slug, title и контент';
	} elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
		$pub_error = 'Slug — только латиница, цифры и дефис';
	} else {

		/* Обложка */
		$img_local = '';
		if (!empty($_FILES['image']['tmp_name'])) {
			$ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			$allowed  = ['jpg', 'jpeg', 'png', 'webp'];
			if (!in_array($ext, $allowed)) {
				$pub_error = 'Допустимые форматы: jpg, png, webp';
			} else {
				$filename  = $slug . '.' . $ext;
				$img_path  = IMG_DIR . '/' . $filename;
				if (move_uploaded_file($_FILES['image']['tmp_name'], $img_path)) {
					$img_local = '/source/img/blog/' . $filename;
				} else {
					$pub_error = 'Не удалось сохранить изображение';
				}
			}
		}

		if (!$pub_error) {

			/* Дата */
			$months = [
				1=>'января', 2=>'февраля', 3=>'марта', 4=>'апреля',
				5=>'мая', 6=>'июня', 7=>'июля', 8=>'августа',
				9=>'сентября', 10=>'октября', 11=>'ноября', 12=>'декабря',
			];
			$now      = new DateTime();
			$date     = $now->format('Y-m-d\TH:i:s');
			$date_fmt = $now->format('j') . ' ' . $months[(int)$now->format('n')] . ' ' . $now->format('Y');

			/* Сохраняем JSON статьи */
			$post_data = [
				'slug'          => $slug,
				'title'         => $title,
				'h1'            => $h1 ?: $title,
				'meta_desc'     => $desc,
				'meta_keywords' => $keywords,
				'excerpt'       => $excerpt,
				'date'          => $date,
				'date_fmt'      => $date_fmt,
				'image'         => $img_local,
				'content'       => $content,
			];

			if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
			file_put_contents(DATA_DIR . '/' . $slug . '.json', json_encode($post_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

			/* Обновляем каталог */
			$catalog = [];
			if (file_exists(CATALOG_FILE)) {
				$catalog = json_decode(file_get_contents(CATALOG_FILE), true) ?: [];
			}
			/* Убираем старую версию если редактируем */
			$catalog = array_filter($catalog, fn($p) => $p['slug'] !== $slug);
			/* Добавляем в начало */
			array_unshift($catalog, [
				'slug'     => $slug,
				'title'    => $title,
				'excerpt'  => $excerpt,
				'date'     => $date,
				'date_fmt' => $date_fmt,
				'image'    => $img_local,
			]);
			file_put_contents(CATALOG_FILE, json_encode(array_values($catalog), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

			/* Создаём папку /blog/[slug]/ */
			$article_dir = BLOG_DIR . '/' . $slug;
			if (!is_dir($article_dir)) mkdir($article_dir, 0755, true);
			$index_php = <<<PHP
<?php
\$slug = '{$slug}';
include \$_SERVER['DOCUMENT_ROOT'] . '/source/php/article.php';
PHP;
			file_put_contents($article_dir . '/index.php', $index_php);

			$pub_success = $slug;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Админка блога — Engenix</title>
	<meta name="robots" content="noindex, nofollow">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
	<style>
		/* ========== RESET ========== */
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		html { font-size: 16px; }
		body {
			background: #0B1020;
			color: #fff;
			font-family: 'Inter', sans-serif;
			min-height: 100vh;
		}

		/* ========== ПЕРЕМЕННЫЕ ========== */
		:root {
			--accent:       #4F46E5;
			--accent-hover: #4338CA;
			--accent-light: #C7D2FE;
			--border:       rgba(255,255,255,0.12);
			--border-accent:rgba(79,70,229,0.35);
			--card:         rgba(255,255,255,0.06);
			--muted:        rgba(255,255,255,0.46);
			--text-sec:     rgba(255,255,255,0.82);
			--radius:       14px;
		}

		/* ========== ЛОГИН ========== */
		.login {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
		}
		.login__box {
			background: rgba(255,255,255,0.06);
			border: 1px solid var(--border);
			border-radius: 22px;
			padding: 40px;
			width: 100%;
			max-width: 380px;
			display: flex;
			flex-direction: column;
			gap: 20px;
		}
		.login__title {
			font-family: 'Space Grotesk', sans-serif;
			font-size: 22px;
			font-weight: 700;
			color: var(--accent-light);
		}
		.login__error {
			font-size: 14px;
			color: #f87171;
		}

		/* ========== LAYOUT ========== */
		.admin {
			display: grid;
			grid-template-columns: 1fr 1fr;
			min-height: 100vh;
		}

		/* ========== ПАНЕЛЬ РЕДАКТОРА ========== */
		.editor {
			padding: 40px;
			border-right: 1px solid var(--border);
			overflow-y: auto;
			display: flex;
			flex-direction: column;
			gap: 24px;
		}

		.editor__header {
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.editor__title {
			font-family: 'Space Grotesk', sans-serif;
			font-size: 20px;
			font-weight: 700;
			color: var(--accent-light);
		}

		.editor__logout {
			font-size: 13px;
			color: var(--muted);
			text-decoration: none;
			transition: color 0.2s;
		}
		.editor__logout:hover { color: #fff; }

		/* ========== ГРУППЫ ПОЛЕЙ ========== */
		.field {
			display: flex;
			flex-direction: column;
			gap: 8px;
		}

		.field__row {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 16px;
		}

		label {
			font-size: 13px;
			font-weight: 500;
			color: var(--accent-light);
			letter-spacing: 0.04em;
			text-transform: uppercase;
		}

		input[type=text],
		textarea {
			width: 100%;
			background: rgba(255,255,255,0.06);
			color: #fff;
			border: 1px solid var(--border);
			border-radius: var(--radius);
			font-family: 'Inter', sans-serif;
			font-size: 15px;
			padding: 12px 16px;
			outline: none;
			transition: border-color 0.2s, box-shadow 0.2s;
			resize: vertical;
		}

		input[type=text]:focus,
		textarea:focus {
			border-color: rgba(79,70,229,0.55);
			box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
		}

		input[type=text]::placeholder,
		textarea::placeholder { color: var(--muted); }

		/* ========== РЕДАКТОР КОНТЕНТА ========== */
		.wysiwyg {
			border: 1px solid var(--border);
			border-radius: var(--radius);
			overflow: hidden;
			transition: border-color 0.2s;
		}
		.wysiwyg:focus-within {
			border-color: rgba(79,70,229,0.55);
			box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
		}

		/* Тулбар */
		.wysiwyg__toolbar {
			display: flex;
			gap: 4px;
			padding: 8px 12px;
			background: rgba(255,255,255,0.04);
			border-bottom: 1px solid var(--border);
			flex-wrap: wrap;
		}

		.wysiwyg__btn {
			height: 32px;
			min-width: 32px;
			padding: 0 10px;
			border-radius: 8px;
			background: transparent;
			color: var(--text-sec);
			border: 1px solid transparent;
			font-family: 'Inter', sans-serif;
			font-size: 13px;
			font-weight: 600;
			cursor: pointer;
			transition: background 0.15s, color 0.15s, border-color 0.15s;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.wysiwyg__btn:hover {
			background: rgba(255,255,255,0.08);
			color: #fff;
			border-color: var(--border);
		}
		.wysiwyg__btn--sep {
			width: 1px;
			height: 24px;
			background: var(--border);
			padding: 0;
			min-width: 1px;
			pointer-events: none;
			align-self: center;
		}

		/* Редактируемая область */
		.wysiwyg__area {
			min-height: 320px;
			padding: 16px;
			outline: none;
			font-size: 15px;
			line-height: 1.7;
			color: var(--text-sec);
		}
		.wysiwyg__area h2 { font-size: 22px; font-weight: 700; color: #fff; margin: 16px 0 8px; font-family: 'Space Grotesk', sans-serif; }
		.wysiwyg__area h3 { font-size: 18px; font-weight: 700; color: #fff; margin: 12px 0 6px; font-family: 'Space Grotesk', sans-serif; }
		.wysiwyg__area ul { padding-left: 24px; list-style: disc; }
		.wysiwyg__area ol { padding-left: 24px; list-style: decimal; }
		.wysiwyg__area li { margin-bottom: 4px; }
		.wysiwyg__area a  { color: var(--accent-light); text-decoration: underline; }

		/* Скрытый textarea для значения */
		#contentInput { display: none; }

		/* ========== ЗАГРУЗКА КАРТИНКИ ========== */
		.upload {
			border: 2px dashed var(--border);
			border-radius: var(--radius);
			padding: 24px;
			text-align: center;
			cursor: pointer;
			transition: border-color 0.2s, background 0.2s;
			position: relative;
		}
		.upload:hover {
			border-color: var(--accent);
			background: rgba(79,70,229,0.06);
		}
		.upload input {
			position: absolute;
			inset: 0;
			opacity: 0;
			cursor: pointer;
			width: 100%;
			height: 100%;
		}
		.upload__text { font-size: 14px; color: var(--muted); }
		.upload__preview {
			width: 100%;
			max-height: 160px;
			object-fit: cover;
			border-radius: 10px;
			display: none;
			margin-top: 12px;
		}

		/* ========== КНОПКИ ========== */
		.btn-publish {
			height: 52px;
			background: var(--accent);
			color: #fff;
			border: none;
			border-radius: var(--radius);
			font-family: 'Inter', sans-serif;
			font-size: 16px;
			font-weight: 700;
			cursor: pointer;
			transition: background 0.2s, box-shadow 0.2s;
			box-shadow: 0 12px 28px rgba(79,70,229,0.35);
		}
		.btn-publish:hover {
			background: var(--accent-hover);
			box-shadow: 0 14px 32px rgba(79,70,229,0.45);
		}

		/* ========== СТАТУСЫ ========== */
		.status {
			padding: 14px 18px;
			border-radius: var(--radius);
			font-size: 14px;
			font-weight: 500;
		}
		.status--ok  { background: rgba(22,163,74,0.15); border: 1px solid rgba(22,163,74,0.35); color: #4ade80; }
		.status--err { background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.35); color: #f87171; }

		/* ========== ПРЕВЬЮ ========== */
		.preview {
			padding: 40px;
			overflow-y: auto;
			background: #0B1020;
		}

		.preview__label {
			font-size: 12px;
			font-weight: 600;
			letter-spacing: 0.08em;
			text-transform: uppercase;
			color: var(--muted);
			margin-bottom: 24px;
		}

		/* Стили статьи для превью — повторяем style_article.css */
		.preview .article { max-width: 100%; }

		.preview .article__date { font-size: 13px; color: var(--muted); display: block; margin-bottom: 16px; }

		.preview .article__title {
			font-family: 'Space Grotesk', sans-serif;
			font-size: clamp(24px, 3vw, 36px);
			font-weight: 800;
			line-height: 1.2;
			color: #fff;
			margin-bottom: 24px;
		}

		.preview .article__cover { margin-bottom: 24px; border-radius: 22px; overflow: hidden; }
		.preview .article__cover img { width: 100%; height: auto; display: block; }

		.preview .article__content { display: flex; flex-direction: column; gap: 16px; font-size: 15px; line-height: 1.75; color: var(--text-sec); }
		.preview .article__content h2 { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #fff; }
		.preview .article__content h3 { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
		.preview .article__content ul { padding-left: 24px; list-style: disc; }
		.preview .article__content ol { padding-left: 24px; list-style: decimal; }
		.preview .article__content li::marker { color: var(--accent-light); }
		.preview .article__content strong { color: #fff; }
		.preview .article__content a { color: var(--accent-light); text-decoration: underline; }
		.preview .article__content img { width: 100%; border-radius: 16px; }

		.preview .article-cta-box {
			background: rgba(255,255,255,0.06);
			border: 1px solid var(--border-accent);
			border-radius: 22px;
			padding: 24px;
			display: flex;
			flex-direction: column;
			gap: 12px;
		}
		.preview .article-cta-box h3 { font-size: 18px; color: var(--accent-light); }
		.preview .article-cta-box p  { font-size: 14px; color: var(--muted); }

		/* ========== АДАПТИВ ========== */
		@media (max-width: 1024px) {
			.admin { grid-template-columns: 1fr; }
			.preview { border-top: 1px solid var(--border); }
			.editor { border-right: none; }
		}
	</style>
</head>
<body>

<?php if (!$authed): ?>
<!-- ========== ЛОГИН ========== -->
<div class="login">
	<div class="login__box">
		<p class="login__title">Админка блога</p>
		<?php if (!empty($login_error)): ?>
			<p class="login__error"><?= htmlspecialchars($login_error) ?></p>
		<?php endif; ?>
		<form method="post">
			<div class="field">
				<label>Пароль</label>
				<input type="password" name="password" autofocus>
			</div>
			<br>
			<button type="submit" name="login" class="btn-publish" style="width:100%">Войти</button>
		</form>
	</div>
</div>

<?php else: ?>
<!-- ========== АДМИНКА ========== -->
<div class="admin">

	<!-- Редактор -->
	<form class="editor" method="post" enctype="multipart/form-data" id="adminForm">

		<div class="editor__header">
			<p class="editor__title">Новая статья</p>
			<a href="?logout" class="editor__logout">Выйти</a>
		</div>

		<?php if ($pub_success): ?>
			<div class="status status--ok">
				Опубликовано: <a href="/blog/<?= htmlspecialchars($pub_success) ?>/" style="color:inherit;text-decoration:underline;" target="_blank">/blog/<?= htmlspecialchars($pub_success) ?>/</a>
			</div>
		<?php endif; ?>

		<?php if ($pub_error): ?>
			<div class="status status--err"><?= htmlspecialchars($pub_error) ?></div>
		<?php endif; ?>

		<!-- Title + Slug -->
		<div class="field__row">
			<div class="field">
				<label for="title">Title</label>
				<input type="text" id="title" name="title" placeholder="Заголовок страницы" oninput="syncSlug(this.value); updatePreview()">
			</div>
			<div class="field">
				<label for="slug">Slug</label>
				<input type="text" id="slug" name="slug" placeholder="url-stati">
			</div>
		</div>

		<!-- Meta -->
		<div class="field">
			<label for="meta_desc">Meta description</label>
			<input type="text" id="meta_desc" name="meta_desc" placeholder="Описание для поисковиков">
		</div>
		<div class="field">
			<label for="meta_keywords">Meta keywords</label>
			<input type="text" id="meta_keywords" name="meta_keywords" placeholder="ключевое слово, ещё одно">
		</div>

		<!-- H1 -->
		<div class="field">
			<label for="h1">H1 — заголовок статьи</label>
			<input type="text" id="h1" name="h1" placeholder="Если отличается от Title" oninput="updatePreview()">
		</div>

		<!-- Контент -->
		<div class="field">
			<label>Контент</label>
			<div class="wysiwyg">
				<div class="wysiwyg__toolbar">
					<button type="button" class="wysiwyg__btn" onclick="fmt('bold')" title="Жирный"><b>B</b></button>
					<button type="button" class="wysiwyg__btn" onclick="fmt('italic')" title="Курсив"><i>I</i></button>
					<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
					<button type="button" class="wysiwyg__btn" onclick="fmtBlock('h2')">H2</button>
					<button type="button" class="wysiwyg__btn" onclick="fmtBlock('h3')">H3</button>
					<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
					<button type="button" class="wysiwyg__btn" onclick="fmt('insertUnorderedList')" title="Список">• —</button>
					<button type="button" class="wysiwyg__btn" onclick="fmt('insertOrderedList')" title="Нумерованный">1.</button>
					<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
					<button type="button" class="wysiwyg__btn" onclick="insertLink()" title="Ссылка">🔗</button>
					<button type="button" class="wysiwyg__btn" onclick="fmt('removeFormat')" title="Сбросить форматирование">✕</button>
				</div>
				<div class="wysiwyg__area" id="editor" contenteditable="true" oninput="syncContent(); updatePreview()"></div>
			</div>
			<textarea id="contentInput" name="content"></textarea>
		</div>

		<!-- Excerpt -->
		<div class="field">
			<label for="excerpt">Excerpt</label>
			<textarea id="excerpt" name="excerpt" rows="3" placeholder="Краткое описание для карточки в блоге"></textarea>
		</div>

		<!-- Обложка -->
		<div class="field">
			<label>Обложка</label>
			<div class="upload" id="uploadZone">
				<input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(this)">
				<p class="upload__text">Нажмите или перетащите изображение (jpg, png, webp)</p>
				<img class="upload__preview" id="imagePreview" alt="">
			</div>
		</div>

		<button type="submit" name="publish" class="btn-publish">Опубликовать статью →</button>

	</form>

	<!-- Превью -->
	<div class="preview" id="preview">
		<p class="preview__label">Превью</p>
		<div class="article">
			<time class="article__date" id="prev_date"><?php
				$months = [1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня',7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря'];
				echo date('j') . ' ' . $months[(int)date('n')] . ' ' . date('Y');
			?></time>
			<h1 class="article__title" id="prev_title">Заголовок статьи</h1>
			<div class="article__cover" id="prev_cover" style="display:none;">
				<img id="prev_img" src="" alt="">
			</div>
			<div class="article__content" id="prev_content"></div>
		</div>
	</div>

</div>
<?php endif; ?>

<script>
/* ========== SLUG ИЗ TITLE ========== */
function syncSlug(val) {
	/* Только если slug ещё не редактировался вручную */
	if (document.getElementById('slug').dataset.manual) return;
	const slug = val
		.toLowerCase()
		.replace(/[а-яёА-ЯЁ]/g, c => ({
			'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo',
			'ж':'zh','з':'z','и':'i','й':'y','к':'k','л':'l','м':'m',
			'н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u',
			'ф':'f','х':'kh','ц':'ts','ч':'ch','ш':'sh','щ':'shch',
			'ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya'
		}[c] ?? ''))
		.replace(/[^a-z0-9]+/g, '-')
		.replace(/^-+|-+$/g, '');
	document.getElementById('slug').value = slug;
}

/* Помечаем slug как ручной если его тронули */
document.getElementById('slug').addEventListener('input', function() {
	this.dataset.manual = '1';
});

/* ========== WYSIWYG ========== */
function fmt(cmd) {
	document.getElementById('editor').focus();
	document.execCommand(cmd, false, null);
	syncContent();
	updatePreview();
}

function fmtBlock(tag) {
	document.getElementById('editor').focus();
	document.execCommand('formatBlock', false, tag);
	syncContent();
	updatePreview();
}

function insertLink() {
	const url = prompt('Введите URL:');
	if (url) {
		document.getElementById('editor').focus();
		document.execCommand('createLink', false, url);
		syncContent();
		updatePreview();
	}
}

/* Синхронизируем contenteditable → скрытый textarea */
function syncContent() {
	document.getElementById('contentInput').value = document.getElementById('editor').innerHTML;
}

/* ========== ПРЕВЬЮ ========== */
function updatePreview() {
	const title   = document.getElementById('h1').value || document.getElementById('title').value || 'Заголовок статьи';
	const content = document.getElementById('editor').innerHTML;

	document.getElementById('prev_title').textContent = title;
	document.getElementById('prev_content').innerHTML  = content;
}

/* ========== ЗАГРУЗКА ИЗОБРАЖЕНИЯ ========== */
function previewImage(input) {
	const file = input.files[0];
	if (!file) return;
	const reader = new FileReader();
	reader.onload = e => {
		const img = document.getElementById('imagePreview');
		img.src = e.target.result;
		img.style.display = 'block';
		document.getElementById('prev_cover').style.display = 'block';
		document.getElementById('prev_img').src = e.target.result;
	};
	reader.readAsDataURL(file);
}

/* Синхронизируем контент перед отправкой формы */
document.getElementById('adminForm')?.addEventListener('submit', syncContent);
</script>
</body>
</html>