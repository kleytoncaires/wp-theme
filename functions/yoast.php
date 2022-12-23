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

		// FONT AWESOME
		'facebook'         => array(
			'key'         => 'facebook_site',
			'icon'         => '<i class="fa-brands fa-facebook-f"></i>',
		),
		'twitter'         => array(
			'key'         => 'twitter_site',
			'prepend'     => 'https://twitter.com/',
			'icon'         => '<i class="fa-brands fa-twitter"></i>',
		),
		'linkedin'         => array(
			'key'         => 'linkedin_url',
			'icon'         => '<i class="fa-brands fa-linkedin-in"></i>',
		),
		'youtube'         => array(
			'key'         => 'youtube_url',
			'icon'         => '<i class="fa-brands fa-youtube"></i>',
		),
		'instagram'     => array(
			'key'         => 'instagram_url',
			'icon'         => '<i class="fa-brands fa-instagram"></i>',
		),
		'pinterest'     => array(
			'key'         => 'pinterest_url',
			'icon'         => '<i class="fa-brands fa-pinterest-p"></i>',
		),
	);

	$options = apply_filters('ea_social_link_options', $options);

	$output = array();

	$seo_data = get_option('wpseo_social');

	foreach ($options as $social => $settings) {

		$url = !empty($seo_data[$settings['key']]) ? $seo_data[$settings['key']] : false;
		if (!empty($url) && !empty($settings['prepend']))
			$url = $settings['prepend'] . $url;

		if ($url && !empty($settings['icon']))
			$output[] = '<a href="' . esc_url_raw($url) . '" target="_blank" rel="noopener">' . $settings['icon'] . '<span class="sr-only sr-only-focusable">' . $social . '</span></a>';
	}

	// foreach ($seo_data['other_social_urls'] as $link) {

	// 	if (stripos($link, "youtube") !== false) {
	// 		$output[] = '<a href="' . esc_url_raw($link) . '" target="_blank" rel="noopener"><i class="fa-brands fa-youtube"></i><span class="sr-only sr-only-focusable">Youtube</span></a>';
	// 	}

	// 	if (stripos($link, "instagram") !== false) {
	// 		$output[] = '<a href="' . esc_url_raw($link) . '" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i><span class="sr-only sr-only-focusable">Instagram</span></a>';
	// 	}

	// 	if (stripos($link, "linkedin") !== false) {
	// 		$output[] = '<a href="' . esc_url_raw($link) . '" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin-in"></i><span class="sr-only sr-only-focusable">Linkedin</span></a>';
	// 	}
	// }

	if (!empty($output))
		return '<div class="social-links">' . join(' ', $output) . '</div>';
	//return '<span class="footer-text">' . __('Siga nas redes', 'YSSY') . '</span><div class="social-links">' . join(' ', $output) . '</div>';
}
add_shortcode('social_links', 'ea_social_links');
