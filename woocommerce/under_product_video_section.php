<?php
    $logo = get_stylesheet_directory_uri() . '/assets/logo_blk_hires.png';
?>

<div class="divider"></div>

<div class="video_section">
    <div class="video_container">
        <h2>INSIDE OR OUTSIDE MOUNT</h2>
        <div class="video_box">
            <div class="icon"><span class="dashicons dashicons-controls-play"></span></div>
            <div class='img'><img src="https://fauxwoodwarehouse.com/wp-content/uploads/2024/10/video_placeholder.png" alt=""></div>
        </div>
    </div>
    <div class="video_container">
        <h2>WHAT IS A RETURN</h2>
        <div class="video_box">
            <div class="icon"><span class="dashicons dashicons-controls-play"></span></div>
            <div class='img'><img src="https://fauxwoodwarehouse.com/wp-content/uploads/2024/10/video_placeholder.png" alt=""></div>
        </div>
    </div>
    <div class="video_container">
        <h2>WE MEASURE AND INSTALL</h2>
        <div class="video_box">
            <div class="icon"><span class="dashicons dashicons-controls-play"></span></div>
            <div class='img'><img src="https://fauxwoodwarehouse.com/wp-content/uploads/2024/10/video_placeholder.png" alt=""></div>
        </div>
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
