<?php
// Add checkboxes for installation on the cart page
add_action('woocommerce_after_cart_item_name', 'add_installation_checkbox_to_cart_item', 10, 2);
function add_installation_checkbox_to_cart_item($cart_item, $cart_item_key) {
    // Exclude product with ID 1056
    if ($cart_item['product_id'] == 1056) {
        return;
    }

    // Add a checkbox for each cart item
    $checked = isset($cart_item['installation_required']) && $cart_item['installation_required'] === 'yes' ? 'checked' : '';
    echo '<div class="installation-checkbox">';
    echo '<label>';
    echo '<input type="checkbox" class="installation-required" data-cart-key="' . $cart_item_key . '" value="yes" ' . $checked . '>';
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
            jQuery(document).ready(function($) {
                function calculateItemInstallationFee(width) {
                    if (width <= 36) {
                        return 25;
                    } else if (width > 36 && width <= 72) {
                        return 30;
                    } else {
                        return 35;
                    }
                }

                $('.installation-required').on('change', function() {
                    var cartKey = $(this).data('cart-key');
                    var installationRequired = $(this).is(':checked') ? 'yes' : 'no';

                    // Get the product width dynamically
                    var widthText = $(this).closest('tr').find('.variation-Width p').text();
                    var width = parseInt(widthText) || 0;

                    var installationFee = installationRequired === 'yes' ? calculateItemInstallationFee(width) : 0;

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_cart_item_installation',
                            cart_key: cartKey,
                            installation_required: installationRequired,
                            installation_fee: installationFee
                        },
                        success: function() {
                            $('body').trigger('update_checkout');
                            location.reload(); // Refresh cart totals
                        }
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
    $cart = WC()->cart->get_cart();
    $cart_key = sanitize_text_field($_POST['cart_key']);
    $installation_required = sanitize_text_field($_POST['installation_required']);
    $installation_fee = floatval($_POST['installation_fee']);

    if (isset($cart[$cart_key])) {
        $cart[$cart_key]['installation_required'] = $installation_required;
        $cart[$cart_key]['installation_fee'] = $installation_fee;
    }

    WC()->cart->calculate_totals();
    wp_die();
}

// Add installation fees to the cart
add_action('woocommerce_cart_calculate_fees', 'add_installation_fees_to_cart');
function add_installation_fees_to_cart() {
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['installation_required']) && $cart_item['installation_required'] === 'yes') {
            $fee = isset($cart_item['installation_fee']) ? floatval($cart_item['installation_fee']) : 0;
            WC()->cart->add_fee('Installation Fee (' . $cart_item['data']->get_name() . ')', $fee, true, 'standard');
        }
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
