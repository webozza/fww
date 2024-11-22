<?php
// Add an admin menu for managing the pricing table
add_action('admin_menu', 'child_theme_pricing_table_menu');

function child_theme_pricing_table_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save the updated table data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['pricing_table'])) {
            $pricing_table = $_POST['pricing_table'];
            update_option('child_theme_pricing_table_data', $pricing_table);
        }
        if (isset($_POST['discount_percentage'])) {
            $discount_percentage = floatval($_POST['discount_percentage']);
            update_option('child_theme_discount_percentage', $discount_percentage);
        }
        echo '<div class="updated"><p>Pricing table and discount updated successfully!</p></div>';
    }

    // Retrieve existing table data
    $pricing_table_data = get_option('child_theme_pricing_table_data', []);
    $discount_percentage = get_option('child_theme_discount_percentage', 0);

    ?>
    <div class="wrap">
        <h1>Pricing Table Manager</h1>
        <form method="POST">
            <label for="discount_percentage" style="display: block; margin-bottom: 10px;">
                <strong>Discount Percentage:</strong>
                <input type="number" id="discount_percentage" name="discount_percentage" value="<?php echo esc_attr($discount_percentage); ?>" step="0.01" style="width: 100px;" />%
            </label>
            <table class="widefat fixed" style="margin-bottom: 20px; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9f9f9;">
                        <th style="padding: 10px; border: 1px solid #ddd;"></th>
                        <?php
                        $widths = [24, 30, 36, 42, 48, 54, 60, 66, 72, 78];
                        foreach ($widths as $width) {
                            echo '<th style="padding: 10px; border: 1px solid #ddd;">' . esc_html($width) . '"</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $heights = [30, 36, 42, 48, 54, 60, 66, 72, 78];
                    foreach ($heights as $height) {
                        echo '<tr>';
                        echo '<td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">' . esc_html($height) . '"</td>';
                        foreach ($widths as $width) {
                            $value = isset($pricing_table_data[$height][$width]) ? esc_attr($pricing_table_data[$height][$width]) : '';
                            echo '<td style="padding: 10px; border: 1px solid #ddd;"><input type="number" name="pricing_table[' . $height . '][' . $width . ']" value="' . $value . '" style="width: 100%; box-sizing: border-box; padding: 5px;" /></td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="button button-primary">Save Table</button>
        </form>
    </div>
    <?php
}

add_action('wp_footer', 'output_pricing_table_script');
function output_pricing_table_script() {
    if (is_product()) { // Check if it's a single product page
        $pricing_table_data = get_option('child_theme_pricing_table_data', []);
        $discount_percentage = get_option('child_theme_discount_percentage', 0);
        ?>
        <script>
            const pricingTable = <?php echo json_encode($pricing_table_data); ?>;
            const discountPercentage = <?php echo floatval($discount_percentage); ?>;

            // Apply discount to the pricing table
            const discountedPricingTable = JSON.parse(JSON.stringify(pricingTable)); // Deep clone
            Object.keys(discountedPricingTable).forEach(height => {
                Object.keys(discountedPricingTable[height]).forEach(width => {
                    discountedPricingTable[height][width] = 
                        (discountedPricingTable[height][width] * (1 - discountPercentage / 100)).toFixed(2);
                });
            });

            console.log('Discounted Pricing Table:', discountedPricingTable);
        </script>
        <?php
    }
}
