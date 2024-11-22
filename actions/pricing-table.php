<?php
// Add an admin menu for managing the pricing table
add_action('admin_menu', 'child_theme_pricing_table_menu');

function child_theme_pricing_table_menu() {
    add_menu_page(
        'Faux Wood Warehouse Pricing Table Manager',
        'Pricing Table',
        'manage_options',
        'child-theme-pricing-table',
        'child_theme_pricing_table_page',
        'dashicons-editor-table',
        20
    );
}

// Display the admin page
function child_theme_pricing_table_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save the updated table data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pricing_table'])) {
        $pricing_table = $_POST['pricing_table'];
        update_option('child_theme_pricing_table_data', $pricing_table);
        echo '<div class="updated"><p>Pricing table updated successfully!</p></div>';
    }

    // Retrieve existing table data
    $pricing_table_data = get_option('child_theme_pricing_table_data', []);

    ?>
    <div class="wrap">
        <h1>Pricing Table Manager</h1>
        <form method="POST">
            <table class="widefat fixed" style="margin-bottom: 20px; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9f9f9;">
                        <th style="padding: 10px; border: 1px solid #ddd;"></th>
                        <th style="padding: 10px; border: 1px solid #ddd;">24"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">30"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">36"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">42"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">48"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">54"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">60"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">66"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">72"</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">78"</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $heights = [30, 36, 42, 48, 54, 60, 66, 72, 78];
                    $widths = [24, 30, 36, 42, 48, 54, 60, 66, 72, 78];

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
        ?>
        <script>
            const pricingTable = <?php echo json_encode($pricing_table_data); ?>;
            console.log('Pricing Table:', pricingTable);
        </script>
        <?php
    }
}
