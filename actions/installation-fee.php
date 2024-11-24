<?php

add_action('woocommerce_after_cart_table', 'add_installation_checkbox');
function add_installation_checkbox() {
    if (cart_contains_product(1056)) {
        return; // Do not show the installation checkbox if product ID 1056 is in the cart
    }
    ?>
    <div class="installation-checkbox">
        <label>
            <input type="checkbox" id="installation_required" name="installation_required" value="yes">
            I need installation (additional cost)
        </label>
    </div>
    <?php
}

add_action('wp_footer', 'add_installation_checkbox_script');
function add_installation_checkbox_script() {
    if (is_cart()) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function calculateInstallationPrice() {
                    var $productRows = $('.woocommerce-cart-form .woocommerce-cart-form__contents tbody tr');
                    var countWidthLessThan36 = 0;
                    var countWidth36to72 = 0;
                    var countWidthMoreThan72 = 0;

                    $productRows.each(function() {
                        var widthText = $(this).find('.variation-Width p').eq(0).text();
                        var width = parseInt(widthText);

                        if (width <= 36) {
                            countWidthLessThan36++;
                        } else if (width > 36 && width <= 72) {
                            countWidth36to72++;
                        } else if (width > 72) {
                            countWidthMoreThan72++;
                        }
                    });

                    var additionalFee = countWidthLessThan36 * 25 + countWidth36to72 * 30 + countWidthMoreThan72 * 35;
                    return Math.max(75, additionalFee);
                }

                function update_installation_fee() {
                    var installation_required = $('#installation_required').is(':checked') ? 'yes' : 'no';
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'update_installation_fee',
                            installation_required: installation_required,
                            installationPrice: calculateInstallationPrice(),
                        },
                        success: function() {
                            $('body').trigger('update_checkout');
                        }
                    });
                }

                $('#installation_required').on('change', function() {
                    update_installation_fee();
                });
            });
        </script>
        <?php
    }
}

add_action('wp_ajax_update_installation_fee', 'update_installation_fee');
add_action('wp_ajax_nopriv_update_installation_fee', 'update_installation_fee');
function update_installation_fee() {
    if (isset($_POST['installationPrice'])) {
        WC()->session->set('installationPrice', $_POST['installationPrice']);
    }
    WC()->session->set('installation_required', isset($_POST['installation_required']) && $_POST['installation_required'] === 'yes');
    WC()->cart->calculate_totals();
    wp_die();
}

add_action('woocommerce_cart_calculate_fees', 'add_installation_fee');
function add_installation_fee() {
    if (WC()->session->get('installation_required') === true) {
        $installation_fee = WC()->session->get('installationPrice');
        WC()->cart->add_fee('Installation Fee', $installation_fee, true, 'standard');
    }
}

add_action('woocommerce_cart_calculate_fees', 'remove_installation_fee_if_product_in_cart');
function remove_installation_fee_if_product_in_cart() {
    if (cart_contains_product(1056)) {
        WC()->session->set('installation_required', false);
        WC()->session->set('installationPrice', 0);
    }
}

add_action('woocommerce_remove_cart_item', 'remove_installation_fee_on_item_removal', 10, 2);
function remove_installation_fee_on_item_removal($cart_item_key, $cart) {
    if (!cart_contains_product(1056) && !WC()->session->get('installation_required')) {
        WC()->session->set('installation_required', false);
        WC()->session->set('installationPrice', 0);
        WC()->cart->calculate_totals();
    }
}

// Declare cart_contains_product only if it doesn't already exist
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

add_action('wp', 'initialize_installation_session');
function initialize_installation_session() {
    if (is_cart() && WC()->session->get('installation_required') === null) {
        WC()->session->set('installation_required', false);
    }
}
