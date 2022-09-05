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

// QP Add Menu Button
add_action( 'qp_menu_button', 'display_page', 10, 3 );
function display_page() {
	?>
		<!-- <a class="button header-button" href="<?php echo get_site_url()."/quartiersdisplay" ?>">
			<?php include_once( plugin_dir_path( __FILE__ ) . '/includes/assets/icons/ampelmann.svg'); ?>
		</a> -->
	<?php
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

# Add ACF Settingpage
add_action('init', function() {
	if( function_exists('acf_add_options_page') ) {
		acf_add_options_page(array(
			'page_title' 	=> 'Quartiersdisplays',
			'menu_title'	=> 'Quartiersdisplays',
			'menu_slug' 	=> 'quartiersdisplays',
			// 'parent_slug'	=> 'theme-general-settings', // creates error
			'capability'	=> 'edit_posts',
			'redirect'		=> false,
			'update_button' => __('Aktualisieren', 'acf'),
			'updated_message' => __("Die Einstellungen wurden gespeichert.", 'acf'),
			'icon_url' => 'dashicons-desktop',
		));
	}
});
# Add fields
add_action('init', function() {
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_6023ea77ebqs53',
			'title' => __('Quartiersdisplays Einstellungen',"quartiersplattform"),
			'fields' => array(
				array(
					'key' => 'field_6023ea77ebqs54',
					'label' => __('Anzahl der Slides',"quartiersplattform"),
					'name' => 'quartiersdisplays_slides',
					'type' => 'number',
					'instructions' => __('Anzahl der Slides, die angezeigt werden sollen.',"quartiersplattform"),
					'required' => 0,
					'default_value' => 10,
					'placeholder' => 10,
					'prepend' => '',
					'append' => '',
					'min' => 3,
					'max' => 20,
					'step' => 1,
				),
				array(
					'key' => 'field_6024ebe66b6425',
					'label' => __('Slide-Dauer',"quartiersdisplays"),
					'name' => 'quartiersdisplays_slide_duration',
					'type' => 'number',
					'instructions' => __('Hier kannst du die Dauer der Slides festlegen (in Sekunden).',"quartiersplattform"),
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 8,
					'placeholder' => __('Dauer der Slides in Sekunden',"quartiersplattform"),
					'prepend' => '',
					'append' => '',
					'maxlength' => 2,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'quartiersdisplays',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			)
		);
	endif;
});

?>