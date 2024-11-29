<?php
function display_zip_code_checker() {

    $hostName = $_SERVER['HTTP_HOST'];
    $product_id = 1056;

    // Get the value of the ACF field 'zip_code' and 'available_zip_codes'
    $available_zip_codes = get_field('zip_code', $product_id);
    $cros_icon = get_stylesheet_directory_uri() . '/assets/cross.png';
    $tick_icon = get_stylesheet_directory_uri() . '/assets/tick.png';
    // $saved_zip_code = get_field('user_zip_code', 'user_'.get_current_user_ID());

    ?>
    <div class="zip-code-check">
        <p>Want us to measure for you?</p>
        <p>Enter your zip code below to see if <strong style="color:#52A37F">FIT</strong>rite<br> is available in your area</p>
        <!-- <p>Enter your Zip code below to see if installation<br> is available in your area</p> -->
        <label for="zip_code">ZIP CODE</label>
        <input type="text" id="zip_code" name="zip_code" placeholder="90210" value="">
        <button id="check_zip_code">CHECK</button>
        <div id="zip_code_result"></div>
    </div>
    <script>
        // Pass the PHP variables to JavaScript
        let availableZipCodes = "<?php echo esc_js($available_zip_codes); ?>".split(',');
        let successText = `<span class="zip-available"><img src="<?= $tick_icon ?>"> YES, get started <a href="/product/2-cordless-faux-wood-blinds-new?measuring=available">click here</a>`;
        let unavailableText = `<span class="zip-not-available"><img src="<?= $cros_icon ?>"> Unfortunately not at this time</span>`;

        jQuery(document).ready(async function($) {

            const zipCodeStatus = localStorage.getItem('zip_code_status');
            if(zipCodeStatus !== null) {
                const parsedData = JSON.parse(zipCodeStatus);
                const storedZipCode = parsedData.zipCode;
                
                if(storedZipCode !== "") {
                    $('#zip_code').val(storedZipCode);
                }
            }

            let zipCode = $('#zip_code').val().trim();

            // Auto-trigger zip code check if input already has a value
            if (zipCode !== "") {
                setTimeout(() => {
                    $('#check_zip_code').trigger('click');
                }, 600);
            }

            $('#check_zip_code').click(function() {
                zipCode = $('#zip_code').val().trim();
                const isValid = availableZipCodes.includes(zipCode);
                const resultDiv = $('#zip_code_result');
                const checkboxContainer = $('.installation-checkbox-container');

                resultDiv.html(isValid ? successText : unavailableText);
                checkboxContainer.toggle(isValid);

                localStorage.setItem('zip_code_status', JSON.stringify({ zipCode, isValid }));
                $(document).trigger('zipCodeChecked', { zipCode, isValid });
            });
        });
    </script>
    <?php
}
