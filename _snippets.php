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
    'post_type'         => 'post-type',
    'post_status'       => 'publish',
    'posts_per_page'    =>  -1,
    'orderby'           => 'post_date',
    'order'             => 'desc',
);

$posts = query_posts($args);
?>

<?php if (have_posts()) : ?>
    <?php foreach ($posts as $post) : setup_postdata($post); ?>
        <?php the_title(); ?>
        <?php the_content(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php wp_reset_query(); ?>

<? // page query 
?>

<?php
$args = array(
    'post_type'      => 'page',
    'pagename'       => 'contato',
);

$posts = query_posts($args);
?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php the_title(); ?>
        <?php the_content(); ?>
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>

<?php wp_reset_query(); ?>

<? // mod
?>
<div class="row">
    <?php $cont = 0; ?>
    <?php foreach ($posts as $post) : setup_postdata($post); ?>
        <?php if ($cont == 2) {
            echo "<div class='w-100'></div>";
            $cont = 0;
        } ?>
        <div class="col-12">

        </div>
        <?php $cont++; ?>
    <?php endforeach; ?>
</div>


<? // contact form 7 estados brasileiros 
?>
[select Estado class:form-control first_as_label "Selecione o Estado*" "Acre" "Alagoas" "Amazonas" "Amap??" "Bahia" "Cear??" "Distrito Federal" "Esp??rito Santo" "Goi??s" "Maranh??o" "Mato Grosso" "Mato Grosso do Sul" "Minas Gerais" "Par??" "Para??ba" "Paran??" "Pernambuco" "Piau??" "Rio de Janeiro" "Rio Grande do Norte" "Rond??nia" "Rio Grande do Sul" "Roraima" "Santa Catarina" "Sergipe" "S??o Paulo" "Tocantins"]

<? // Video with thumbnail
?>
<?php $link = get_field('url_video'); ?>
<?php if ($link) : ?>
    <a href="<?php echo $link ?>" data-fancybox class="institucional-video">
        <?php
        $link = explode("?v=", $link);
        $link = $link[1];
        $thumbnail = "https://img.youtube.com/vi/" . $link . "/maxresdefault.jpg";
        ?>
        <div class="institucional-play">
            <?php echo file_get_contents(get_template_directory() . '/assets/img/play.svg'); ?>
        </div>
        <img src="<?php echo $thumbnail; ?>" class="w-100">
    </a>
<?php endif; ?>

<? // Translate 
?>
<?php _e('Lorem Ipsum.', 'wp-starter'); ?>