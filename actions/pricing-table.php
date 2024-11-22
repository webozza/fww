<?php
// Add an admin menu for managing the pricing table
add_action('admin_menu', 'child_theme_pricing_table_menu');

function child_theme_pricing_table_menu() {
    add_menu_page(
        'Pricing Table Manager',
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
            <table class="widefat fixed" style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>Height</th>
                        <th>24"</th>
                        <th>28"</th>
                        <th>34"</th>
                        <th>40"</th>
                        <th>46"</th>
                        <th>52"</th>
                        <th>58"</th>
                        <th>64"</th>
                        <th>70"</th>
                        <th>76"</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $heights = [30, 34, 40, 46, 52, 58, 64, 70, 76];
                    $widths = [24, 28, 34, 40, 46, 52, 58, 64, 70, 76];

                    foreach ($heights as $height) {
                        echo '<tr>';
                        echo '<td>' . esc_html($height) . '"</td>';
                        foreach ($widths as $width) {
                            $value = isset($pricing_table_data[$height][$width]) ? esc_attr($pricing_table_data[$height][$width]) : '';
                            echo '<td><input type="number" name="pricing_table[' . $height . '][' . $width . ']" value="' . $value . '" /></td>';
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
