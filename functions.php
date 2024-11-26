<?php
/**
 * MyBag Child
 *
 * @package mybag-child 
 */

/**
 * Include all your custom code here
 */

 if ( ! defined( '_S_VERSION' ) ) {
    // Replace this with your actual theme version
    define( '_S_VERSION', '1.1.50' );
}

//====================================//
//  >>   Enqueue the CSS and JS files
//====================================//

function enqueue_zip_code_checker_assets() {
    // Global CSS file with static version
    wp_enqueue_style('custom-css', get_stylesheet_directory_uri() . '/css/custom.css', array(), _S_VERSION);
    
    // Global JS file with static version
    wp_enqueue_script('global-js', get_stylesheet_directory_uri() . '/js/global.js', array(), _S_VERSION, true);

    if (is_checkout()) {
        wp_enqueue_script(
            'custom-coupon-ajax', // Handle for the script
            get_stylesheet_directory_uri() . '/js/custom-coupon-ajax.js', // Path to the script
            array('jquery'), // Dependencies (jQuery in this case)
            _S_VERSION, // Version (replace _S_VERSION with a specific version if not defined)
            true // Load in the footer
        );

        // Pass localized variables to the script
        wp_localize_script('custom-coupon-ajax', 'customCouponAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'), // WordPress AJAX URL
            'nonce'    => wp_create_nonce('apply_coupon_nonce') // Security nonce
        ));
    }
    
    // Conditional styles and scripts for product and cart pages
    if (is_product() || is_cart()) {
        wp_enqueue_style('zip-code-checker-css', get_stylesheet_directory_uri() . '/css/zip_code_checker.css', array(), _S_VERSION);
        wp_enqueue_script('zip-code-checker-js', get_stylesheet_directory_uri() . '/js/zip_code_checker.js', array(), _S_VERSION, true);

        wp_enqueue_script('product-attributes-js', get_stylesheet_directory_uri() . '/js/product_attributes.js', array('jquery'), _S_VERSION, true);
        wp_localize_script('product-attributes-js', 'customPriceUpdateParams', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('custom_price_update_nonce')
        ));
    } elseif (is_page('fitrite')) {
        wp_enqueue_style('zip-code-checker-css', get_stylesheet_directory_uri() . '/css/zip_code_checker.css', array(), _S_VERSION);
    }

    // Global cart script with static version
    wp_enqueue_script('cart-script', get_stylesheet_directory_uri() . '/js/cart.js', array('jquery'), _S_VERSION, true);

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
//  >>  ZipCode Shortcode for non product page
//====================================//

// Register shortcode for zip code checker
function zip_code_checker_shortcode() {
    ob_start(); // Start output buffering
    
    // Include the PHP file
    include plugin_dir_path( __FILE__ ) . 'woocommerce/zip_code_checker_non-product-page.php';
    display_zip_code_checker();
    return ob_get_clean(); // Return the buffered output
}
add_shortcode('zip_code_checker', 'zip_code_checker_shortcode');

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
//  >>  Update Product Price On Height And Width
// ====================================//

add_action('wp_ajax_add_custom_product_to_cart', 'add_custom_product_to_cart');
add_action('wp_ajax_nopriv_add_custom_product_to_cart', 'add_custom_product_to_cart');

function add_custom_product_to_cart() {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $custom_quantity = intval($_POST['custom_quantity']); // Ensure you get the value
    $custom_price = floatval($_POST['custom_price']);
    $measuring_fee =  floatval($_POST['measuring_fee']);
    $width = sanitize_text_field($_POST['width']);
    $height = sanitize_text_field($_POST['height']);
    $mount = sanitize_text_field($_POST['mount']);
    $window_name = sanitize_text_field($_POST['window_name']);
    $blind = sanitize_text_field($_POST['blind']);
    $color = sanitize_text_field($_POST['color']);

    WC()->cart->add_to_cart($product_id, $quantity, 0, array(), array(
        'custom_price' => $custom_price,
        'custom_quantity' => $custom_quantity,
        'width' => $width,
        'height' => $height,
        'mount' => $mount,
        'window_name' => $window_name,
        'blind' => $blind,
        'color' => $color,
    ));


    wp_die();
}

add_action('woocommerce_before_calculate_totals', 'update_custom_price_in_cart', 10, 1);
function update_custom_price_in_cart($cart_obj) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart_obj->get_cart() as $key => $value) {
        if (isset($value['custom_price'])) {
            $value['data']->set_price($value['custom_price']);
        }
    }
}

add_filter('woocommerce_add_cart_item_data', 'save_custom_data_to_cart', 10, 2);
function save_custom_data_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['color'])) {
        $cart_item_data['color'] = sanitize_text_field($_POST['color']);
    }
    if (isset($_POST['width'])) {
        $cart_item_data['width'] = sanitize_text_field($_POST['width']);
    }
    if (isset($_POST['height'])) {
        $cart_item_data['height'] = sanitize_text_field($_POST['height']);
    }
    if (isset($_POST['mount'])) {
        $cart_item_data['mount'] = sanitize_text_field($_POST['mount']);
    }
    if (isset($_POST['window_name'])) {
        $cart_item_data['window_name'] = sanitize_text_field($_POST['window_name']);
    }
    if (isset($_POST['blind'])) {
        $cart_item_data['blind'] = sanitize_text_field($_POST['blind']);
    }
    if (isset($_POST['custom_price'])) {
        $cart_item_data['custom_price'] = floatval($_POST['custom_price']);
        $cart_item_data['unique_key'] = md5(microtime().rand()); // Ensure unique key for each cart item
    }
    if (isset($_POST['custom_quantity'])) {
        $cart_item_data['custom_quantity'] = intval($_POST['custom_quantity']); // Capture custom quantity
    }
    return $cart_item_data;
}

add_filter('woocommerce_get_cart_item_from_session', 'get_cart_items_from_session', 10, 2);
function get_cart_items_from_session($cart_item, $values) {
    if (isset($values['color'])) {
        $cart_item['color'] = $values['color'];
    }
    if (isset($values['width'])) {
        $cart_item['width'] = $values['width'];
    }
    if (isset($values['height'])) {
        $cart_item['height'] = $values['height'];
    }
    if (isset($values['mount'])) {
        $cart_item['mount'] = $values['mount'];
    }
    if (isset($values['window_name'])) {
        $cart_item['window_name'] = $values['window_name'];
    }
    if (isset($values['blind'])) {
        $cart_item['blind'] = $values['blind'];
    }
    if (isset($values['custom_price'])) {
        $cart_item['custom_price'] = $values['custom_price'];
    }
    return $cart_item;
}

add_filter('woocommerce_get_item_data', 'display_custom_data_in_cart', 10, 2);
function display_custom_data_in_cart($item_data, $cart_item) {

    if ($cart_item['custom_quantity']) {
        $item_data[] = array(
            'name' => 'Number of Windows:',
            'value' => intval($cart_item['custom_quantity']) // Display the custom quantity
        );
    }

    if ($cart_item['color']) {
        $item_data[] = array(
            'name' => 'Color',
            'value' => sanitize_text_field($cart_item['color'])
        );
    }

    if ($cart_item['width']) {
        $item_data[] = array(
            'name' => 'Width',
            'value' => sanitize_text_field($cart_item['width'])
        );
    }

    if ($cart_item['height']) {
        $item_data[] = array(
            'name' => 'Height',
            'value' => sanitize_text_field($cart_item['height'])
        );
    }
    if ($cart_item['mount']) {
        $item_data[] = array(
            'name' => 'Mount',
            'value' => sanitize_text_field($cart_item['mount'])
        );
    }
    if ($cart_item['window_name']) {
        $item_data[] = array(
            'name' => 'Window Name',
            'value' => sanitize_text_field($cart_item['window_name'])
        );
    }
    // if ($cart_item['blind']) {
    //     $item_data[] = array(
    //         'name' => 'Returns',
    //         'value' => sanitize_text_field($cart_item['blind'])
    //     );
    // }
    if (($cart_item['custom_price'])) {
        $item_data[] = array(
            'name' => 'Custom Price',
            'value' => wc_price($cart_item['custom_price'])
        );
    }
    return $item_data;
}

add_filter('woocommerce_cart_item_price', 'display_custom_price_cart', 10, 3);
function display_custom_price_cart($price, $cart_item, $cart_item_key) {
    if (isset($cart_item['custom_price'])) {
        $price = wc_price($cart_item['custom_price']);
    }
    return $price;
}

// ====================================//
//  >>  Fedex Tracking System
// ====================================//

// Shortcode to display the tracking form
function fedex_tracking_form() {
    ob_start();
    include 'product_tracking.php';
    return ob_get_clean();
}
add_shortcode('fedex_tracking_form', 'fedex_tracking_form');

function get_fedex_access_token($client_id, $client_secret) {
    $url = 'https://apis.fedex.com/oauth/token';

    $data = [
        'grant_type' => 'client_credentials',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        die("cURL error: $error_msg");
    }

    curl_close($ch);

    $response_data = json_decode($response);

    if (isset($response_data->access_token)) {
        return $response_data->access_token;
    } else {
        echo "Error: HTTP code $http_code\n";
        echo "Response: $response\n";
        return false;
    }
}

function track_fedex_package() {
    $client_id = 'l706fce85ef82c468d8d27e8f5c461a8e2';
    $client_secret = '05fe661b99384c2c94187b34b19e9328';

    $tracking_number = sanitize_text_field($_POST['tracking_number']);
    $access_token = get_fedex_access_token($client_id, $client_secret);

    if (!$access_token) {
        echo 'Failed to obtain access token.';
        wp_die();
    }

    $url = 'https://apis.fedex.com/track/v1/trackingnumbers';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'Accept-Encoding: gzip',
    ];

    $body = [
        'trackingInfo' => [
            [
                'trackingNumberInfo' => [
                    'trackingNumber' => $tracking_number,
                ],
            ],
        ],
        'includeDetailedScans' => true,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        if ($http_code !== 200) {
            error_log('HTTP error (' . $http_code . '): ' . $response);
            echo 'HTTP error (' . $http_code . ')';
        } else {
            if (strpos(curl_getinfo($ch, CURLINFO_CONTENT_TYPE), 'gzip') !== false) {
                $response = gzdecode($response);
            }
            error_log('API Response: ' . print_r($response, true));
            wp_send_json_success(json_decode($response, true));
        }
    }


    curl_close($ch);

    wp_die();
}

add_action('wp_ajax_track_fedex_package', 'track_fedex_package');
add_action('wp_ajax_nopriv_track_fedex_package', 'track_fedex_package');

// Hook to save custom fields into the order

add_action('woocommerce_checkout_create_order_line_item', 'save_custom_fields_to_order_meta', 10, 4);
function save_custom_fields_to_order_meta( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['width'] ) ) {
        $item->add_meta_data( 'Width', $values['width'] );
    }

    if ( isset( $values['height'] ) ) {
        $item->add_meta_data( 'Height', $values['height'] );
    }

    if ( isset( $values['mount'] ) ) {
        $item->add_meta_data( 'Mount', $values['mount'] );
    }

    if ( isset( $values['window_name'] ) ) {
        $item->add_meta_data( 'Window Name', $values['window_name'] );
    }

    // if ( isset( $values['blind'] ) ) {
    //     $item->add_meta_data( 'Returns', $values['blind'] );
    // }

    if ( isset( $values['color'] ) ) {
        $item->add_meta_data( 'Color', $values['color'] );
    }

    if (isset($values['custom_quantity'])) {
        $item->add_meta_data('custom_quantity', intval($values['custom_quantity']), true);
    }
}


// ====================================//
//  >>  Generate custom coupon code
// ====================================//

// Register the AJAX actions
add_action('wp_ajax_generate_custom_discount_code', 'generate_custom_discount_code');
add_action('wp_ajax_nopriv_generate_custom_discount_code', 'generate_custom_discount_code');

// Function to generate a random discount code
function generate_custom_discount_code() {
    $amount = isset($_POST['coupon_amount']) ? sanitize_text_field($_POST['coupon_amount']) : '0';
    $code = 'FITrite-' . wp_generate_password(4, false, false);
    $discount_type = 'fixed_cart'; 
    $expiration_timestamp = time() + (5 * 60); // Current time + 5 minutes
    $expiration_date = date('Y-m-d H:i:s', $expiration_timestamp); // Format for WooCommerce

    if (!wc_get_coupon_id_by_code($code)) {
        $coupon = array(
            'post_title' => $code,
            'post_content' => '',
            'post_excerpt' => 'Auto-generated discount code.',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon',
        );
        $new_coupon_id = wp_insert_post($coupon);

        // Set the coupon properties
        update_post_meta($new_coupon_id, 'discount_type', $discount_type);
        update_post_meta($new_coupon_id, 'coupon_amount', $amount);
        update_post_meta($new_coupon_id, 'individual_use', 'yes');
        update_post_meta($new_coupon_id, 'usage_limit', '1');
        update_post_meta($new_coupon_id, 'expiry_date', $expiration_date); // Set expiration date for backend display
        update_post_meta($new_coupon_id, 'custom_expiration_timestamp', $expiration_timestamp); // Custom expiration timestamp for cron job if needed
    }

    wp_send_json_success(array('coupon_code' => $code));
}

// ====================================//
//  >>  Remove the last cart item for FITrite product 
// ====================================//

// Action hook to handle removing duplicate products in the cart
add_action('woocommerce_add_to_cart', 'remove_duplicate_product_from_cart', 10, 6);

function remove_duplicate_product_from_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
    // Check if the product ID is 1056 (targeted product)

    $hostName = $_SERVER['HTTP_HOST'];
    $fitriteProduct = ($hostName == "fauxwoodwarehouse.com") ? 1056 : 1058;

    if ($product_id == $fitriteProduct) {
        $cart = WC()->cart;
        $latest_item_key = null;

        // Loop through cart items to find the latest instance of the product
        foreach ($cart->get_cart() as $key => $item) {
            if ($item['product_id'] == $product_id) {
                // If there is already an instance, remove the previous one(s)
                if ($latest_item_key !== null) {
                    $cart->remove_cart_item($latest_item_key);
                }
                // Set the current key as the latest item key
                $latest_item_key = $key;
            }
        }
    }
}

// ====================================//
//  >>  Show coupon on order received page
// ====================================//
function display_discount_coupon_on_thankyou($order_id) {
    // Get the order
    $order = wc_get_order($order_id);
    $discount_amount = 0;

    // Determine the correct product ID based on hostname
    $hostName = $_SERVER['HTTP_HOST'];
    $fitriteProduct = ($hostName == "fauxwoodwarehouse.com") ? 1056 : 1058;

    // Check each item in the order to see if the determined product was purchased
    $product_in_order = false;
    foreach ($order->get_items() as $item) {
        if ($item->get_product_id() == $fitriteProduct) {
            $product_in_order = true;
            // Calculate the discount amount based on the custom attribute 'custom_quantity'
            $custom_quantity = $item->get_meta('custom_quantity');
            if ($custom_quantity) {
                $discount_amount += intval($custom_quantity) * 10; // Multiply custom quantity by $10
            }
            break; // Exit loop once the product is found
        }
    }

    // Only proceed if the product was in the order and a discount amount was calculated
    if ($product_in_order && $discount_amount > 0) {
        // Set a unique coupon code for this order
        $coupon_code = 'FITrite_' . $order_id;

        // Check if the coupon already exists, or create it
        if (!wc_get_coupon_id_by_code($coupon_code)) {
            $coupon = new WC_Coupon();
            $coupon->set_code($coupon_code);
            $coupon->set_discount_type('fixed_cart'); // Apply discount to the entire cart
            $coupon->set_amount($discount_amount);
            $coupon->set_usage_limit(1); // One-time use
            $coupon->set_individual_use(true); // Cannot be combined with other coupons
            $coupon->set_date_expires(strtotime('+30 days')); // Set expiry date
            $coupon->save();
        }

        // Display the coupon code and discount amount on the Thank You page
        echo '<div class="woocommerce-message">';
        echo '<p>Thank you for your order! Here is a discount code for your next purchase:</p>';
        echo '<p><strong>Coupon Code:</strong> ' . esc_html($coupon_code) . '</p>';
        echo '<p><strong>Discount Amount:</strong> ' . wc_price($discount_amount) . '</p>';
        echo '</div>';
    }
}

//add_action('woocommerce_thankyou', 'display_discount_coupon_on_thankyou', 10, 1);

// ===============================================================//
//  >>  Send coupon via email when customer places an order
// ===============================================================//
add_action('woocommerce_checkout_update_order_meta', 'generate_discount_coupon_on_checkout', 10, 1);

function generate_discount_coupon_on_checkout($order_id) {
    // Get the order
    $order = wc_get_order($order_id);
    $discount_amount = 0;

    // Determine the correct product ID based on hostname
    $hostName = $_SERVER['HTTP_HOST'];
    $fitriteProduct = ($hostName == "fauxwoodwarehouse.com") ? 1056 : 1058;

    // Check each item in the order to see if the determined product was purchased
    $product_in_order = false;
    foreach ($order->get_items() as $item) {
        if ($item->get_product_id() == $fitriteProduct) {
            $product_in_order = true;
            // Calculate the discount amount based on the custom attribute 'custom_quantity'
            $custom_quantity = $item->get_meta('custom_quantity');
            if ($custom_quantity) {
                $discount_amount += intval($custom_quantity) * 10; // Multiply custom quantity by $10
            }
            break; // Exit loop once the product is found
        }
    }

    // Only proceed if the product was in the order and a discount amount was calculated
    if ($product_in_order && $discount_amount > 0) {
        // Set a unique coupon code for this order
        $coupon_code = 'FITrite_' . $order_id;

        // Check if the coupon already exists, or create it
        if (!wc_get_coupon_id_by_code($coupon_code)) {
            $coupon = new WC_Coupon();
            $coupon->set_code($coupon_code);
            $coupon->set_discount_type('fixed_cart'); // Apply discount to the entire cart
            $coupon->set_amount($discount_amount);
            $coupon->set_usage_limit(1); // One-time use
            $coupon->set_individual_use(true); // Cannot be combined with other coupons
            $coupon->set_date_expires(strtotime('+30 days')); // Set expiry date
            $coupon->save();
        }

        // Send the coupon code to the customer's billing email
        $to = $order->get_billing_email();
        $subject = 'Your Discount Coupon Code';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $message = '<p>Thank you for your order! Here is a discount code for your next purchase:</p>';
        $message .= '<p><strong>Coupon Code:</strong> ' . esc_html($coupon_code) . '</p>';
        $message .= '<p><strong>Discount Amount:</strong> ' . wc_price($discount_amount) . '</p>';
        
        // Send the email
        wp_mail($to, $subject, $message, $headers);
    }
}

// ====================================//
//  >>  Apply coupon code
// ====================================//

// Register AJAX action for applying custom discount
// add_action('wp_ajax_apply_custom_discount', 'apply_custom_discount');
// add_action('wp_ajax_nopriv_apply_custom_discount', 'apply_custom_discount');

function apply_custom_discount() {
    // Verify nonce
    check_ajax_referer('custom_price_update_nonce', 'nonce');

    // Get the coupon code from the AJAX request
    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    // Check if product ID 1056 is in the cart
    $hostName = $_SERVER['HTTP_HOST'];
    $fitriteProduct = ($hostName == "fauxwoodwarehouse.com") ? 1056 : 1058;

    $product_in_cart = false;
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $fitriteProduct) {
            $product_in_cart = true;
            break;
        }
    }

    // If the product is not in the cart, return an error message
    if (!$product_in_cart) {
        wp_send_json_error(array('message' => 'This coupon is only valid for specific products in your cart.'));
    }

    // Proceed to apply the coupon if valid
    $coupon = new WC_Coupon($coupon_code);
    if (!$coupon->is_valid()) {
        wp_send_json_error(array('message' => 'Invalid or expired coupon code.'));
    }

    // Check if the coupon is already applied
    $applied_coupons = WC()->cart->get_applied_coupons();
    if (!in_array($coupon_code, $applied_coupons)) {
        // Apply the coupon to the cart
        WC()->cart->apply_coupon($coupon_code);
    }

    // Recalculate totals
    WC()->cart->calculate_totals();

    // Get the new cart total and discount amount
    $discount_amount = $coupon->get_amount();
    $cart_total = WC()->cart->total;

    // Send response with discount amount and new cart total
    wp_send_json_success(array(
        'discount_amount' => wc_price($discount_amount),
        'new_total' => wc_price($cart_total)
    ));
}


// ====================================//
//  >>  Disable tax for FITrite
// ====================================//
add_filter('woocommerce_product_get_tax_class', 'disable_tax_for_specific_product', 10, 2);

function disable_tax_for_specific_product($tax_class, $product) {

    $hostName = $_SERVER['HTTP_HOST'];
    $fitriteProduct = ($hostName == "fauxwoodwarehouse.com") ? 1056 : 1058;

    // Replace 123 with the ID of your specific product
    if ($product->get_id() === $fitriteProduct) {
        return 'No Tax'; // Return the tax class that you've set to be tax exempt
    }
    return $tax_class;
}


// ==================================================//
//  >>  Plugin Code Patch to Hide Vide Player Icon
// =================================================//
function aiovg_custom_player_css() {
    ?>
    <style type="text/css">
        body.aiovg-player .video-js .vjs-big-play-button { opacity: 0 !important; }
    </style>
    <?php
}
add_action( 'aiovg_player_head', 'aiovg_custom_player_css' );

// ====================================//
//  >>  Label for custom_quantity
// ====================================//
add_filter('woocommerce_order_item_display_meta_key', 'change_custom_quantity_label', 10, 2);

function change_custom_quantity_label($display_key, $meta) {
    // Check if the key is 'custom_quantity'
    if ($display_key === 'custom_quantity') {
        return __('Number of Windows', 'woocommerce'); // Change label to 'Number of Windows'
    }
    return $display_key; // Return original key if it does not match
}

// ===========================================//
//  >>  Change WooCommerce Email Footer Text
// ===========================================//
add_filter('woocommerce_email_footer_text', 'custom_woocommerce_email_footer_text');

function custom_woocommerce_email_footer_text($footer_text) {
    // Replace this with your custom text
    $footer_text = 'Faux Wood Warehouse';
    return $footer_text;
}

// ====================================//
//  >>  ACTIONS
// ====================================//
require_once(get_stylesheet_directory() . '/actions/no-shipping-for-measurement.php');
require_once(get_stylesheet_directory() . '/actions/standalone-measurement-on-cart.php');
require_once(get_stylesheet_directory() . '/actions/alter-coupon-code-position.php');
require_once(get_stylesheet_directory() . '/actions/pricing-table.php');
require_once(get_stylesheet_directory() . '/actions/shipping-logic.php');
require_once(get_stylesheet_directory() . '/actions/installation-fee.php');
require_once(get_stylesheet_directory() . '/actions/handling-fee.php');