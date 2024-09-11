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


// -----------
// SVG SUPPORT
// -----------
function allow_svg_upload($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

function svg_thumbnail_support()
{
	if (function_exists('add_theme_support')) {
		add_theme_support('post-thumbnails');
		add_filter('wp_generate_attachment_metadata', 'svg_attachment_metadata', 10, 2);
	}
}

function svg_attachment_metadata($metadata, $attachment_id)
{
	$attachment = get_post($attachment_id);
	$mime_type  = get_post_mime_type($attachment);

	if ('image/svg+xml' === $mime_type) {
		$metadata['width']  = 0;
		$metadata['height'] = 0;
	}

	return $metadata;
}

add_action('after_setup_theme', 'svg_thumbnail_support');


// -----------
// GET VIDEO THUMBNAILS
// -----------
function get_video_thumbnail($video_link)
{
	if (strpos($video_link, 'vimeo.com') !== false) {
		$link_parts = explode("/", parse_url($video_link, PHP_URL_PATH));
		$video_id = end($link_parts);
		return "https://i.vimeocdn.com/video/" . $video_id . "_1280.jpg";
	} elseif (strpos($video_link, 'youtube.com') !== false || strpos($video_link, 'youtu.be') !== false) {
		$link = explode("?v=", $video_link);
		$link = $link[1];
		return "https://img.youtube.com/vi/" . $link . "/hqdefault.jpg";
	} else {
		return '';
	}
}

// -----------
// GET VIDEO IDS
// -----------

function get_youtube_video_id($url)
{
	$query_string = parse_url($url, PHP_URL_QUERY);
	parse_str($query_string, $params);
	if (isset($params['v'])) {
		return $params['v'];
	}
	return false;
}

function get_vimeo_video_id($url)
{
	preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $url, $matches);
	if (isset($matches[2])) {
		return $matches[2];
	}
	return false;
}
