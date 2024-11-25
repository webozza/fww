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

// Add script to calculate installation fee with JavaScript
add_action('wp_footer', 'add_installation_fee_calculation_script');
function add_installation_fee_calculation_script() {
    if (is_cart() && !product_1056_in_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // Function to calculate the total installation fee
                function calculateInstallationFee() {
                    let baseFee = 75; // Base fee
                    let additionalFee = 0; // Additional fee based on widths

                    // Iterate over cart items
                    $('.cart_item').each(function () {
                        let widthText = $(this).find('.variation-Width').text().trim(); // Example selector
                        let width = parseInt(widthText) || 0;

                        // Add to the additional fee based on the width
                        if (width > 0 && width <= 36) {
                            additionalFee += 25;
                        } else if (width > 36 && width <= 72) {
                            additionalFee += 30;
                        } else if (width > 72) {
                            additionalFee += 35;
                        }
                    });

                    // Return the total fee
                    return baseFee + additionalFee;
                }

                // Handle checkbox change
                $('#installation-required').on('change', function () {
                    let installationRequired = $(this).is(':checked') ? 'yes' : 'no';
                    let totalFee = installationRequired === 'yes' ? calculateInstallationFee() : 0;

                    // Send the data to the server
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_installation_fee',
                            installation_required: installationRequired,
                            total_fee: totalFee
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

// Handle AJAX request to update the installation fee
add_action('wp_ajax_update_installation_fee', 'update_installation_fee');
add_action('wp_ajax_nopriv_update_installation_fee', 'update_installation_fee');
function update_installation_fee() {
    $installation_required = sanitize_text_field($_POST['installation_required']);
    $total_fee = floatval($_POST['total_fee']);

    WC()->session->set('installation_required', $installation_required);
    WC()->session->set('installation_fee', $total_fee);

    wp_send_json_success(['message' => 'Installation fee updated']);
}

// Add the installation fee to the cart totals
add_action('woocommerce_cart_calculate_fees', 'add_combined_installation_fee_to_cart');
function add_combined_installation_fee_to_cart(WC_Cart $cart) {
    $installation_required = WC()->session->get('installation_required');
    $installation_fee = WC()->session->get('installation_fee');

    // Remove installation fee if product ID 1056 is in the cart
    if (product_1056_in_cart()) {
        WC()->session->set('installation_required', 'no');
        WC()->session->set('installation_fee', 0);
        return;
    }

    if ($installation_required === 'yes' && $installation_fee > 0) {
        $cart->add_fee('Installation Fee', $installation_fee, true, 'standard');
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
