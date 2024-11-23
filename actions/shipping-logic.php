<?php

add_filter('woocommerce_package_rates', 'remove_all_default_shipping_methods', 10, 2);
function remove_all_default_shipping_methods($rates, $package) {
    return []; // Removes all default shipping methods
}

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_fee_and_message');
function custom_shipping_fee_and_message() {
    if (is_cart() || is_checkout()) {
        global $woocommerce;

        // Count the total number of items in the cart
        $item_count = $woocommerce->cart->get_cart_contents_count();

        // Define shipping cost
        $shipping_cost = ($item_count < 3) ? 25 : 0;

        // Add custom shipping fee
        $woocommerce->cart->add_fee(__('Shipping', 'woocommerce'), $shipping_cost, true);
    }
}

add_action('woocommerce_cart_totals_after_shipping', 'add_free_shipping_message');
function add_free_shipping_message() {
    global $woocommerce;

    // Count the total number of items in the cart
    $item_count = $woocommerce->cart->get_cart_contents_count();

    // Display message only if less than 3 items are in the cart
    if ($item_count < 3) {
        $items_needed = 3 - $item_count; // Calculate items needed for free shipping
        echo '<p class="free-shipping-message">';
        echo sprintf(__('Add %d more blind(s) to get FREE shipping!', 'woocommerce'), $items_needed);
        echo '</p>';
    }
}
