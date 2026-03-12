<?php

function engenix_assets() {

	$theme = get_template_directory_uri();

	// CSS
	wp_enqueue_style('style-main', $theme . '/source/css/style_main.css');
	wp_enqueue_style('style-second', $theme . '/source/css/style_second.css');
	wp_enqueue_style('style-services', $theme . '/source/css/style_services.css');
	wp_enqueue_style('style-info', $theme . '/source/css/style_info.css');

	wp_enqueue_style('style-verif-bank', $theme . '/source/css/verifikaciya-po-banku.css');
	wp_enqueue_style('style-verif-gos', $theme . '/source/css/verifikaciya-po-gosuslugam.css');
	wp_enqueue_style('style-verif-pass', $theme . '/source/css/verifikaciya-po-pasportu.css');
	wp_enqueue_style('style-verif-rekv', $theme . '/source/css/verifikaciya-po-rekvizitam.css');

	// JS
	wp_enqueue_script('main-js', $theme . '/source/js/main.js', array(), null, true);
	wp_enqueue_script('services-js', $theme . '/source/js/services.js', array(), null, true);

}

add_action('wp_enqueue_scripts', 'engenix_assets');