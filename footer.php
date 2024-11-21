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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>