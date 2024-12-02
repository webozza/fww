<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package mybag
 */
?>
			</div><!-- #content -->

			<?php 
			do_action( 'mybag_before_footer' ); ?>
			
			<!-- ========================================= FOOTER ========================================= -->
					
					<?php
					/**
					 * @hooked mybag_get_footer_template - 10
					 */
					do_action( 'mybag_footer' ); ?>

				
			<?php 
			do_action( 'mybag_after_footer' ); ?>
			</div><!--st-content-->
		</div><!-- #page -->
	</div><!-- #st-pusher -->

	<div class="st-slider">
		<div class="offcanvas-right-content">
			<div class="offcanvas-right-inner-content">
				<?php
				/**
				 * @hooked mybag_mini_cart_template - 10
				 */
				do_action( 'mybag_slide_content' ); ?>
			</div><!-- .nano-content -->
		</div><!-- .nano -->
	</div><!-- #st-slider -->

</div><!-- #st-container -->

<?php wp_footer(); ?>

<div class="video_popup">
	<div class="popup_content">
		<video controls poster="/wp-content/uploads/2024/10/video_placeholder.png" playsinline preload="auto" style="width: 100%; height: 100%;" class="vjs-tech" id="player_outside_mount">
			<source type="video/mp4" src="/wp-content/uploads/2024/11/Faux-Wood-Warehouse_-Measuring-for-an-Outside-Mount-Blind.mp4">
			Your browser does not support the video tag.
		</video>
		<button class="close_button"></button>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>