<?php

// Add checkboxes for installation on the cart page
add_action('woocommerce_after_cart_item_name', 'add_installation_checkbox_to_cart_item', 10, 2);
function add_installation_checkbox_to_cart_item($cart_item, $cart_item_key) {
    // Add a checkbox for each cart item
    $checked = isset($cart_item['installation_required']) && $cart_item['installation_required'] === 'yes' ? 'checked' : '';
    echo '<div class="installation-checkbox">';
    echo '<label>';
    echo '<input type="checkbox" class="installation-required" data-cart-key="' . esc_attr($cart_item_key) . '" value="yes" ' . $checked . '>';
    echo ' I need installation (additional cost)';
    echo '</label>';
    echo '</div>';
}

// Add script to handle checkbox changes dynamically
add_action('wp_footer', 'add_installation_checkbox_script');
function add_installation_checkbox_script() {
    if (is_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.installation-required').on('change', function () {
                    var cartKey = $(this).data('cart-key');
                    var installationRequired = $(this).is(':checked') ? 'yes' : 'no';

                    // Dynamically retrieve the product's width for fee calculation
                    var widthText = $(this).closest('tr').find('.variation-Width p').text();
                    var width = parseInt(widthText) || 0;

                    var installationFee = 0;
                    if (installationRequired === 'yes') {
                        if (width <= 36) {
                            installationFee = 25;
                        } else if (width > 36 && width <= 72) {
                            installationFee = 30;
                        } else if (width > 72) {
                            installationFee = 35;
                        }
                    }

                    // Send the data to the server
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_cart_item_installation',
                            cart_key: cartKey,
                            installation_required: installationRequired,
                            installation_fee: installationFee,
                        },
                        success: function (response) {
                            if (response.success) {
                                $('body').trigger('update_checkout'); // Update totals dynamically
                                location.reload(); // Reload page to reflect changes
                            } else {
                                console.error(response);
                            }
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                });
            });
        </script>
        <?php
    }
}

// Handle AJAX request to update cart item meta
add_action('wp_ajax_update_cart_item_installation', 'update_cart_item_installation');
add_action('wp_ajax_nopriv_update_cart_item_installation', 'update_cart_item_installation');
function update_cart_item_installation() {
    // Get cart data
    $cart_key = sanitize_text_field($_POST['cart_key']);
    $installation_required = sanitize_text_field($_POST['installation_required']);
    $installation_fee = floatval($_POST['installation_fee']);

    // Update the specific cart item's meta
    $cart = WC()->cart->get_cart();
    if (isset($cart[$cart_key])) {
        WC()->cart->cart_contents[$cart_key]['installation_required'] = $installation_required;
        WC()->cart->cart_contents[$cart_key]['installation_fee'] = $installation_fee;
    }

    // Save the cart session and recalculate totals
    WC()->cart->set_session();
    WC()->cart->calculate_totals();

    wp_send_json_success(['message' => 'Installation fee updated for item']);
}

// Add combined installation fees to the cart
add_action('woocommerce_cart_calculate_fees', 'add_combined_installation_fees_to_cart');
function add_combined_installation_fees_to_cart() {
    $total_installation_fee = 0; // Initialize the total installation fee

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        // Check if the item requires installation
        if (isset($cart_item['installation_required']) && $cart_item['installation_required'] === 'yes') {
            $fee = isset($cart_item['installation_fee']) ? floatval($cart_item['installation_fee']) : 0;
            $total_installation_fee += $fee; // Add to the total
        }
    }

    // Add the total installation fee as a single line item
    if ($total_installation_fee > 0) {
        WC()->cart->add_fee('Installation Fee', $total_installation_fee, true, 'standard');
    }
}

// Utility function to check if a product is in the cart
if (!function_exists('cart_contains_product')) {
    function cart_contains_product($product_id) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] == $product_id) {
                return true;
            }
        }
        return false;
    }
}
