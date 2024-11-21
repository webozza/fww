<?php
// Remove default coupon placement
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

// Add coupon form after the order total
add_action('woocommerce_review_order_after_order_total', 'woocommerce_checkout_coupon_form');

// Enqueue the custom JavaScript
function custom_coupon_ajax_script() {
    if (is_checkout()) {
        wp_enqueue_script(
            'custom-coupon-ajax',
            get_stylesheet_directory_uri() . '/js/custom-coupon-ajax.js',
            array('jquery'),
            null,
            true
        );
        wp_localize_script('custom-coupon-ajax', 'customCouponAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('apply_coupon_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'custom_coupon_ajax_script');

// AJAX handler for applying coupon
function apply_coupon_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'apply_coupon_nonce')) {
        wp_send_json_error(array('message' => 'Invalid request.'));
        wp_die();
    }

    if (isset($_POST['coupon_code'])) {
        $coupon_code = sanitize_text_field($_POST['coupon_code']);
        $result = WC()->cart->apply_coupon($coupon_code);

        if ($result) {
            wc_clear_notices();
            wp_send_json_success();
        } else {
            $error_messages = wc_get_notices('error');
            wc_clear_notices();
            wp_send_json_error(array('message' => implode(', ', $error_messages)));
        }
    }
    wp_die();
}
add_action('wp_ajax_apply_coupon', 'apply_coupon_ajax');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon_ajax');
