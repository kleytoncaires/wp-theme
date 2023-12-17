<?php

// -----------
// DISABLE BLOCK LIBRARY
// -----------
function wpassist_remove_block_library_css()
{
    wp_dequeue_style('wp-block-library');
}
add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css');

// -----------
// LOAD DASHICONS
// -----------
// function ww_load_dashicons()
// {
//     wp_enqueue_style('dashicons');
// }
// add_action('wp_enqueue_scripts', 'ww_load_dashicons');

// -----------
// REMOVE DASHICONS
// -----------
function wpdocs_dequeue_dashicon()
{
    if (current_user_can('update_core')) {
        return;
    }
    wp_deregister_style('dashicons');
}
add_action('wp_enqueue_scripts', 'wpdocs_dequeue_dashicon');

// -----------
// REMOVE JQUERY MIGRATE
// -----------
function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];

        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}

add_action('wp_default_scripts', 'remove_jquery_migrate');

// -----------
// REPLACE/REMOVE JQUERY VERSION
// -----------
function replace_core_jquery_version()
{
    wp_deregister_script('jquery');
    // Change the URL if you want to load a local copy of jQuery from your own server.
    // wp_register_script('jquery', "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js", array(), '3.6.0');
}
add_action('wp_enqueue_scripts', 'replace_core_jquery_version');

// -----------
// REMOVE WP EMOJI
// -----------
function disable_wp_emojicons()
{
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'disable_wp_emojicons');

function disable_emojicons_tinymce($plugins)
{
    return is_array($plugins) ? array_diff($plugins, array('wpemoji')) : array();
}

// -----------
// ENQUEUE SCRIPTS
// -----------
function inc_scripts()
{
    wp_enqueue_script('jquery', "https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js", array(), '3.7.0');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js', array(), '5.3.1', false);
    wp_enqueue_script('jQueryMask', 'https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js', array(), '1.14.16', false);
    wp_enqueue_script('fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0.13/dist/fancybox/fancybox.umd.min.js', array(), '5.0.14', false);
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10.1.0/swiper-bundle.min.js', array(), '10.1.0', false);


    // wp_enqueue_script('jquery', get_template_directory_uri() . '/node_modules/jquery/dist/jquery.min.js');
    // wp_enqueue_script('bootstrap', get_template_directory_uri() . '/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js');
    // wp_enqueue_script('jQueryMask', get_template_directory_uri() . '/node_modules/jquery-mask-plugin/dist/jquery.mask.min.js');
    // wp_enqueue_script('fancybox', get_template_directory_uri() . '/node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js');
    // wp_enqueue_script('swiper', get_template_directory_uri() . '/node_modules/swiper/swiper-bundle.min.js');
    wp_enqueue_script('custom', get_template_directory_uri() . '/scripts.js');
}
add_action('wp_enqueue_scripts', 'inc_scripts');
