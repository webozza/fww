<?php
/**
 * MyBag Child
 *
 * @package mybag-child 
 */

/**
 * Include all your custom code here
 */



// Enqueue the CSS and JS files
function enqueue_zip_code_checker_assets() {
    if (is_product()) {
        wp_enqueue_style('zip-code-checker-css', get_stylesheet_directory_uri() . '/css/zip_code_checker.css');
        wp_enqueue_script('zip-code-checker-js', get_stylesheet_directory_uri() . '/js/zip_code_checker.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_zip_code_checker_assets');

// Include the ZIP code checker template
function include_zip_code_checker() {
    if (is_product()) {
        include get_stylesheet_directory() . '/woocommerce/zip_code_checker.php';
        display_zip_code_checker();
    }
}
add_action('woocommerce_before_single_product_summary', 'include_zip_code_checker', 20);
