<?php
/**
 * @package Quartiersdisplays
 * @version 0.0.1
 */
/*
Plugin Name: Quartiersdisplays
Plugin URI: https://github.com/studio-arrenberg/quartiersplattform
Description: Dieses Plugin stellt die Schnittstelle zu den Quartiersdisplays.
Author: studio arrenberg
Version: 0.0.1
Author URI: https://arrenberg.studio
*/

# Admin note
add_action('admin_init', function() {
	add_action('admin_notices', function() {
		$notice = "<strong>Hallo Quartiersdisplays</strong>";
		$hint = "Quartiersdisplay Plugin aktiviert.";
		echo "<div class='updated notice'><p>$notice<br>$hint<br></p></div>";
	});
});

# Check for Quartiersplattform Theme
if (wp_get_theme() != "Quartiersplattform") {
	add_action('admin_init', function() {
		add_action('admin_notices', function() {
			$notice = "<strong>Die Quartiersplattform ist nicht Installiert</strong>";
			$hint = "Bitte die Quartiersplattform installieren und aktivieren.";
			echo "<div class='error'><p>$notice<br>$hint<br></p></div>";
		});
	});
}

// Fallback if Quartiersplattform is not installed
if (wp_get_theme() != "Quartiersplattform") {
	return;
}

// create page call Quartiersdisplay
add_action('init', function() {
	$slug = "quartiersdisplay";
	$title = "Quartiersdisplay";
	$content = "Quartiersdisplay";
	$parent = 0;
	$slug = sanitize_title($slug);
	$check = get_page_by_path($slug);
	if (!isset($check->ID)) {
		$page = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_name' => $slug,
			'post_parent' => $parent,
			'comment_status' => 'closed'
		);
		$page_id = wp_insert_post($page);
	}
});


// define template for quartiersdisplay page
add_filter('template_include', function($template) {
	if (is_page('quartiersdisplay')) {
		$new_template = plugin_dir_path(__FILE__) . 'display-page.php';
		if ('' != $new_template) {
			return $new_template ;
		}
	}
	return $template;
});

// include scritps and styles
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style( 'quartiersdisplay-css', plugins_url( '/includes/quartiersdisplay.css', __FILE__ ), false, null );
	wp_enqueue_script( 'quartiersdisplay-js', plugins_url( '/includes/quartiersdisplay.js', __FILE__ ), false, null );
});


# Quartiersdisplay API endpoint
add_action('init', function() {
	$slug = "quartiersdisplay-api";
	$title = "Quartiersdisplay-API";
	$content = "Quartiersdisplay-API";
	$parent = 0;
	$slug = sanitize_title($slug);
	$check = get_page_by_path($slug);
	if (!isset($check->ID)) {
		$page = array(
			'post_title' => $title,
			'post_content' => $content,
			'post_status' => 'publish',
			'post_type' => 'page',
			'post_name' => $slug,
			'post_parent' => $parent,
			'comment_status' => 'closed'
		);
		$page_id = wp_insert_post($page);
	}
});
# Add Page Template
add_filter('template_include', function($template) {
	if (is_page('quartiersdisplay-api')) {
		$new_template = plugin_dir_path(__FILE__) . 'display-api.php';
		if ('' != $new_template) {
			return $new_template ;
		}
	}
	return $template;
});

?>