<?php
function display_zip_code_checker() {
    $product_id = 1058;

    // Get the value of the ACF field 'zip_code' and 'available_zip_codes'
    $available_zip_codes = get_field('zip_code', $product_id);
    $cros_icon = get_stylesheet_directory_uri() . '/assets/cross.png';
    $tick_icon = get_stylesheet_directory_uri() . '/assets/tick.png';

    ?>
    <div class="zip-code-check">
        <p>Is Measuring available in my area?</p>
        <label for="zip_code">ZIP CODE</label>
        <input type="text" id="zip_code" name="zip_code" placeholder="90210">
        <button id="check_zip_code">CHECK</button>
        <div id="zip_code_result"></div>
    </div>
    <script>
        // Pass the PHP variables to JavaScript
        let available_zip_codes = "<?php echo esc_js($available_zip_codes); ?>".split(',');
         let sucess_text =  `<span class="zip-available"><img src="<?= $tick_icon?>"> YES, get started <a href="/product/2-cordless-faux-wood-blinds-new?measuring=available">Measurement Service<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg></a>`;
         let unavailable_text = `<span class="zip-not-available"><img src="<?= $cros_icon?>"> Unfortunately not at this time</span>`

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('check_zip_code').addEventListener('click', function() {
                var zipCode = document.getElementById('zip_code').value;
                var resultDiv = document.getElementById('zip_code_result');
                
                if (available_zip_codes.includes(zipCode)) {
                    resultDiv.innerHTML = sucess_text;
                } else {
                    resultDiv.innerHTML = unavailable_text;
                }
            });
        });
    </script>
    <?php
}
