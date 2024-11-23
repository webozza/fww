<?php

add_action('woocommerce_cart_totals_before_shipping', 'display_free_shipping_message');
function display_free_shipping_message() {
    global $woocommerce;

    // Count the total number of items in the cart
    $item_count = $woocommerce->cart->get_cart_contents_count();

    // Check if there are less than 3 items and calculate items needed for free shipping
    if ($item_count < 3) {
        $items_needed = 3 - $item_count; // Calculate items needed for free shipping
        echo '<p class="free-shipping-message" style="color: #ff0000; font-size: 14px; margin-top: 5px;">';
        echo sprintf(__('Add %d more item%s to get FREE shipping!', 'woocommerce'), $items_needed, $items_needed > 1 ? 's' : '');
        echo '</p>';
    }
}

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_cost');
function custom_shipping_cost() {
    global $woocommerce;

    // Count the total number of items in the cart
    $item_count = $woocommerce->cart->get_cart_contents_count();

    // Define shipping cost
    $shipping_cost = ($item_count < 3) ? 25 : 0;

    // Add custom shipping fee
    $woocommerce->cart->add_fee(__('Shipping', 'woocommerce'), $shipping_cost, true);
}
