<?php
    $logo = get_stylesheet_directory_uri() . '/assets/logo_blk_hires.png';
?>

<div class="divider"></div>

<div class="video_section">
    <div class="video_container">
        <h2>Inside or Outside Mount</h2>
        <video poster="/wp-content/uploads/2024/10/video_placeholder.png" playsinline="playsinline" preload="auto" style="width: 100%; height: 100%;" class="vjs-tech" id="player_html5_api" src="/wp-content/uploads/2024/11/Faux-Wood-Blinds-Deciding-on-Inside-or-Outside-Mount-Blinds.mp4">
            <source type="video/mp4" src="/wp-content/uploads/2024/11/Faux-Wood-Blinds-Deciding-on-Inside-or-Outside-Mount-Blinds.mp4" label="">
        </video>
    </div>
    <div class="video_container">
        <h2>Measure Inside Mount</h2>
        <video poster="/wp-content/uploads/2024/10/video_placeholder.png" playsinline="playsinline" preload="auto" style="width: 100%; height: 100%;" class="vjs-tech" id="player_html5_api" src="/wp-content/uploads/2024/11/Faux-Wood-Warehouse_-Measuring-for-an-Inside-Mount-Blind.mp4">
            <source type="video/mp4" src="/wp-content/uploads/2024/11/Faux-Wood-Warehouse_-Measuring-for-an-Inside-Mount-Blind.mp4" label="">
        </video>
    </div>
    <div class="video_container">
        <h2>Measure Outside Mount</h2>
        <video poster="/wp-content/uploads/2024/10/video_placeholder.png" playsinline="playsinline" preload="auto" style="width: 100%; height: 100%;" class="vjs-tech" id="player_html5_api" src="/wp-content/uploads/2024/11/Faux-Wood-Warehouse_-Measuring-for-an-Outside-Mount-Blind.mp4">
            <source type="video/mp4" src="/wp-content/uploads/2024/11/Faux-Wood-Warehouse_-Measuring-for-an-Outside-Mount-Blind.mp4" label="">
        </video>
    </div>
</div>

<!-- Hidden video popups -->
<?php
function add_video_popup_to_footer() {
    ?>
    <div class="video_popup" style="display: none;">
        <!-- Embed your video here -->
        <div class="popup_content">
            <iframe width="560" height="515" src="https://www.youtube.com/embed/LA1XUZTtcNc?si=E-R88UUtcKdYNsSW" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <button class="close_button"></button>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'add_video_popup_to_footer');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.video_box').click(function(){
            var popup = $('.video_popup');
            console.log('dfdfdf')
            popup.show();
        });

        $('.close_button').click(function(){
            $(this).closest('.video_popup').hide();
        });
    });
</script>
