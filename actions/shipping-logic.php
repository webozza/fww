<?php

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_cost_based_on_cart_items');
function custom_shipping_cost_based_on_cart_items() {
    // Ensure we are in the cart or checkout page
    if (is_cart() || is_checkout()) {
        global $woocommerce;

        // Count the total number of items in the cart
        $item_count = $woocommerce->cart->get_cart_contents_count();

        // Define shipping cost
        if ($item_count < 3) {
            $shipping_cost = 25; // Shipping cost for less than 3 items
        } else {
            $shipping_cost = 0; // Free shipping for 3 or more items
        }

        // Add custom shipping fee (label it as Shipping)
        $woocommerce->cart->add_fee(__('Shipping', 'woocommerce'), $shipping_cost, true);
    }
}
