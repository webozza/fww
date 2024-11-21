<?php

function remove_all_products_if_specific_product_added( $cart_item_key, $product_id ) {
    $target_product_id = 1056; // The product ID to check

    if ( $product_id == $target_product_id ) {
        // Get the current cart contents
        $cart = WC()->cart->get_cart();

        // Loop through the cart and remove all other items
        foreach ( $cart as $key => $item ) {
            if ( $key !== $cart_item_key ) {
                WC()->cart->remove_cart_item( $key );
            }
        }
    }
}
add_action( 'woocommerce_add_to_cart', 'remove_all_products_if_specific_product_added', 10, 2 );
