<?php
header('Content-Type: application/json; charset=utf-8');

/* Только POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
	exit;
}

/* Получаем данные */
$name    = trim($_POST['name'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$message = trim($_POST['message'] ?? '');

/* Валидация */
if ($name === '' || $contact === '') {
	echo json_encode(['success' => false, 'error' => 'Заполните имя и контакт']);
	exit;
}

/* Настройки */
$to      = 'safronovvan@gmail.com';//-----------------------------------------------почта
$subject = 'Заявка с сайта — Услуги авитолога';

/* Тело письма */
$body  = "Новая заявка с сайта Engenix\n\n";
$body .= "Имя: {$name}\n";
$body .= "Контакт: {$contact}\n";
$body .= "Сообщение: {$message}\n";
$body .= "\n---\nОтправлено со страницы /uslugi/";

/* Заголовки */

$headers = "From: no-reply@" . $_SERVER["SERVER_NAME"] . "\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

/* Отправка */
$sent = mail($to, $subject, $body, $headers);

if ($sent) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
}