<?php

function handle_cart_logic_for_target_product( $cart_item_key, $product_id ) {
    $target_product_id = 1056; // The product ID to check and manage

    // Get the current cart contents
    $cart = WC()->cart->get_cart();

    if ( $product_id == $target_product_id ) {
        // If the target product is added, remove all other products
        foreach ( $cart as $key => $item ) {
            if ( $item['product_id'] != $target_product_id ) {
                WC()->cart->remove_cart_item( $key );
            }
        }
    } else {
        // If any other product is added, remove the target product
        foreach ( $cart as $key => $item ) {
            if ( $item['product_id'] == $target_product_id ) {
                WC()->cart->remove_cart_item( $key );
            }
        }
    }
}

add_action( 'woocommerce_add_to_cart', 'handle_cart_logic_for_target_product', 10, 2 );
