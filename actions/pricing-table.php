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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pricing_table_data'])) {
        update_option('child_theme_pricing_table_data', sanitize_text_field($_POST['pricing_table_data']));
        echo '<div class="updated"><p>Pricing table updated successfully!</p></div>';
    }

    // Retrieve existing table data
    $pricing_table_data = get_option('child_theme_pricing_table_data', '{}');

    ?>
    <div class="wrap">
        <h1>Pricing Table Manager</h1>
        <form method="POST">
            <textarea name="pricing_table_data" rows="15" style="width: 100%;"><?php echo esc_textarea($pricing_table_data); ?></textarea>
            <p>Enter the JSON structure for the pricing table.</p>
            <button type="submit" class="button button-primary">Save Table</button>
        </form>
    </div>
    <?php
}

// Enqueue the existing product_attributes.js and localize the data
add_action('wp_enqueue_scripts', 'child_theme_pricing_table_enqueue_script');

function child_theme_pricing_table_enqueue_script() {
    wp_enqueue_script(
        'product-attributes-script',
        get_stylesheet_directory_uri() . '/js/product_attributes.js',
        [],
        '1.0',
        true
    );

    $pricing_table_data = get_option('child_theme_pricing_table_data', '{}');
    wp_localize_script(
        'product-attributes-script',
        'pricingTableData',
        json_decode($pricing_table_data, true)
    );
}
