<?php
$unique_id = uniqid();
$video_link = $args['video_link'];
$thumbnail = get_video_thumbnail($video_link);
// $imagem_capa = get_field('imagem_capa') ?: get_field('imagem_capa', 'option');
$is_youtube = false;
$is_vimeo = false;

$youtube_video_id = get_youtube_video_id($video_link);
$vimeo_video_id = get_vimeo_video_id($video_link);

if ($youtube_video_id) {
    $is_youtube = true;
    $video_id = $youtube_video_id;
} elseif ($vimeo_video_id) {
    $is_vimeo = true;
    $video_id = $vimeo_video_id;
}

if ($imagem_capa) {
    $background_image = $imagem_capa['url'];
} else {
    $background_image = $thumbnail;
}
?>

<div id="video-container<?php echo $unique_id; ?>" class="video-thumbnail" style="background-image: url('<?php echo $background_image; ?>');">
    <button id="video-button<?php echo $unique_id; ?>" class="video-play color-white">
        <i class="icon-circle-play"></i>
    </button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var playButton = document.getElementById("video-button<?php echo $unique_id; ?>");
        var videoContainer = document.getElementById("video-container<?php echo $unique_id; ?>");
        var isYoutube = <?php echo json_encode($is_youtube); ?>;
        var isVimeo = <?php echo json_encode($is_vimeo); ?>;
        var videoId = "<?php echo $video_id; ?>";

        playButton.addEventListener("click", function() {
            var videoWrapper = document.createElement("div");
            videoWrapper.classList.add("ratio", "ratio-16x9", "background-white");

            var iframe = document.createElement("iframe");

            if (isYoutube) {
                iframe.setAttribute("src", "https://www.youtube.com/embed/" + videoId + "?autoplay=1");
            } else if (isVimeo) {
                iframe.setAttribute("src", "https://player.vimeo.com/video/" + videoId + "?autoplay=1");
            }

            iframe.setAttribute("allowfullscreen", "");
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allow", "autoplay");

            videoWrapper.appendChild(iframe);

            videoContainer.innerHTML = "";
            videoContainer.appendChild(videoWrapper);
        });
    });
</script>