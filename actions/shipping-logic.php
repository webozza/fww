<?php

add_filter('woocommerce_package_rates', 'remove_default_shipping_methods', 10, 2);
function remove_default_shipping_methods($rates, $package) {
    // Unset all shipping methods except the custom fee logic
    foreach ($rates as $rate_id => $rate) {
        if ($rate_id !== 'custom_shipping_method') {
            unset($rates[$rate_id]);
        }
    }
    return $rates;
}

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_with_message');
function custom_shipping_with_message() {
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

add_action('woocommerce_cart_totals_after_shipping', 'display_free_shipping_hint');
function display_free_shipping_hint() {
    global $woocommerce;

    // Count the total number of items in the cart
    $item_count = $woocommerce->cart->get_cart_contents_count();

    // If items are less than 3, show a dynamic message
    if ($item_count < 3) {
        $items_needed = 3 - $item_count; // Calculate items needed for free shipping
        echo '<p class="free-shipping-hint" style="margin: 5px 0 0; font-size: 12px; color: #666; line-height: 1.4;">';
        echo sprintf(__('%d more = FREE', 'woocommerce'), $items_needed);
        echo '</p>';
    }
}
