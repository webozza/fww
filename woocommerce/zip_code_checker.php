<?php
function display_zip_code_checker() {
    global $post;
    $product_id = $post->ID;

    // Get the value of the ACF field 'zip_code' and 'available_zip_codes'
    $available_zip_codes = get_field('zip_code', $product_id);
    $cros_icon = get_stylesheet_directory_uri() . '/assets/cross.png';
    $tick_icon = get_stylesheet_directory_uri() . '/assets/tick.png';

    ?>
    <div class="zip-code-check">
        <p>Want us to measure for you?</p>
        <p>Enter your zip code below to see if <strong style="color:#52A37F">FIT</strong>rite<br> is available in your area</p>
        <label for="zip_code">ZIP CODE</label>
        <input type="text" id="zip_code" name="zip_code" placeholder="90210">
        <button id="check_zip_code">CHECK</button>
        <div id="zip_code_result"></div>
    </div>
    <script>
        // Pass the PHP variables to JavaScript
        let available_zip_codes = "<?php echo esc_js($available_zip_codes); ?>".split(',');
        let success_text = `<span class="zip-available"><img src="<?= $tick_icon ?>"> YES, get started <a href="/fitrite">click here</a>`;
        let unavailable_text = `<span class="zip-not-available"><img src="<?= $cros_icon ?>"> Unfortunately not at this time</span>`;

        // Product page script
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('check_zip_code').addEventListener('click', function() {
                var zipCode = document.getElementById('zip_code').value.trim();
                var resultDiv = document.getElementById('zip_code_result');
                var isValid = available_zip_codes.includes(zipCode);
                
                resultDiv.innerHTML = isValid ? success_text : unavailable_text;

                // Store the validation status in localStorage
                localStorage.setItem('zip_code_status', JSON.stringify({
                    zipCode: zipCode,
                    isValid: isValid
                }));

                // Emit a custom event for tracking
                document.dispatchEvent(new CustomEvent('zipCodeChecked', {
                    detail: { zipCode, isValid }
                }));
            });
        });

    </script>
    <?php
}
