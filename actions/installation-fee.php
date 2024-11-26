<?php
// Add a single installation checkbox on the cart page
add_action('woocommerce_cart_totals_before_order_total', 'add_installation_checkbox_to_cart_totals');
function add_installation_checkbox_to_cart_totals() {
    if (product_1056_in_cart()) {
        return; // Do not display the checkbox if product ID 1056 is in the cart
    }

    ?>
    <div class="installation-checkbox-container">
        <label>
            <input type="checkbox" id="installation-required" value="yes">
            Want us to install for you? (additional cost applies)
        </label>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            // Retrieve ZIP code status from localStorage
            let zipCodeStatus = localStorage.getItem('zip_code_status');
            if (zipCodeStatus) {
                zipCodeStatus = JSON.parse(zipCodeStatus);
                if (zipCodeStatus.isValid) {
                    // Show the installation checkbox if ZIP code is valid
                    document.querySelector('.installation-checkbox-container').style.display = 'block';
                }
            }
        });
    </script>
    <?php
}


// Add script to handle dynamic cart updates
add_action('wp_footer', 'add_installation_fee_calculation_script');
function add_installation_fee_calculation_script() {
    if (is_cart() && !product_1056_in_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // Function to calculate the installation fee based on item width
                function calculateInstallationFee() {
                    let additionalFee = 0;

                    // Iterate over cart items
                    $('.cart_item').each(function () {
                        let widthText = $(this).find('.variation-Width p').text().trim();
                        let width = parseInt(widthText) || 0;

                        if (width > 0 && width <= 36) {
                            additionalFee += 25;
                        } else if (width > 36 && width <= 72) {
                            additionalFee += 30;
                        } else if (width > 72) {
                            additionalFee += 35;
                        }
                    });

                    return Math.max(additionalFee, 75); // Minimum fee is $75
                }

                // Function to display the installation fee
                function displayInstallationFee(totalFee) {
                    // Remove existing fee row if present
                    $('.installation-fee-row').remove();

                    // Add the installation fee row dynamically
                    if (totalFee > 0) {
                        $('<tr class="installation-fee-row"><th>Installation Fee</th><td>' + wc_price(totalFee) + '</td></tr>')
                            .insertAfter('.cart_totals .cart-subtotal');
                    }

                    // Update total
                    updateCartTotal(totalFee);
                }

                // Function to update cart total dynamically
                function updateCartTotal(installationFee) {
                    const subtotalText = $('.cart-subtotal td').text().replace(/[^0-9.-]+/g, '');
                    let subtotal = parseFloat(subtotalText) || 0;

                    const shippingText = $('.shipping td').text().replace(/[^0-9.-]+/g, '');
                    let shipping = parseFloat(shippingText) || 0;

                    let newTotal = subtotal + shipping + installationFee;

                    // Update total in DOM
                    $('.order-total td').text(wc_price(newTotal));
                }

                // Sync the installation fee with WooCommerce backend
                function syncInstallationFee(installationRequired, totalFee) {
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
                                console.log('Installation fee synced with backend.');
                            } else {
                                console.error('Failed to sync installation fee:', response);
                            }
                        },
                        error: function (error) {
                            console.error('Error syncing installation fee:', error);
                        },
                    });
                }

                // Trigger fee calculation on checkbox change
                $('#installation-required').on('change', function () {
                    let installationRequired = $(this).is(':checked') ? 'yes' : 'no';
                    let totalFee = installationRequired === 'yes' ? calculateInstallationFee() : 0;

                    // Update the fee in the DOM
                    displayInstallationFee(totalFee);

                    // Sync the fee with WooCommerce backend
                    syncInstallationFee(installationRequired, totalFee);
                });

                // Initial calculation on page load
                $(document).ready(function () {
                    let installationRequired = $('#installation-required').is(':checked') ? 'yes' : 'no';

                    // If the checkbox is checked, calculate and display the fee
                    if (installationRequired === 'yes') {
                        let totalFee = calculateInstallationFee();
                        displayInstallationFee(totalFee);

                        // Sync with WooCommerce backend
                        syncInstallationFee(installationRequired, totalFee);
                    }
                });
            });
        </script>
        <?php
    }
}


// AJAX handler for updating installation fee
add_action('wp_ajax_update_installation_fee', 'update_installation_fee');
add_action('wp_ajax_nopriv_update_installation_fee', 'update_installation_fee');
function update_installation_fee() {
    $installation_required = sanitize_text_field($_POST['installation_required']);
    $total_fee = floatval($_POST['total_fee']);

    WC()->session->set('installation_required', $installation_required);
    WC()->session->set('installation_fee', $total_fee);

    wp_send_json_success();
}

// Add fee to WooCommerce cart
add_action('woocommerce_cart_calculate_fees', 'add_combined_installation_fee_to_cart');
function add_combined_installation_fee_to_cart(WC_Cart $cart) {
    $installation_required = WC()->session->get('installation_required');
    $installation_fee = WC()->session->get('installation_fee');

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
