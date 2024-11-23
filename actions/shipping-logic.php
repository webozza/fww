<?php

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_cost_with_message');
function custom_shipping_cost_with_message() {
    if (is_cart() || is_checkout()) {
        global $woocommerce;

        // Count the total number of items in the cart
        $item_count = $woocommerce->cart->get_cart_contents_count();

        // Define shipping cost and dynamic message
        if ($item_count < 3) {
            $shipping_cost = 25; // Shipping cost for less than 3 items
            $items_needed = 3 - $item_count; // Items needed for free shipping
            $message = sprintf(__('%d more = FREE', 'woocommerce'), $items_needed);
        } else {
            $shipping_cost = 0; // Free shipping for 3 or more items
            $message = __('Free Shipping', 'woocommerce');
        }

        // Add custom shipping fee with the message
        $label = $shipping_cost > 0 ? sprintf(__('Shipping (%s)', 'woocommerce'), $message) : __('Shipping', 'woocommerce');
        $woocommerce->cart->add_fee($label, $shipping_cost, true);
    }
}
