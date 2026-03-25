<?php
/* ========== КОНФИГ ========== */
define('DATA_DIR',     $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts');
define('CATALOG_FILE', $_SERVER['DOCUMENT_ROOT'] . '/source/data/posts.json');
define('IMG_DIR',      $_SERVER['DOCUMENT_ROOT'] . '/source/img/blog');
define('BLOG_DIR',     $_SERVER['DOCUMENT_ROOT'] . '/blog');
define('CONFIG_FILE',  $_SERVER['DOCUMENT_ROOT'] . '/blog/admin/config.php');

/* ========== СЕССИЯ ========== */
session_start();

$cfg   = require CONFIG_FILE;
$users = $cfg['users'];

/* Логин */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
	$login_name = trim($_POST['username'] ?? '');
	$login_pass = $_POST['password'] ?? '';
	if (isset($users[$login_name]) && $users[$login_name]['password'] === $login_pass) {
		$_SESSION['admin_user'] = $login_name;
		$_SESSION['admin_role'] = $users[$login_name]['role'];
	} else {
		$login_error = 'Неверный логин или пароль';
	}
}

/* Выход */
if (isset($_GET['logout'])) {
	session_destroy();
	header('Location: /blog/admin/');
	exit;
}

$authed   = !empty($_SESSION['admin_user']);
$role     = $_SESSION['admin_role'] ?? '';
$is_admin = $role === 'admin';

/* ========== СМЕНА ПАРОЛЕЙ ========== */
$pwd_success = '';
$pwd_error   = '';

if ($authed && $is_admin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_passwords'])) {
	$new_cfg   = $cfg;
	$has_error = false;
	foreach ($users as $uname => $udata) {
		$val = trim($_POST['pwd_' . $uname] ?? '');
		if ($val !== '') {
			if (mb_strlen($val) < 6) {
				$pwd_error = "Пароль для {$uname} слишком короткий (минимум 6 символов)";
				$has_error = true;
				break;
			}
			$new_cfg['users'][$uname]['password'] = $val;
		}
	}
	if (!$has_error) {
		$out = "<?php\n/**\n * config.php — учётные записи админки блога\n */\n\nreturn [\n\t'users' => [\n";
		foreach ($new_cfg['users'] as $uname => $udata) {
			$p    = addslashes($udata['password']);
			$r    = $udata['role'];
			$out .= "\t\t'{$uname}' => [\n\t\t\t'password' => '{$p}',\n\t\t\t'role'     => '{$r}',\n\t\t],\n";
		}
		$out .= "\t],\n];\n";
		file_put_contents(CONFIG_FILE, $out);
		$pwd_success = 'Пароли обновлены';
		$cfg   = require CONFIG_FILE;
		$users = $cfg['users'];
	}
}

/* ========== УДАЛЕНИЕ СТАТЬИ ========== */
$del_success = '';
$del_error   = '';

if ($authed && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
	$del_slug = preg_replace('/[^a-z0-9\-]/', '', $_POST['del_slug'] ?? '');
	if (!$del_slug) {
		$del_error = 'Не указан slug';
	} else {
		/* Удаляем JSON статьи */
		$json_file = DATA_DIR . '/' . $del_slug . '.json';
		$post_data_del = [];
		if (file_exists($json_file)) {
			$post_data_del = json_decode(file_get_contents($json_file), true) ?: [];
			unlink($json_file);
		}

		/* Удаляем изображение */
		if (!empty($post_data_del['image'])) {
			$img_file = $_SERVER['DOCUMENT_ROOT'] . $post_data_del['image'];
			if (file_exists($img_file)) unlink($img_file);
		}

		/* Удаляем папку /blog/[slug]/ */
		$article_dir = BLOG_DIR . '/' . $del_slug;
		if (is_dir($article_dir)) {
			$index_file = $article_dir . '/index.php';
			if (file_exists($index_file)) unlink($index_file);
			rmdir($article_dir);
		}

		/* Обновляем каталог */
		if (file_exists(CATALOG_FILE)) {
			$catalog = json_decode(file_get_contents(CATALOG_FILE), true) ?: [];
			$catalog = array_values(array_filter($catalog, fn($p) => $p['slug'] !== $del_slug));
			file_put_contents(CATALOG_FILE, json_encode($catalog, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		}

		$del_success = "Статья «{$del_slug}» удалена";
	}
}

/* ========== ПУБЛИКАЦИЯ / СОХРАНЕНИЕ ========== */
$pub_success = '';
$pub_error   = '';

if ($authed && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {

	$slug        = trim($_POST['slug'] ?? '');
	$title       = trim($_POST['title'] ?? '');
	$desc        = trim($_POST['meta_desc'] ?? '');
	$keywords    = trim($_POST['meta_keywords'] ?? '');
	$excerpt     = trim($_POST['excerpt'] ?? '');
	$content     = trim($_POST['content'] ?? '');
	$is_edit     = !empty($_POST['is_edit']);
	$orig_slug   = trim($_POST['orig_slug'] ?? $slug);

	if (!$slug || !$title || !$content) {
		$pub_error = 'Заполните slug, title и контент';
	} elseif (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
		$pub_error = 'Slug — только латиница, цифры и дефис';
	} else {

		/* Читаем существующие данные если редактирование */
		$existing = [];
		if ($is_edit && file_exists(DATA_DIR . '/' . $orig_slug . '.json')) {
			$existing = json_decode(file_get_contents(DATA_DIR . '/' . $orig_slug . '.json'), true) ?: [];
		}

		/* Обложка */
		$img_local = $existing['image'] ?? '';
		if (!empty($_FILES['image']['tmp_name'])) {
			$ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
			$allowed = ['jpg', 'jpeg', 'png', 'webp'];
			if (!in_array($ext, $allowed)) {
				$pub_error = 'Допустимые форматы: jpg, png, webp';
			} else {
				$filename = $slug . '.' . $ext;
				$img_path = IMG_DIR . '/' . $filename;
				if (move_uploaded_file($_FILES['image']['tmp_name'], $img_path)) {
					$img_local = '/source/img/blog/' . $filename;
				} else {
					$pub_error = 'Не удалось сохранить изображение';
				}
			}
		}

		if (!$pub_error) {
			$months   = [1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня',7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря'];
			$now      = new DateTime();

			/* При редактировании сохраняем оригинальную дату */
			$date     = $existing['date']     ?? $now->format('Y-m-d\TH:i:s');
			$date_fmt = $existing['date_fmt'] ?? ($now->format('j') . ' ' . $months[(int)$now->format('n')] . ' ' . $now->format('Y'));

			$post_data = [
				'slug'          => $slug,
				'title'         => $title,
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
			$catalog = array_filter($catalog, fn($p) => $p['slug'] !== $orig_slug && $p['slug'] !== $slug);
			$catalog_entry = [
				'slug'     => $slug,
				'title'    => $title,
				'excerpt'  => $excerpt,
				'date'     => $date,
				'date_fmt' => $date_fmt,
				'image'    => $img_local,
			];
			/* Новая статья — в начало, редактирование — на своё место по дате */
			if ($is_edit) {
				array_unshift($catalog, $catalog_entry);
				usort($catalog, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
			} else {
				array_unshift($catalog, $catalog_entry);
			}
			file_put_contents(CATALOG_FILE, json_encode(array_values($catalog), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

			/* Создаём /blog/[slug]/index.php */
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

/* ========== ЗАГРУЗКА СТАТЬИ ДЛЯ РЕДАКТИРОВАНИЯ ========== */
$edit_post = null;
if ($authed && !empty($_GET['edit'])) {
	$edit_slug = preg_replace('/[^a-z0-9\-]/', '', $_GET['edit']);
	$edit_file = DATA_DIR . '/' . $edit_slug . '.json';
	if (file_exists($edit_file)) {
		$edit_post = json_decode(file_get_contents($edit_file), true);
	}
}

/* ========== КАТАЛОГ ДЛЯ СПИСКА СТАТЕЙ ========== */
$all_posts = [];
if ($authed && file_exists(CATALOG_FILE)) {
	$all_posts = json_decode(file_get_contents(CATALOG_FILE), true) ?: [];
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
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		html { font-size: 16px; }
		body { background: #0B1020; color: #fff; font-family: 'Inter', sans-serif; min-height: 100vh; }

		:root {
			--accent:        #4F46E5;
			--accent-hover:  #4338CA;
			--accent-light:  #C7D2FE;
			--border:        rgba(255,255,255,0.12);
			--border-accent: rgba(79,70,229,0.35);
			--card:          rgba(255,255,255,0.06);
			--muted:         rgba(255,255,255,0.46);
			--text-sec:      rgba(255,255,255,0.82);
			--radius:        14px;
			--danger:        #DC2626;
		}

		/* ========== ЛОГИН ========== */
		.login { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
		.login__box { background: var(--card); border: 1px solid var(--border); border-radius: 22px; padding: 40px; width: 100%; max-width: 380px; display: flex; flex-direction: column; gap: 20px; }
		.login__title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: var(--accent-light); }
		.login__error { font-size: 14px; color: #f87171; }

		/* ========== LAYOUT ========== */
		.admin { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }
		.admin--full { grid-template-columns: 1fr; }

		/* ========== ПАНЕЛЬ ========== */
		.editor { padding: 40px; border-right: 1px solid var(--border); overflow-y: auto; display: flex; flex-direction: column; gap: 24px; }
		.editor--full { border-right: none; max-width: 900px; margin: 0 auto; width: 100%; }

		.editor__header { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
		.editor__title  { font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 700; color: var(--accent-light); }
		.editor__nav    { display: flex; align-items: center; gap: 12px; }
		.editor__nav-link { font-size: 13px; color: var(--muted); text-decoration: none; padding: 6px 12px; border-radius: 8px; transition: background 0.2s, color 0.2s; }
		.editor__nav-link:hover     { background: var(--card); color: #fff; }
		.editor__nav-link.is-active { background: var(--card); color: var(--accent-light); }
		.editor__logout { font-size: 13px; color: var(--muted); text-decoration: none; transition: color 0.2s; }
		.editor__logout:hover { color: #fff; }

		/* ========== ПОЛЯ ========== */
		.field { display: flex; flex-direction: column; gap: 8px; }
		.field__row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
		label { font-size: 13px; font-weight: 500; color: var(--accent-light); letter-spacing: 0.04em; text-transform: uppercase; }

		input[type=text], input[type=password], textarea {
			width: 100%; background: rgba(255,255,255,0.06); color: #fff;
			border: 1px solid var(--border); border-radius: var(--radius);
			font-family: 'Inter', sans-serif; font-size: 15px; padding: 12px 16px;
			outline: none; resize: vertical; transition: border-color 0.2s, box-shadow 0.2s;
		}
		input[type=text]:focus, input[type=password]:focus, textarea:focus {
			border-color: rgba(79,70,229,0.55); box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
		}
		input[type=text][readonly] { opacity: 0.5; cursor: not-allowed; }
		input::placeholder, textarea::placeholder { color: var(--muted); }

		/* ========== ВКЛАДКИ КОНТЕНТА ========== */
		.tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--border); }
		.tab { padding: 8px 18px; font-size: 13px; font-weight: 600; color: var(--muted); cursor: pointer; border-radius: 10px 10px 0 0; border: 1px solid transparent; border-bottom: none; background: transparent; font-family: 'Inter', sans-serif; transition: color 0.2s, background 0.2s; }
		.tab:hover { color: #fff; }
		.tab.is-active { color: var(--accent-light); background: rgba(255,255,255,0.06); border-color: var(--border); }
		.tab-panel { display: none; }
		.tab-panel.is-active { display: block; }

		/* ========== WYSIWYG ========== */
		.wysiwyg { border: 1px solid var(--border); border-radius: 0 var(--radius) var(--radius) var(--radius); overflow: hidden; transition: border-color 0.2s; }
		.wysiwyg:focus-within { border-color: rgba(79,70,229,0.55); box-shadow: 0 0 0 4px rgba(79,70,229,0.12); }
		.wysiwyg__toolbar { display: flex; gap: 4px; padding: 8px 12px; background: rgba(255,255,255,0.04); border-bottom: 1px solid var(--border); flex-wrap: wrap; }
		.wysiwyg__btn { height: 32px; min-width: 32px; padding: 0 10px; border-radius: 8px; background: transparent; color: var(--text-sec); border: 1px solid transparent; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s, color 0.15s, border-color 0.15s; }
		.wysiwyg__btn:hover { background: rgba(255,255,255,0.08); color: #fff; border-color: var(--border); }
		.wysiwyg__btn--sep  { width: 1px; height: 24px; background: var(--border); padding: 0; min-width: 1px; pointer-events: none; align-self: center; }
		.wysiwyg__area { min-height: 320px; padding: 16px; outline: none; font-size: 15px; line-height: 1.7; color: var(--text-sec); }
		.wysiwyg__area h2 { font-size: 22px; font-weight: 700; color: #fff; margin: 16px 0 8px; font-family: 'Space Grotesk', sans-serif; }
		.wysiwyg__area h3 { font-size: 18px; font-weight: 700; color: #fff; margin: 12px 0 6px; font-family: 'Space Grotesk', sans-serif; }
		.wysiwyg__area ul  { padding-left: 24px; list-style: disc; }
		.wysiwyg__area ol  { padding-left: 24px; list-style: decimal; }
		.wysiwyg__area li  { margin-bottom: 4px; }
		.wysiwyg__area a   { color: var(--accent-light); text-decoration: underline; }

		.html-editor { min-height: 360px; padding: 16px; font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.6; color: #a5f3fc; background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 0 var(--radius) var(--radius) var(--radius); resize: vertical; width: 100%; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
		.html-editor:focus { border-color: rgba(79,70,229,0.55); box-shadow: 0 0 0 4px rgba(79,70,229,0.12); }
		#contentInput { display: none; }

		/* ========== ЗАГРУЗКА КАРТИНКИ ========== */
		.upload { border: 2px dashed var(--border); border-radius: var(--radius); padding: 24px; text-align: center; cursor: pointer; transition: border-color 0.2s, background 0.2s; position: relative; }
		.upload:hover { border-color: var(--accent); background: rgba(79,70,229,0.06); }
		.upload input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
		.upload__text { font-size: 14px; color: var(--muted); }
		.upload__preview { width: 100%; max-height: 160px; object-fit: cover; border-radius: 10px; margin-top: 12px; }

		/* ========== КНОПКИ ========== */
		.btn-publish { height: 52px; background: var(--accent); color: #fff; border: none; border-radius: var(--radius); font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 700; cursor: pointer; box-shadow: 0 12px 28px rgba(79,70,229,0.35); transition: background 0.2s, box-shadow 0.2s; }
		.btn-publish:hover { background: var(--accent-hover); box-shadow: 0 14px 32px rgba(79,70,229,0.45); }
		.btn-save { height: 44px; padding: 0 24px; background: transparent; color: var(--accent-light); border: 1px solid var(--border-accent); border-radius: var(--radius); font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s, color 0.2s; }
		.btn-save:hover { background: rgba(79,70,229,0.12); color: #fff; }
		.btn-danger { height: 36px; padding: 0 16px; background: transparent; color: #f87171; border: 1px solid rgba(220,38,38,0.4); border-radius: 10px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
		.btn-danger:hover { background: rgba(220,38,38,0.12); }
		.btn-edit { height: 36px; padding: 0 16px; background: transparent; color: var(--accent-light); border: 1px solid var(--border-accent); border-radius: 10px; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; transition: background 0.2s; }
		.btn-edit:hover { background: rgba(79,70,229,0.12); }

		/* ========== СТАТУСЫ ========== */
		.status { padding: 14px 18px; border-radius: var(--radius); font-size: 14px; font-weight: 500; }
		.status--ok  { background: rgba(22,163,74,0.15);  border: 1px solid rgba(22,163,74,0.35);  color: #4ade80; }
		.status--err { background: rgba(220,38,38,0.15);  border: 1px solid rgba(220,38,38,0.35);  color: #f87171; }

		/* ========== СПИСОК СТАТЕЙ ========== */
		.posts-search { width: 100%; background: rgba(255,255,255,0.06); color: #fff; border: 1px solid var(--border); border-radius: var(--radius); font-family: 'Inter', sans-serif; font-size: 15px; padding: 12px 16px; outline: none; transition: border-color 0.2s; }
		.posts-search:focus { border-color: rgba(79,70,229,0.55); }
		.posts-search::placeholder { color: var(--muted); }

		.posts-list { display: flex; flex-direction: column; gap: 12px; }

		.post-item { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; transition: border-color 0.2s; }
		.post-item:hover { border-color: rgba(255,255,255,0.2); }

		.post-item__info { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
		.post-item__title { font-size: 15px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
		.post-item__meta  { font-size: 12px; color: var(--muted); }

		.post-item__actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

		/* Модалка подтверждения удаления */
		.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 200; opacity: 0; pointer-events: none; transition: opacity 0.2s; }
		.modal-overlay.is-open { opacity: 1; pointer-events: auto; }
		.modal { background: #121933; border: 1px solid var(--border); border-radius: 22px; padding: 32px; max-width: 400px; width: 100%; display: flex; flex-direction: column; gap: 20px; }
		.modal__title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
		.modal__text  { font-size: 14px; color: var(--muted); line-height: 1.6; }
		.modal__slug  { color: #f87171; font-weight: 600; }
		.modal__actions { display: flex; gap: 12px; }
		.btn-cancel { height: 44px; padding: 0 24px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: var(--radius); font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; transition: color 0.2s; }
		.btn-cancel:hover { color: #fff; }

		/* ========== СМЕНА ПАРОЛЕЙ ========== */
		.passwords { display: flex; flex-direction: column; gap: 24px; }
		.passwords__title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: var(--accent-light); }
		.passwords__hint  { font-size: 13px; color: var(--muted); margin-top: -8px; }
		.passwords__grid  { display: flex; flex-direction: column; gap: 16px; }

		/* ========== ПРЕВЬЮ ========== */
		.preview { padding: 40px; overflow-y: auto; background: #0B1020; }
		.preview__label { font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 24px; }
		.preview .article__date  { font-size: 13px; color: var(--muted); display: block; margin-bottom: 16px; }
		.preview .article__title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(24px, 3vw, 36px); font-weight: 800; line-height: 1.2; color: #fff; margin-bottom: 24px; }
		.preview .article__cover { margin-bottom: 24px; border-radius: 22px; overflow: hidden; }
		.preview .article__cover img { width: 100%; height: auto; display: block; }
		.preview .article__content { display: flex; flex-direction: column; gap: 16px; font-size: 15px; line-height: 1.75; color: var(--text-sec); }
		.preview .article__content h2 { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #fff; }
		.preview .article__content h3 { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
		.preview .article__content ul { padding-left: 24px; list-style: disc; }
		.preview .article__content ol { padding-left: 24px; list-style: decimal; }
		.preview .article__content li::marker { color: var(--accent-light); }
		.preview .article__content strong { color: #fff; }
		.preview .article__content a  { color: var(--accent-light); text-decoration: underline; }
		.preview .article__content img { width: 100%; border-radius: 16px; }
		.preview .article-cta-box { background: rgba(255,255,255,0.06); border: 1px solid var(--border-accent); border-radius: 22px; padding: 24px; display: flex; flex-direction: column; gap: 12px; }
		.preview .article-cta-box h3 { font-size: 18px; color: var(--accent-light); }
		.preview .article-cta-box p  { font-size: 14px; color: var(--muted); }

		@media (max-width: 1024px) {
			.admin { grid-template-columns: 1fr; }
			.preview { border-top: 1px solid var(--border); }
			.editor  { border-right: none; }
		}
	</style>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i] = m[i] || function() {(m[i].a = m[i].a || []).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.yandex.ru/metrika/tag.js', 'ym');

        ym(100428952, 'init', {webvisor:true, clickmap:true, referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/100428952" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <meta name="google-site-verification" content="vlSGXSb0c9NGqk46LjNoqF7aWFhiVRuAQKXyenRHjEM" />
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
			<div class="field" style="margin-bottom:16px;">
				<label>Логин</label>
				<input type="text" name="username" autofocus autocomplete="username">
			</div>
			<div class="field" style="margin-bottom:20px;">
				<label>Пароль</label>
				<input type="password" name="password" autocomplete="current-password">
			</div>
			<button type="submit" name="login" class="btn-publish" style="width:100%">Войти</button>
		</form>
	</div>
</div>

<?php else:
	$page = $_GET['page'] ?? 'editor';
	if ($page === 'passwords' && !$is_admin) $page = 'editor';
	$is_editor_page = in_array($page, ['editor', 'edit']);
?>

<!-- ========== МОДАЛКА УДАЛЕНИЯ ========== -->
<div class="modal-overlay" id="deleteModal">
	<div class="modal">
		<p class="modal__title">Удалить статью?</p>
		<p class="modal__text">Будет удалена статья <span class="modal__slug" id="modalSlugLabel"></span>. Это действие необратимо.</p>
		<div class="modal__actions">
			<form method="post" id="deleteForm">
				<input type="hidden" name="del_slug" id="modalSlugInput">
				<button type="submit" name="delete" class="btn-danger">Удалить</button>
			</form>
			<button type="button" class="btn-cancel" onclick="closeDeleteModal()">Отмена</button>
		</div>
	</div>
</div>

<div class="admin <?= !$is_editor_page ? 'admin--full' : '' ?>">

	<!-- Левая панель -->
	<form class="editor <?= !$is_editor_page ? 'editor--full' : '' ?>" method="post" enctype="multipart/form-data" id="adminForm">

		<!-- Шапка -->
		<div class="editor__header">
			<p class="editor__title">
				Блог / <?= $is_admin ? 'admin' : htmlspecialchars($_SESSION['admin_user']) ?>
				<?php if ($page === 'edit' && $edit_post): ?>
					<span style="color:var(--muted);font-size:14px;font-weight:400;"> — редактирование</span>
				<?php endif; ?>
			</p>
			<div class="editor__nav">
				<a href="?page=editor"  class="editor__nav-link <?= $page === 'editor' ? 'is-active' : '' ?>">+ Статья</a>
				<a href="?page=posts"   class="editor__nav-link <?= $page === 'posts'  ? 'is-active' : '' ?>">Статьи</a>
				<?php if ($is_admin): ?>
				<a href="?page=passwords" class="editor__nav-link <?= $page === 'passwords' ? 'is-active' : '' ?>">Пароли</a>
				<?php endif; ?>
				<a href="?logout" class="editor__logout">Выйти</a>
			</div>
		</div>

		<?php if ($page === 'passwords'): ?>
		<!-- ========== СМЕНА ПАРОЛЕЙ ========== -->
		<div class="passwords">
			<p class="passwords__title">Управление паролями</p>
			<p class="passwords__hint">Оставьте поле пустым, чтобы не менять пароль. Минимум 6 символов.</p>

			<?php if ($pwd_success): ?>
				<div class="status status--ok"><?= htmlspecialchars($pwd_success) ?></div>
			<?php endif; ?>
			<?php if ($pwd_error): ?>
				<div class="status status--err"><?= htmlspecialchars($pwd_error) ?></div>
			<?php endif; ?>

			<div class="passwords__grid">
				<?php foreach ($users as $uname => $udata): ?>
				<div class="field">
					<label><?= htmlspecialchars($uname) ?> <span style="color:var(--muted);font-weight:400;text-transform:none;">(<?= $udata['role'] ?>)</span></label>
					<input type="password" name="pwd_<?= htmlspecialchars($uname) ?>" placeholder="Новый пароль" autocomplete="new-password">
				</div>
				<?php endforeach; ?>
			</div>
			<button type="submit" name="change_passwords" class="btn-save" style="align-self:flex-start;">Сохранить пароли</button>
		</div>

		<?php elseif ($page === 'posts'): ?>
		<!-- ========== СПИСОК СТАТЕЙ ========== -->

		<?php if ($del_success): ?>
			<div class="status status--ok"><?= htmlspecialchars($del_success) ?></div>
		<?php endif; ?>
		<?php if ($del_error): ?>
			<div class="status status--err"><?= htmlspecialchars($del_error) ?></div>
		<?php endif; ?>

		<input type="text" class="posts-search" id="postsSearch" placeholder="Поиск по заголовку или slug..." oninput="filterPosts(this.value)">

		<div class="posts-list" id="postsList">
			<?php if (empty($all_posts)): ?>
				<p style="color:var(--muted);font-size:14px;">Статей пока нет.</p>
			<?php else: ?>
				<?php foreach ($all_posts as $p): ?>
				<div class="post-item" data-search="<?= htmlspecialchars(mb_strtolower($p['title'] . ' ' . $p['slug'])) ?>">
					<div class="post-item__info">
						<p class="post-item__title"><?= htmlspecialchars($p['title']) ?></p>
						<p class="post-item__meta"><?= htmlspecialchars($p['date_fmt']) ?> &nbsp;·&nbsp; /blog/<?= htmlspecialchars($p['slug']) ?>/</p>
					</div>
					<div class="post-item__actions">
						<a href="/blog/<?= htmlspecialchars($p['slug']) ?>/" class="btn-edit" target="_blank" title="Открыть">↗</a>
						<a href="?page=edit&edit=<?= htmlspecialchars($p['slug']) ?>" class="btn-edit">Изменить</a>
						<button type="button" class="btn-danger" onclick="openDeleteModal('<?= htmlspecialchars($p['slug']) ?>', '<?= htmlspecialchars(addslashes($p['title'])) ?>')">Удалить</button>
					</div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php else: ?>
		<!-- ========== РЕДАКТОР (новая / редактирование) ========== -->

		<?php if ($pub_success): ?>
			<div class="status status--ok">
				Сохранено: <a href="/blog/<?= htmlspecialchars($pub_success) ?>/" style="color:inherit;text-decoration:underline;" target="_blank">/blog/<?= htmlspecialchars($pub_success) ?>/</a>
			</div>
		<?php endif; ?>
		<?php if ($pub_error): ?>
			<div class="status status--err"><?= htmlspecialchars($pub_error) ?></div>
		<?php endif; ?>

		<?php
			/* Значения полей — из редактируемой статьи или пустые */
			$f_slug     = $edit_post['slug']          ?? '';
			$f_title    = $edit_post['title']         ?? '';
			$f_desc     = $edit_post['meta_desc']     ?? '';
			$f_keywords = $edit_post['meta_keywords'] ?? '';
			$f_excerpt  = $edit_post['excerpt']       ?? '';
			$f_content  = $edit_post['content']       ?? '';
			$f_image    = $edit_post['image']         ?? '';
			$is_edit_mode = ($page === 'edit' && $edit_post);
		?>

		<?php if ($is_edit_mode): ?>
			<input type="hidden" name="is_edit" value="1">
			<input type="hidden" name="orig_slug" value="<?= htmlspecialchars($f_slug) ?>">
		<?php endif; ?>

		<!-- Title + Slug -->
		<div class="field__row">
			<div class="field">
				<label for="title">Title</label>
				<input type="text" id="title" name="title" placeholder="Заголовок страницы"
					value="<?= htmlspecialchars($f_title) ?>"
					oninput="<?= $is_edit_mode ? '' : 'syncSlug(this.value);' ?> updatePreview()">
			</div>
			<div class="field">
				<label for="slug">Slug</label>
				<input type="text" id="slug" name="slug"
					value="<?= htmlspecialchars($f_slug) ?>"
					placeholder="url-stati"
					<?= $is_edit_mode ? 'readonly title="Slug нельзя менять при редактировании"' : '' ?>>
			</div>
		</div>

		<!-- Meta -->
		<div class="field">
			<label for="meta_desc">Meta description</label>
			<input type="text" id="meta_desc" name="meta_desc" placeholder="Описание для поисковиков" value="<?= htmlspecialchars($f_desc) ?>">
		</div>
		<div class="field">
			<label for="meta_keywords">Meta keywords</label>
			<input type="text" id="meta_keywords" name="meta_keywords" placeholder="ключевое слово, ещё одно" value="<?= htmlspecialchars($f_keywords) ?>">
		</div>

		<!-- Контент — вкладки -->
		<div class="field">
			<label>Контент</label>
			<div class="tabs">
				<button type="button" class="tab is-active" onclick="switchTab('wysiwyg', this)">Редактор</button>
				<button type="button" class="tab"           onclick="switchTab('html', this)">HTML</button>
			</div>

			<div class="tab-panel is-active" id="panel-wysiwyg">
				<div class="wysiwyg">
					<div class="wysiwyg__toolbar">
						<button type="button" class="wysiwyg__btn" onclick="fmt('bold')"                title="Жирный"><b>B</b></button>
						<button type="button" class="wysiwyg__btn" onclick="fmt('italic')"              title="Курсив"><i>I</i></button>
						<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
						<button type="button" class="wysiwyg__btn" onclick="fmtBlock('h2')">H2</button>
						<button type="button" class="wysiwyg__btn" onclick="fmtBlock('h3')">H3</button>
						<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
						<button type="button" class="wysiwyg__btn" onclick="fmt('insertUnorderedList')" title="Список">• —</button>
						<button type="button" class="wysiwyg__btn" onclick="fmt('insertOrderedList')"   title="Нумерованный">1.</button>
						<div class="wysiwyg__btn wysiwyg__btn--sep"></div>
						<button type="button" class="wysiwyg__btn" onclick="insertLink()"               title="Ссылка">🔗</button>
						<button type="button" class="wysiwyg__btn" onclick="fmt('removeFormat')"        title="Сбросить">✕</button>
					</div>
					<div class="wysiwyg__area" id="editor" contenteditable="true" oninput="syncFromWysiwyg(); updatePreview()"><?= $f_content ?></div>
				</div>
			</div>

			<div class="tab-panel" id="panel-html">
				<textarea class="html-editor" id="htmlEditor" rows="20"
					placeholder="<h2>Заголовок раздела</h2>&#10;<p>Текст статьи...</p>"
					oninput="syncFromHtml(); updatePreview()"><?= htmlspecialchars($f_content) ?></textarea>
			</div>

			<textarea id="contentInput" name="content"><?= htmlspecialchars($f_content) ?></textarea>
		</div>

		<!-- Excerpt -->
		<div class="field">
			<label for="excerpt">Excerpt</label>
			<textarea id="excerpt" name="excerpt" rows="3" placeholder="Краткое описание для карточки в блоге"><?= htmlspecialchars($f_excerpt) ?></textarea>
		</div>

		<!-- Обложка -->
		<div class="field">
			<label>Обложка<?= $f_image ? ' <span style="color:var(--muted);font-weight:400;text-transform:none;">(есть)</span>' : '' ?></label>
			<?php if ($f_image): ?>
				<img src="<?= htmlspecialchars($f_image) ?>" class="upload__preview" style="display:block;" alt="">
			<?php endif; ?>
			<div class="upload">
				<input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(this)">
				<p class="upload__text"><?= $f_image ? 'Загрузить другое изображение' : 'Нажмите или перетащите изображение (jpg, png, webp)' ?></p>
				<img class="upload__preview" id="imagePreview" alt="">
			</div>
		</div>

		<button type="submit" name="publish" class="btn-publish">
			<?= $is_edit_mode ? 'Сохранить изменения →' : 'Опубликовать статью →' ?>
		</button>

		<?php endif; ?>
	</form>

	<!-- Правая панель — превью только для редактора -->
	<?php if ($is_editor_page): ?>
	<div class="preview">
		<p class="preview__label">Превью</p>
		<div class="article">
			<time class="article__date"><?php
				$mn = [1=>'января',2=>'февраля',3=>'марта',4=>'апреля',5=>'мая',6=>'июня',7=>'июля',8=>'августа',9=>'сентября',10=>'октября',11=>'ноября',12=>'декабря'];
				echo isset($edit_post['date_fmt']) ? htmlspecialchars($edit_post['date_fmt']) : date('j') . ' ' . $mn[(int)date('n')] . ' ' . date('Y');
			?></time>
			<div class="article__cover" id="prev_cover" style="<?= $f_image ? '' : 'display:none;' ?>">
				<img id="prev_img" src="<?= htmlspecialchars($f_image) ?>" alt="">
			</div>
			<div class="article__content" id="prev_content"><?= $f_content ?></div>
		</div>
	</div>
	<?php endif; ?>

</div>
<?php endif; ?>

<script>
/* ========== SLUG ========== */
function syncSlug(val) {
	if (document.getElementById('slug')?.dataset.manual) return;
	const map = {'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'yo','ж':'zh','з':'z','и':'i','й':'y','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t','у':'u','ф':'f','х':'kh','ц':'ts','ч':'ch','ш':'sh','щ':'shch','ъ':'','ы':'y','ь':'','э':'e','ю':'yu','я':'ya'};
	const slug = val.toLowerCase().replace(/[а-яёА-ЯЁ]/g, c => map[c] ?? '').replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
	document.getElementById('slug').value = slug;
}
document.getElementById('slug')?.addEventListener('input', function() {
	if (!this.readOnly) this.dataset.manual = '1';
});

/* ========== ВКЛАДКИ ========== */
function switchTab(name, btn) {
	document.querySelectorAll('.tab').forEach(t => t.classList.remove('is-active'));
	document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('is-active'));
	btn.classList.add('is-active');
	document.getElementById('panel-' + name).classList.add('is-active');
	const current = document.getElementById('contentInput')?.value || '';
	if (name === 'wysiwyg') document.getElementById('editor').innerHTML   = current;
	else                    document.getElementById('htmlEditor').value   = current;
}

/* ========== СИНХРОНИЗАЦИЯ ========== */
function syncFromWysiwyg() {
	const html = document.getElementById('editor').innerHTML;
	document.getElementById('contentInput').value = html;
	document.getElementById('htmlEditor').value   = html;
}
function syncFromHtml() {
	const html = document.getElementById('htmlEditor').value;
	document.getElementById('contentInput').value = html;
	document.getElementById('editor').innerHTML   = html;
}

/* ========== WYSIWYG ========== */
function fmt(cmd) { document.getElementById('editor').focus(); document.execCommand(cmd, false, null); syncFromWysiwyg(); updatePreview(); }
function fmtBlock(tag) { document.getElementById('editor').focus(); document.execCommand('formatBlock', false, tag); syncFromWysiwyg(); updatePreview(); }
function insertLink() {
	const url = prompt('Введите URL:');
	if (url) { document.getElementById('editor').focus(); document.execCommand('createLink', false, url); syncFromWysiwyg(); updatePreview(); }
}

/* ========== ПРЕВЬЮ ========== */
function updatePreview() {
	const content = document.getElementById('contentInput')?.value || '';
	const pc = document.getElementById('prev_content');
	if (pc) pc.innerHTML = content;
}

/* ========== ЗАГРУЗКА ИЗОБРАЖЕНИЯ ========== */
function previewImage(input) {
	const file = input.files[0];
	if (!file) return;
	const reader = new FileReader();
	reader.onload = e => {
		document.getElementById('imagePreview').src = e.target.result;
		document.getElementById('imagePreview').style.display = 'block';
		const cover = document.getElementById('prev_cover');
		const pimg  = document.getElementById('prev_img');
		if (cover) cover.style.display = 'block';
		if (pimg)  pimg.src = e.target.result;
	};
	reader.readAsDataURL(file);
}

/* ========== ПОИСК ПО СТАТЬЯМ ========== */
function filterPosts(q) {
	const term = q.toLowerCase();
	document.querySelectorAll('.post-item').forEach(el => {
		el.style.display = el.dataset.search.includes(term) ? '' : 'none';
	});
}

/* ========== МОДАЛКА УДАЛЕНИЯ ========== */
function openDeleteModal(slug, title) {
	document.getElementById('modalSlugLabel').textContent = title + ' (' + slug + ')';
	document.getElementById('modalSlugInput').value = slug;
	document.getElementById('deleteModal').classList.add('is-open');
}
function closeDeleteModal() {
	document.getElementById('deleteModal').classList.remove('is-open');
}
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
	if (e.target === this) closeDeleteModal();
});

/* Синхронизация перед отправкой */
document.getElementById('adminForm')?.addEventListener('submit', () => {
	if (!document.getElementById('contentInput')?.value) {
		const ed = document.getElementById('editor');
		if (ed) document.getElementById('contentInput').value = ed.innerHTML;
	}
});
</script>
</body>
</html>