<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package mybag
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php mybag_html_tag_schema(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="st-container" class="st-container">
	<div class="st-pusher">
	 <div class="st-pusher-after"></div>  
	  <div class="st-content"> 
		<div id="page" class="hfeed site wrapper">
			<?php
			do_action( 'mybag_before_header' ); ?>
			<!-- ============================================================= HEADER ============================================================= -->
			<header <?php mybag_header_class();?>>
				<?php
					ob_start(); 
					/**
					 * @hooked mybag_mini_cart - 10
					 * @hooked mybag_skip_links - 10
					 * @hooked mybag_navbar - 20
					 */
					do_action( 'mybag_header' );
				?>
			</header><!-- /.site-header -->
			<!-- ============================================================= HEADER : END ============================================================= -->

			<?php
			/**
			 * @hooked mybag_hook_jumbotron - 5
			 * @hooked mybag_archive_header - 10
			 */
			do_action( 'mybag_before_content' ); ?>

			<div id="content" class="<?php echo esc_attr( apply_filters( 'mybag_site_content_classes', 'site-content' ) ); ?>" tabindex="-1">

				<?php

				do_action( 'mybag_content_top' ); ?>
