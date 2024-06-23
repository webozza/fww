<?php
/**
 * MyBag Child
 *
 * @package mybag-child 
 */

/**
 * Include all your custom code here
 */

//====================================//
//  >>   Enqueue the CSS and JS files
//====================================//

function enqueue_zip_code_checker_assets() {
    wp_enqueue_script('global-js', get_stylesheet_directory_uri() . '/js/global.js', array(), null, true);

    if (is_product() || is_cart()) {
        wp_enqueue_style('zip-code-checker-css', get_stylesheet_directory_uri() . '/css/zip_code_checker.css');
        wp_enqueue_script('zip-code-checker-js', get_stylesheet_directory_uri() . '/js/zip_code_checker.js', array(), null, true);

        wp_enqueue_script('product-attributes-js', get_stylesheet_directory_uri() . '/js/product_attributes.js', array('jquery'), null, true);
        wp_localize_script('product-attributes-js', 'customPriceUpdateParams', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('custom_price_update_nonce')
        ));
        
    }


    wp_enqueue_script('cart-script', get_stylesheet_directory_uri() . '/js/cart.js', array('jquery'), null, true);

    wp_localize_script('cart-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('update_cart_variant_nonce'),
        'product_id' => get_the_ID() // Ensure this is the correct product ID
    ));




}
add_action('wp_enqueue_scripts', 'enqueue_zip_code_checker_assets');

//====================================//
//  >>  Include the ZIP code checker template
//====================================//

function include_zip_code_checker() {
    if (is_product()) {
        include get_stylesheet_directory() . '/woocommerce/zip_code_checker.php';
        display_zip_code_checker();
    }
}
add_action('woocommerce_before_single_product_summary', 'include_zip_code_checker', 20);

//====================================//
//  >>  Include the Prodcut attributes template
//====================================//

function include_product_attributes() {
    if (is_product()) {
        include get_stylesheet_directory() . '/woocommerce/product_attributes.php';
    }
}
add_action('woocommerce_before_add_to_cart_form', 'include_product_attributes', 20);

//====================================//
//  >>  Include the bottom video section template
//====================================//

function include_under_product_video_section() {
    if (is_product()) {
        include get_stylesheet_directory() . '/woocommerce/under_product_video_section.php';
    }
}
add_action('woocommerce_after_single_product_summary', 'include_under_product_video_section', 2);

// ====================================//
//  >>  Update the produt Price when Attribure are selected
// ====================================//

add_action('wp_ajax_custom_update_price', 'custom_update_price');
add_action('wp_ajax_nopriv_custom_update_price', 'custom_update_price');

function custom_update_price() {
    // Verify the nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'custom_price_update_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce.'));
    }

    // Parse the form data
    parse_str($_POST['data'], $form_data);

    // Ensure variation ID is correctly set
    $product_id = $form_data['product_id'];
    $variation_id = isset($form_data['variation_id']) ? $form_data['variation_id'] : null;

    // Try to get the variation ID based on selected attributes if not set
    if (!$variation_id) {
        $product = wc_get_product($product_id);
        if ($product->is_type('variable')) {
            $variation_id = find_matching_variation($product, $form_data);
        }
    }

    $product = wc_get_product($variation_id ? $variation_id : $product_id);

    if ($product) {
        $price = $product->get_price();
        $price_html = wc_price($price);

        wp_send_json_success(array(
            'price' => $price,
            'price_html' => $price_html
        ));
    } else {
        wp_send_json_error(array('message' => 'Product not found.'));
    }

    wp_die();
}

function find_matching_variation($product, $attributes) {
    foreach ($product->get_available_variations() as $variation) {
        $match = true;
        foreach ($variation['attributes'] as $attr => $value) {
            if (isset($attributes[$attr]) && $attributes[$attr] !== $value) {
                $match = false;
                break;
            }
        }
        if ($match) {
            return $variation['variation_id'];
        }
    }
    return null;
}






// ====================================//
//  >>  Update Cart On Instalation
// ====================================//



add_action('wp_ajax_update_cart_variant', 'update_cart_variant');
add_action('wp_ajax_nopriv_update_cart_variant', 'update_cart_variant');

function update_cart_variant() {
    check_ajax_referer('update_cart_variant_nonce', 'nonce');

    $product_id = intval($_POST['product_id']);
    $variant_value = sanitize_text_field($_POST['variant_value']);

    if (!$product_id || !$variant_value) {
        wp_send_json_error(array('message' => 'Invalid product ID or variant value'));
    }

    // Function to get product variants
    function get_product_variants($product_id) {
        $product = wc_get_product($product_id);

        // Check if the product is a variable product
        if ($product->is_type('variable')) {
            $available_variations = $product->get_available_variations();
            $variants = [];

            foreach ($available_variations as $variation) {
                $variant_id = $variation['variation_id'];
                $attributes = $variation['attributes'];
                $variants[$variant_id] = $attributes;
            }

            return $variants;
        } else {
            return false;
        }
    }

    // Get the product variants
    $variants = get_product_variants($product_id);

    if ($variants) {
        $new_variant_id = null;
        foreach ($variants as $variant_id => $attributes) {
            if ($attributes['attribute_pa_color-swatches'] === 'subdued-steel' && $attributes['attribute_installation'] === $variant_value) {
                $new_variant_id = $variant_id;
                break;
            }
        }

        if ($new_variant_id) {
            // Update the cart with the new variant
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                if ($cart_item['product_id'] == $product_id) {
                    WC()->cart->remove_cart_item($cart_item_key);
                    WC()->cart->add_to_cart($product_id, 1, $new_variant_id);
                    break;
                }
            }

            // Recalculate totals and send response
            WC()->cart->calculate_totals();
            $cart_total = WC()->cart->get_cart_total();
            $installation_value = $variant_value;

            wp_send_json_success(array(
                'new_total' => $cart_total,
                'installation_value' => $installation_value
            ));
        } else {
            wp_send_json_error(array('message' => 'Variant not found'));
        }
    } else {
        wp_send_json_error(array('message' => 'No variants available for this product'));
    }

    wp_die();
}





// ====================================//
//  >>  Update Product Price On Height And Width
// ====================================//

function update_product_price() {
    if (isset($_POST['product_id']) && isset($_POST['new_price'])) {
        $product_id = intval($_POST['product_id']);
        $new_price = floatval($_POST['new_price']);

        if ($new_price && $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                // Update the product price
                $product->set_price($new_price);
                $product->set_regular_price($new_price);
                $product->save();

                // Save the custom price in post meta
                update_post_meta($product_id, '_custom_price', $new_price);

                wp_send_json_success(array('new_price' => $new_price));
            }
        }
    }
    wp_send_json_error('Failed to update product price');
}
add_action('wp_ajax_update_product_price', 'update_product_price');
add_action('wp_ajax_nopriv_update_product_price', 'update_product_price');




// ====================================//
//  >>  Cart Action
// ====================================//



function update_cart_item_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    // Iterate through each cart item
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        // Ensure the product ID is present
        if (isset($cart_item['product_id'])) {
            $product_id = $cart_item['product_id'];

            // Fetch the updated price from post meta or calculate it based on custom logic
            // For this example, we're assuming the updated price is stored in post meta
            $updated_price = get_post_meta($product_id, '_custom_price', true);

            if ($updated_price) {
                // Update the price in the cart
                $cart_item['data']->set_price($updated_price);
            }
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'update_cart_item_price', 10, 1);
