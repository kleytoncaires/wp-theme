<? // clean cache 
?>
?version=1.0<?php echo get_cache_prevent_string(); ?>

<?php
define("DEBUGGING", true); // or false in production enviroment
function get_cache_prevent_string($always = false)
{
    return (DEBUGGING || $always) ? date('_Y-m-d_H:i:s') : "";
}
?>

<? // call sidebar 
?>
<?php if (is_active_sidebar('sidebar')) : ?>
    <?php dynamic_sidebar('sidebar'); ?>
<?php endif; ?>

<? // yoast social 
?>
<?php echo do_shortcode('[social_links]'); ?>

<? // language call 
?>
<?php _e('Text', 'DOMAIN'); ?>

<? // ratio background 
?>
<?php $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
<div class="ratio ratio-4x3">
    <div class="" style="background-image: url(<?php echo $url; ?>);"></div>
</div>

<? // foreach query 
?>
<?php
$args = array(
    'post_type'      => '', // CHANGE
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'post_date',
    'order'          => 'desc',
);

$posts_query = new WP_Query($args);
?>

<?php if ($posts_query->have_posts()) : ?>
    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
        <?php the_title(); ?>
        <?php the_content(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<? // page query 
?>

<?php
$args = array(
    'post_type'      => 'page',
    'pagename'       => '', // CHANGE
);

$posts_query = new WP_Query($args);
?>

<?php if ($posts_query->have_posts()) : ?>
    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
        <?php the_title(); ?>
        <?php the_content(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<? // contact form 7 estados brasileiros 
?>
[select Estado class:form-control first_as_label "Selecione o Estado*" "Acre" "Alagoas" "Amazonas" "Amapá" "Bahia" "Ceará" "Distrito Federal" "Espírito Santo" "Goiás" "Maranhão" "Mato Grosso" "Mato Grosso do Sul" "Minas Gerais" "Pará" "Paraíba" "Paraná" "Pernambuco" "Piauí" "Rio de Janeiro" "Rio Grande do Norte" "Rondônia" "Rio Grande do Sul" "Roraima" "Santa Catarina" "Sergipe" "São Paulo" "Tocantins"]

<? // Video with thumbnail
?>
<?php $yt_link = get_field('url_video'); ?>
<?php if ($yt_link) : ?>
    <a href="<?php echo $yt_link ?>" data-fancybox class="video-thumbnail">
        <?php
        $link = explode("?v=", $yt_link);
        $link = $link[1];
        $thumbnail = "https://img.youtube.com/vi/" . $link . "/maxresdefault.jpg";
        ?>
        <div class="video-play">
            <?php echo file_get_contents(get_template_directory() . '/assets/img/play.svg'); ?>
        </div>
        <img src="<?php echo $thumbnail; ?>" class="w-100">
    </a>
<?php endif; ?>


<? // Translate 
?>
<?php _e('Lorem Ipsum.', 'wp-starter'); ?>