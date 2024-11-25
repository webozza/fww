<?php

// Add a single installation checkbox on the cart page
add_action('woocommerce_cart_totals_before_order_total', 'add_installation_checkbox_to_cart_totals');
function add_installation_checkbox_to_cart_totals() {
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
    if (is_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#installation-required').on('change', function () {
                    var installationRequired = $(this).is(':checked') ? 'yes' : 'no';

                    // Calculate installation fee
                    var totalWidth = 0;
                    $('.cart_item').each(function () {
                        var widthText = $(this).find('.variation-Width p').text();
                        var width = parseInt(widthText) || 0;
                        totalWidth += width;
                    });

                    var installationFee = 75; // Base fee
                    if (installationRequired === 'yes') {
                        if (totalWidth <= 36) {
                            installationFee += 25;
                        } else if (totalWidth > 36 && totalWidth <= 72) {
                            installationFee += 30;
                        } else if (totalWidth > 72) {
                            installationFee += 35;
                        }
                    }

                    // Send the data to the server
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_installation_fee',
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

// Handle AJAX request to update session for installation
add_action('wp_ajax_update_installation_fee', 'update_installation_fee');
add_action('wp_ajax_nopriv_update_installation_fee', 'update_installation_fee');
function update_installation_fee() {
    $installation_required = sanitize_text_field($_POST['installation_required']);
    $installation_fee = floatval($_POST['installation_fee']);

    WC()->session->set('installation_required', $installation_required);
    WC()->session->set('installation_fee', $installation_fee);

    wp_send_json_success(['message' => 'Installation fee updated']);
}

// Add installation fee to the cart totals
add_action('woocommerce_cart_calculate_fees', 'add_combined_installation_fee_to_cart');
function add_combined_installation_fee_to_cart() {
    $installation_required = WC()->session->get('installation_required');
    $installation_fee = WC()->session->get('installation_fee');

    if ($installation_required === 'yes' && $installation_fee > 0) {
        WC()->cart->add_fee('Installation Fee', $installation_fee, true, 'standard');
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
