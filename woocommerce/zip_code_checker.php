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
        <!-- <p>Enter your zip code below to see if <strong style="color:#52A37F">FIT</strong>rite<br> is available in your area</p> -->
        <p>Enter your Zip code below to see if installation is available in your area</p>
        <label for="zip_code">ZIP CODE</label>
        <input type="text" id="zip_code" name="zip_code" placeholder="90210">
        <button id="check_zip_code">CHECK</button>
        <div id="zip_code_result"></div>
    </div>
    <script>
        // Pass the PHP variables to JavaScript
        let available_zip_codes = "<?php echo esc_js($available_zip_codes); ?>".split(',');
        //  let sucess_text =  `<span class="zip-available"><img src="<?= $tick_icon?>"> YES, get started <a href="/measuring">click here</a>`;
        let sucess_text =  `<span class="zip-available"><img src="<?= $tick_icon?>"> YES, get started <a href="/fitrite">click here</a>`;
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
