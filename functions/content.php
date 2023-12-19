<?php

// -----------
// REGISTER CUSTOM NAVIGATION WALKER
// -----------
function register_navwalker()
{
	require_once get_template_directory() . '/functions/navwalker.php';
}
add_action('after_setup_theme', 'register_navwalker');

add_filter('nav_menu_link_attributes', 'prefix_bs5_dropdown_data_attribute', 20, 3);
/**
 * Use namespaced data attribute for Bootstrap's dropdown toggles.
 *
 * @param array    $atts HTML attributes applied to the item's `<a>` element.
 * @param WP_Post  $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return array
 */
function prefix_bs5_dropdown_data_attribute($atts, $item, $args)
{
	if (is_a($args->walker, 'WP_Bootstrap_Navwalker')) {
		if (array_key_exists('data-toggle', $atts)) {
			unset($atts['data-toggle']);
			$atts['data-bs-toggle'] = 'dropdown';
		}
	}
	return $atts;
}

// -----------
// REMOVE STRING WHITESPACE
// -----------
function clean($string)
{
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	return preg_replace('/[^a-zA-Z0-9]+/', '', $string); // Removes special chars.
}

// -----------
// ADD EXCERPT TO PAGES
// -----------
function add_excerpt_to_pages()
{
	add_post_type_support('page', 'excerpt');
}
add_action('init', 'add_excerpt_to_pages');

// -----------
// MODIFY EXCERPT LENGTH
// -----------
function custom_excerpt_length($length)
{
	return 25;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// -----------
// CHANGE MORE EXCERPT
// -----------
// function custom_more_excerpt($more)
// {
//     return '...';
// }
// add_filter('excerpt_more', 'custom_more_excerpt');
