<?php

// Add a single installation checkbox on the cart page
add_action('woocommerce_cart_totals_before_order_total', 'add_installation_checkbox_to_cart_totals');
function add_installation_checkbox_to_cart_totals() {
    if (product_1056_in_cart()) {
        return; // Do not display the checkbox if product ID 1056 is in the cart
    }

    $checked = WC()->session->get('installation_required') === 'yes' ? 'checked' : '';
    echo '<div class="installation-checkbox">';
    echo '<label>';
    echo '<input type="checkbox" id="installation-required" value="yes" ' . $checked . '>';
    echo ' I need installation (additional cost applies)';
    echo '</label>';
    echo '</div>';
}

// Add script to handle checkbox changes dynamically
add_action('wp_footer', 'add_installation_checkbox_script');
function add_installation_checkbox_script() {
    if (is_cart() && !product_1056_in_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#installation-required').on('change', function () {
                    var installationRequired = $(this).is(':checked') ? 'yes' : 'no';

                    // Send the data to the server
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_installation_fee',
                            installation_required: installationRequired
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

// Handle AJAX request to update session for installation
add_action('wp_ajax_update_installation_fee', 'update_installation_fee');
add_action('wp_ajax_nopriv_update_installation_fee', 'update_installation_fee');
function update_installation_fee() {
    $installation_required = sanitize_text_field($_POST['installation_required']);
    WC()->session->set('installation_required', $installation_required);
    wp_send_json_success(['message' => 'Installation status updated']);
}

// Add installation fee to the cart totals
add_action('woocommerce_cart_calculate_fees', 'add_combined_installation_fee_to_cart');
function add_combined_installation_fee_to_cart(WC_Cart $cart) {
    $installation_required = WC()->session->get('installation_required');

    // Remove installation fee if product ID 1056 is in the cart
    if (product_1056_in_cart()) {
        WC()->session->set('installation_required', 'no');
        return; // Exit without applying the fee
    }

    if ($installation_required === 'yes') {
        $base_fee = 75; // Base installation fee
        $additional_fee = 0; // Additional fee for all items

        // Iterate through all items in the cart
        foreach ($cart->get_cart() as $cart_item) {
            // Exclude product with ID 1056 from the fee calculation
            if ($cart_item['product_id'] == 1056) {
                continue;
            }

            // Check if the width is stored in the item's metadata
            if (isset($cart_item['variation']['Width'])) {
                $width = intval($cart_item['variation']['Width']); // Get the width

                // Calculate fee based on width
                if ($width > 0 && $width <= 36) {
                    $additional_fee += 25; // Add $25 for widths <= 36
                } elseif ($width > 36 && $width <= 72) {
                    $additional_fee += 30; // Add $30 for widths between 37 and 72
                } elseif ($width > 72) {
                    $additional_fee += 35; // Add $35 for widths > 72
                }
            }
        }

        // Total fee is the base fee plus all additional fees
        $total_fee = $base_fee + $additional_fee;

        // Log the calculation details
        $log_file = WP_CONTENT_DIR . '/installation-fee-debug.log';
        $log_data = [
            'base_fee' => $base_fee,
            'additional_fee' => $additional_fee,
            'total_fee' => $total_fee,
        ];
        file_put_contents($log_file, json_encode($log_data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

        // Add the installation fee to the cart
        $cart->add_fee('Installation Fee', $total_fee, true, 'standard');
    }
}

// Helper function to check if product ID 1056 is in the cart
function product_1056_in_cart() {
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == 1056) {
            return true;
        }
    }
    return false;
}
