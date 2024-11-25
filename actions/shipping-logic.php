<?php

add_filter('woocommerce_package_rates', 'remove_all_default_shipping_methods', 10, 2);
function remove_all_default_shipping_methods($rates, $package) {
    // Check if the cart contains product with ID 1056
    if (cart_contains_product(1056)) {
        return []; // No shipping methods if product ID 1056 is in the cart
    }
    return $rates; // Return default rates otherwise
}

add_action('woocommerce_cart_calculate_fees', 'custom_shipping_fee_and_message');
function custom_shipping_fee_and_message() {
    if (is_cart() || is_checkout()) {
        global $woocommerce;

        // Check if the cart contains product with ID 1056
        if (cart_contains_product(1056)) {
            return; // Skip custom shipping if product ID 1056 is in the cart
        }

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

    // Check if the cart contains product with ID 1056
    if (cart_contains_product(1056)) {
        return; // Skip the free shipping message if product ID 1056 is in the cart
    }

    // Count the total number of items in the cart
    $item_count = $woocommerce->cart->get_cart_contents_count();

    // Display message only if less than 3 items are in the cart
    if ($item_count < 3) {
        $items_needed = 3 - $item_count; // Calculate items needed for free shipping
        $blind_label = ($items_needed > 1) ? 'blinds' : 'blind'; // Use singular or plural based on the count
        echo '<p class="free-shipping-message">';
        echo sprintf(__('Add %d more %s to get FREE shipping!', 'woocommerce'), $items_needed, $blind_label);
        echo '</p>';
    }
}

/**
 * Helper function to check if the cart contains a specific product ID.
 *
 * @param int $product_id Product ID to check for.
 * @return bool True if the product is in the cart, false otherwise.
 */
function cart_contains_product($product_id) {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            return true;
        }
    }
    return false;
}
