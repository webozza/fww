<?php

// Show the zip code checker and installation checkbox above the Proceed to Checkout button
add_action('woocommerce_proceed_to_checkout', 'display_zip_code_checker_and_installation_checkbox', 10);
function display_zip_code_checker_and_installation_checkbox() {
    $product_id = 1056;
    $available_zip_codes = get_field('zip_code', $product_id);
    $cros_icon = get_stylesheet_directory_uri() . '/assets/cross.png';
    $tick_icon = get_stylesheet_directory_uri() . '/assets/tick.png';
    ?>
    <div class="zip-code-check-wrapper">
        <div class="zip-code-check">
            <p>Want us to install for you?</p>
            <p>Enter your zip code below to see if installation <br> is available in your area</p>
            <label for="zip_code">ZIP CODE</label>
            <div class="fww-flex-row">
                <input type="text" id="zip_code" name="zip_code" placeholder="90210">
                <button id="check_zip_code">CHECK</button>
            </div>
            <div id="zip_code_result"></div>
        </div>
        <div class="installation-checkbox-container" style="display: none;">
            <label>
                <input type="checkbox" id="installation-required" value="yes">
                Want us to install for you? <strong><span class="place-installation-fee"></span></strong>
            </label>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availableZipCodes = "<?php echo esc_js($available_zip_codes); ?>".split(',');
            const crosIcon = "<?php echo esc_js($cros_icon); ?>";
            const tickIcon = "<?php echo esc_js($tick_icon); ?>";
            const successText = ``;
            const unavailableText = `<span class="zip-not-available"><img src="${crosIcon}"> Unfortunately not at this time</span>`;

            document.getElementById('check_zip_code').addEventListener('click', function() {
                const zipCode = document.getElementById('zip_code').value.trim();
                const resultDiv = document.getElementById('zip_code_result');
                const isValid = availableZipCodes.includes(zipCode);

                resultDiv.innerHTML = isValid ? successText : unavailableText;

                // Toggle the installation checkbox visibility
                const checkboxContainer = document.querySelector('.installation-checkbox-container');
                if (isValid) {
                    checkboxContainer.style.display = 'block';
                } else {
                    checkboxContainer.style.display = 'none';
                }
            });
        });

        jQuery(document).ready(function ($) {
            // Function to calculate the installation fee (display only)
            function calculateDisplayOnlyInstallationFee() {
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

            // Function to display the installation fee (always)
            function showInstallationFeePreview() {
                let totalFee = calculateDisplayOnlyInstallationFee();

                // Update the .place-installation-fee element
                $('.place-installation-fee').text(wc_price(totalFee));
            }

            // Format price as WooCommerce does
            function wc_price(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: '<?php echo get_woocommerce_currency(); ?>'
                }).format(amount);
            }

            // Trigger the display of installation fee preview on page load
            function initializeFeePreview() {
                // Delay execution until the cart is fully rendered
                setTimeout(() => {
                    showInstallationFeePreview();
                }, 500); // Adjust the delay if necessary
            }

            // Listen for WooCommerce cart updates and re-trigger the fee preview
            $('body').on('updated_cart_totals', function () {
                initializeFeePreview();
            });

            // Initial fee calculation on page load
            initializeFeePreview();
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