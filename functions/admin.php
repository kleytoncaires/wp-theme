<?php

// ----------- 
// REMOVE DASHBOARD WIDGETS 
// -----------
remove_action('welcome_panel', 'wp_welcome_panel'); //remove WordPress Welcome Panel
add_action('admin_init', 'remove_dashboard_widgets');
function remove_dashboard_widgets()
{
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); // Quick Draft
    remove_meta_box('dashboard_primary', 'dashboard', 'core'); // WordPress News
    remove_meta_box('dashboard_secondary', 'dashboard', 'core'); // WordPress News
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core'); // Drafts Widget
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'core'); // Incoming Links Widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'core'); // Plugins Widget

    if (!is_admin()) {
        wp_deregister_script('jquery');                                     // De-Register jQuery
        wp_register_script('jquery', '', '', '', true);                     // Register as 'empty', because we manually insert our script in header.php
    }
}

// ----------- 
// MANAGE MENU 
// -----------
function remove_menus()
{

    //remove_menu_page( 'index.php' );                  //Dashboard
    //remove_menu_page( 'jetpack' );                    //Jetpack* 
    //remove_menu_page( 'upload.php' );                 //Media
    //remove_menu_page( 'edit.php?post_type=page' );    //Pages
    //remove_menu_page( 'themes.php' );                 //Appearance
    //remove_menu_page( 'plugins.php' );                //Plugins
    //remove_menu_page( 'users.php' );                  //Users
    //remove_menu_page( 'tools.php' );                  //Tools
    //remove_menu_page( 'options-general.php' );        //Settings
    //remove_menu_page('edit.php');                     //Posts
    remove_menu_page('edit-comments.php');          //Comments

}
add_action('admin_menu', 'remove_menus');

// ----------- 
// INCLUDE NAVIGATION MENUS
// -----------
function register_my_menus()
{
    register_nav_menus(
        array(
            'header-nav' => __('Menu Principal'),
            // 'footer-nav' => __('Menu do Rodapé'),
        )
    );
}
add_action('init', 'register_my_menus');

// ----------- 
// ADD CLASSES IN MENU ITEMS
// -----------
function add_menu_link_class($atts, $item, $args)
{
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

function add_menu_list_item_class($classes, $item, $args)
{
    if (property_exists($args, 'list_item_class')) {
        $classes[] = $args->list_item_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_menu_list_item_class', 1, 3);

// ----------- 
// REGISTER WIDGETS
// -----------
// function my_widget()
// {
//     register_sidebar(array(
//         'name' => __('Sidebar', 'yourtheme'),
//         'id' => 'sidebar',
//         'description' => __('This is description', 'yourtheme'),
//         'before_widget' => '<aside>',
//         'after_widget' => '</aside>',
//         'before_title' => '<h3>',
//         'after_title' => '</h3>',
//     ));
// }
// add_action('widgets_init', 'my_widget');

// ----------- 
// DISPLAY POST EXCERPT BY DEFAULT
// -----------
function wpse_edit_post_show_excerpt()
{
    $user = wp_get_current_user();
    $unchecked = get_user_meta($user->ID, 'metaboxhidden_post', true);
    if (!empty($unchecked)) {
        $key = array_search('postexcerpt', $unchecked);
        if (FALSE !== $key) {
            array_splice($unchecked, $key, 1);
            update_user_meta($user->ID, 'metaboxhidden_post', $unchecked);
        }
    }
}
add_action('admin_init', 'wpse_edit_post_show_excerpt', 10);

function show_excerpt_meta_box($hidden, $screen)
{
    if ('post' == $screen->base) {
        foreach ($hidden as $key => $value) {
            if ('postexcerpt' == $value) {
                unset($hidden[$key]);
                break;
            }
        }
    }
    return $hidden;
}
add_filter('default_hidden_meta_boxes', 'show_excerpt_meta_box', 10, 2);

// ----------- 
// DISABLE SELF PINGBACKS
// -----------
function wpsites_disable_self_pingbacks(&$links)
{
    foreach ($links as $l => $link)
        if (0 === strpos($link, get_option('home')))
            unset($links[$l]);
}

add_action('pre_ping', 'wpsites_disable_self_pingbacks');

// ----------- 
// DISABLE FEED
// -----------
function itsme_disable_feed()
{
    wp_die(__('No feed available, please visit the homepage!'));
}

add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);


// ----------- 
// CUSTOM ADMIN LOGO
// -----------
function my_login_logo()
{ ?>
    <style type="text/css">
        #login,
        .login {
            background-color: #fff;
        }

        #login h1 a,
        .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/wp-login.png);
            background-size: 320px 65px;
            background-repeat: no-repeat;
            height: 65px;
            padding-bottom: 30px;
            width: 320px;
        }

        .login #backtoblog a,
        .login #nav a {
            color: #000 !important;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo');
