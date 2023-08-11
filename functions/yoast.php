<?php


// -----------
// MOVE YOAST TO THE BOTTOM IN ADMIN
// -----------
function yoasttobottom()
{
	return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');

// -----------
// YOAST SOCIAL LINKS
// -----------

/**
 * Social Links
 * Uses Social URLs specified in Yoast SEO. See SEO > Social
 *
 */
function ea_social_links()
{
	$options = array(
		'facebook'   => array(
			'key'  => 'facebook_site',
			'icon'         => '<i class="fa-brands fa-facebook-f"></i>',
		),
		'twitter'    => array(
			'key'     => 'twitter_site',
			'prepend' => 'https://twitter.com/',
			'icon'    => '<i class="fa-brands fa-twitter"></i>',
		),
	);

	$options = apply_filters('ea_social_link_options', $options);

	$output = array();

	$seo_data = get_option('wpseo_social');

	foreach ($options as $social => $settings) {
		$url = !empty($seo_data[$settings['key']]) ? $seo_data[$settings['key']] : false;

		if (!empty($url) && !empty($settings['prepend']))
			$url = $settings['prepend'] . $url;

		if ($url && !empty($settings['icon'])) {
			$output[] = '<a href="' . esc_url_raw($url) . '" target="_blank" rel="noopener">' . $settings['icon'] . '<span class="visually-hidden">' . $social . '</span></a>';
		} elseif ($url) {
			$output[] = '<a href="' . esc_url_raw($url) . '" target="_blank" rel="noopener">' . $social . '</a>';
		}
	}

	foreach ($seo_data['other_social_urls'] as $social => $url) {
		$icon = '';

		if (stripos($url, 'whatsapp') !== false) {
			$icon = '<i class="fa-brands fa-whatsapp"></i>';
		} elseif (stripos($url, 'linkedin') !== false) {
			$icon = '<i class="fa-brands fa-linkedin-in"></i>';
		} elseif (stripos($url, 'instagram') !== false) {
			$icon = '<i class="fa-brands fa-instagram"></i>';
		} elseif (stripos($url, 'youtube') !== false) {
			$icon = '<i class="fa-brands fa-youtube"></i>';
		} elseif (stripos($url, 'tiktok') !== false) {
			$icon = '<i class="fa-brands fa-tiktok"></i>';
		}

		if (!empty($icon)) {
			$output[] = '<a href="' . esc_url_raw($url) . '" target="_blank" rel="noopener">' . $icon . '<span class="visually-hidden">' . $social . '</span></a>';
		} else {
			$output[] = '<a href="' . esc_url_raw($url) . '" target="_blank" rel="noopener">' . $social . '</a>';
		}
	}


	if (!empty($output))
		return '<div class="social-links">' . join(' ', $output) . '</div>';
}

add_shortcode('social_links', 'ea_social_links');
