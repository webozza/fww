<?php

add_action('woocommerce_cart_calculate_fees', 'add_handling_fee');
function add_handling_fee() {
    // Check if WooCommerce is properly initialized
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Add a flat handling fee of $5.95
    $handling_fee = 5.95;

    WC()->cart->add_fee(__('Handling Fee', 'woocommerce'), $handling_fee, true, 'standard');
}
