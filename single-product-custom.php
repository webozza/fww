<?php
/**
 * Template Name: Fitrite Measurement
 * Template Post Type: product
 */

get_header(); // Include the header

// Get the product object for the current post
$product = wc_get_product(get_the_ID());

if ($product) : 
    $currentProductID = $product->get_id();
    $productPrice = $product->get_price(); 
?>

<script>
    let currentProductID = <?= json_encode($currentProductID) ?>; // Pass the current product ID
    let productPrice = <?= json_encode($productPrice) ?>; // Pass the product price
    console.log('Current Product ID -> ' + currentProductID);
    console.log('Product Price -> $' + productPrice);
</script>


<div class="fitrite-measurement-container">
    <div class="inner-content">
        <div class="fitrite-image">
            <img src="/wp-content/uploads/2024/11/man-taking-window-measurements.jpeg" alt="Professional measuring windows" />
        </div>
        
        <div class="fitrite-content">
            <h1>Professionally Measured</h1>
            <h2>Our <span style="color: #52A37F; font-weight: bold">FIT</span>rite Program Ensures Your Blinds Fit Right The First Time!</h2>
            
            <ul>
                <li>3. Professional Measurement: A skilled installer will come to your home and measure each window accurately, ensuring a seamless fit for all your window coverings.</li>
                <li>2. Cost: We charge you $75 for the measurement and take a $10 deposit on each window you are having measured..</li>
                <li>3. Discount Code: We will email you a discount code for the amount of your deposit which you can apply at checkout.</li>
            </ul>
            
            <!-- Dynamic Cost Calculation Section -->
            <div class="cost-calculation">
                <div class="cost-row">
                    <span>Total Windows</span>
                    <div class="window-calculation">
                        <input type="text" id="window-count" name="window_count" min="1" max='99' value="1" required>
                        <div class="plus-minus-btn">
                            <div class="plus"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg></div>
                            <div class="minus"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/></svg></div>
                        </div>
                    </div>
                </div>
                <div class="cost-row">
                    <span>Deposit per Window</span>
                    <span>$10</span>
                </div>
                <div class="cost-row">
                    <span>Measurement Fee</span>
                    <span>$75</span>
                </div>
            </div>
            <div class="total-cost">
                <span>Total Cost</span>
                <span id="total-cost" class='new_price'><h3>$85</h3></span>
            </div>

            <!-- WooCommerce Add to Cart Button -->
            <form class="fitrite-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="fitrite-button">REQUEST FITrite SERVICE</button>
            </form>
        </div>
    </div>
</div>

<?php
endif; // End product check

?>

<script>

jQuery(document).ready(function($) {
    const $windowCountInput = $("#window-count");
    const $totalCostDisplay = $("#total-cost h3");

    const eachWindowCost = 10; // Cost per window
    const measurementFee = 75;

    function updateTotalCost() {
        const windowCount = parseInt($windowCountInput.val()) || 0;
        const totalCost = windowCount * eachWindowCost + measurementFee;
        $totalCostDisplay.text(`$${totalCost}`);
    }
    function restrictInputToNumbers() {
        let value = $windowCountInput.val();
        // Remove any non-digit characters
        const filteredValue = value.replace(/[^0-9]/g, '');
        
        // Update input value with filtered value
        $windowCountInput.val(filteredValue);

        // Ensure the value is within the specified range (1-99)
        if (filteredValue !== '' && (parseInt(filteredValue) < 1 || parseInt(filteredValue) > 99)) {
            $windowCountInput.val(Math.max(1, Math.min(99, parseInt(filteredValue) || 1)).toString());
        }

        // Update total cost whenever the input changes
        updateTotalCost();
    }

    function increaseCount() {
        let currentCount = parseInt($windowCountInput.val()) || 0;
        if (currentCount < 99) {
            $windowCountInput.val(currentCount + 1);
            updateTotalCost();
        }
    }

    function decreaseCount() {
        let currentCount = parseInt($windowCountInput.val()) || 0;
        if (currentCount > 1) {
            $windowCountInput.val(currentCount - 1);
            updateTotalCost();
        }
    }

    $windowCountInput.on("input", restrictInputToNumbers);

    // Add event listeners for plus and minus buttons
    $(".plus").on("click", increaseCount);
    $(".minus").on("click", decreaseCount);

    // Initialize with default value
    updateTotalCost();
});

</script>



<?php
get_footer(); // Include the footer
?>
