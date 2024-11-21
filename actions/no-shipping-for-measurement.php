<?php

function hide_shipping_for_specific_product() {
    $target_product_id = 1056;

    // Check if the product is in the cart
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $target_product_id) {
            // Remove shipping methods from cart and checkout
            add_filter('woocommerce_cart_shipping_method_full_label', '__return_empty_string');
            add_filter('woocommerce_cart_no_shipping_available_html', '__return_empty_string');
            add_filter('woocommerce_no_shipping_available_html', '__return_empty_string');
            add_filter('woocommerce_shipping_methods', '__return_empty_array');
            return;
        }
    }
}
add_action('woocommerce_before_cart', 'hide_shipping_for_specific_product');
add_action('woocommerce_before_checkout_form', 'hide_shipping_for_specific_product');
