<?php

function engenix_assets() {

	wp_enqueue_style(
		'engenix-style',
		get_template_directory_uri() . '/css/style.css',
		array(),
		null
	);

	wp_enqueue_script(
		'engenix-script',
		get_template_directory_uri() . '/js/app.js',
		array(),
		null,
		true
	);

}

add_action('wp_enqueue_scripts', 'engenix_assets');