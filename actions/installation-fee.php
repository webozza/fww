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
                // Function to calculate the installation fee
                function calculateInstallationFee() {
                    let additionalFee = 0;

                    // Iterate over cart items to determine the fee based on width
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

                // Function to add/remove the installation fee dynamically
                function updateInstallationFee(installationRequired) {
                    let totalFee = installationRequired ? calculateInstallationFee() : 0;

                    // Send the fee to the backend to update WooCommerce totals
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_installation_fee',
                            installation_required: installationRequired ? 'yes' : 'no',
                            total_fee: totalFee
                        },
                        success: function () {
                            // Refresh the WooCommerce cart totals
                            $('body').trigger('update_checkout');
                        },
                        error: function (error) {
                            console.error('Failed to update installation fee:', error);
                        }
                    });
                }

                // Handle checkbox change to toggle the installation fee
                $('#installation-required').on('change', function () {
                    let installationRequired = $(this).is(':checked');
                    updateInstallationFee(installationRequired);
                });

                // Initial setup: ensure fee is applied if the checkbox is already checked
                $(document).ready(function () {
                    let installationRequired = $('#installation-required').is(':checked');
                    if (installationRequired) {
                        updateInstallationFee(true);
                    }
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
