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
            Want us to install for you?<br> (additional cost applies)
        </label>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            // Retrieve ZIP code status from localStorage
            let zipCodeStatus = localStorage.getItem('zip_code_status');

            // Check if zipCodeStatus exists and is not null
            if (zipCodeStatus) {
                zipCodeStatus = JSON.parse(zipCodeStatus);

                // Check if the ZIP code is valid
                if (zipCodeStatus.isValid) {
                    // Show the installation checkbox if ZIP code is valid
                    document.querySelector('.installation-checkbox-container').style.display = 'block';
                } else {
                    // Hide the installation checkbox if ZIP code is invalid
                    document.querySelector('.installation-checkbox-container').style.display = 'none';
                }
            } else {
                // No ZIP code checked, ensure the installation checkbox is hidden
                document.querySelector('.installation-checkbox-container').style.display = 'none';
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
                // Hide duplicate installation fees in WooCommerce cart
                function hideDuplicateInstallationFees() {
                    $('.fee th').each(function () {
                        let feeType = $(this).text().trim();
                        if (feeType === "Installation Fee") {
                            $(this).parent().hide(); // Hide the duplicate row
                        }
                    });
                }

                // Function to calculate the total installation fee
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
                }

                // Update the installation fee dynamically
                function updateInstallationFee() {
                    let installationRequired = $('#installation-required').is(':checked') ? 'yes' : 'no';
                    let totalFee = installationRequired === 'yes' ? calculateInstallationFee() : 0;

                    // Update the fee in the DOM
                    displayInstallationFee(totalFee);

                    // Sync the fee with WooCommerce backend
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
                                hideDuplicateInstallationFees(); // Ensure duplicates are hidden
                            } else {
                                console.error(response);
                            }
                        },
                        error: function (error) {
                            console.error(error);
                        },
                    });
                }

                // Trigger fee calculation on checkbox change
                $('#installation-required').on('change', function () {
                    updateInstallationFee();
                });

                // Trigger fee calculation on cart updates
                $('body').on('updated_cart_totals', function () {
                    let installationRequired = $('#installation-required').is(':checked') ? 'yes' : 'no';
                    let totalFee = installationRequired === 'yes' ? calculateInstallationFee() : 0;
                    displayInstallationFee(totalFee);
                    hideDuplicateInstallationFees(); // Ensure duplicates are hidden
                });

                // Initial calculation on page load
                $(document).ready(function () {
                    let installationRequired = $('#installation-required').is(':checked') ? 'yes' : 'no';

                    // If the checkbox is checked, calculate and display the fee
                    if (installationRequired === 'yes') {
                        let totalFee = calculateInstallationFee();
                        displayInstallationFee(totalFee);

                        // Sync with WooCommerce backend
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
                                    hideDuplicateInstallationFees(); // Ensure duplicates are hidden
                                }
                            },
                            error: function (error) {
                                console.error(error);
                            }
                        });
                    }
                });

                // WooCommerce price formatting helper
                function wc_price(amount) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: '<?php echo get_woocommerce_currency(); ?>'
                    }).format(amount);
                }
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