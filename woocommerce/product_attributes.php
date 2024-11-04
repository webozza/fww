<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
global $wpdb;
$product_ID = get_the_ID();
$product_price = $product->get_price();

?>
<script>
	let currentProductID = <?= json_encode($product_ID) ?>;
	let productPrice = <?= json_encode($product_price) ?>;
	console.log('Current Product ID -> ' + currentProductID);
	console.log('Product Price -> $' + productPrice);
</script>


<input type="hidden" id="productId" value="<?php echo get_the_ID(); ?>">

<div class="product_attribute">
	<div class="height_width">
		<div class="width">
			<label>Width</label>
			<select data-val="true" id="width" name="HidthInches" class="hwFieldSelect">
			</select>
		</div>
		<div class="height">
			<label>Height</label>
			<select data-val="true" data-val-number="The field height must be a number." data-val-required="The height field is required." id="height" name="height" class="hwFieldSelect">
			</select>
		</div>
	</div>
	<div class="moutn_and_others">
		<div class="mount">
			<div class="inside_outside">
					<div>
						<label for="inside_mount">Inside Mount</label>
						<input name="inside_mount" type="checkbox" require>
					</div>
					<div>
						<label for="outside_mount">Outside Mount</label>
						<input name="outside_mount" type="checkbox">
					</div>
			</div>
			<div class="window">
				<div>
					<label for="window_name">Window Name</label>
					<input name="window_name" type="text" placeholder="Living Room Left">
				</div>
				<div>
					<label for="need_returns">Does your blind need returns</label>
					<select id="return_size" name="return_size">
						<option value="select size">select size</option>
						<option value="0.5">1/2"</option>
						<option value="0.625">5/8"</option>
						<option value="0.75">3/4"</option>
						<option value="0.875">7/8"</option>
						<option value="1">1"</option>
						<option value="1.125">1 1/8"</option>
						<option value="1.25">1 1/4"</option>
						<option value="1.375">1 3/8"</option>
						<option value="1.5">1 1/2"</option>
						<option value="1.625">1 5/8"</option>
						<option value="1.75">1 3/4"</option>
						<option value="1.875">1 7/8"</option>
						<option value="2">2"</option>
						<option value="2.125">2 1/8"</option>
						<option value="2.25">2 1/4"</option>
						<option value="2.375">2 3/8"</option>
						<option value="2.5">2 1/2"</option>
						<option value="2.625">2 5/8"</option>
						<option value="2.75">2 3/4"</option>
						<option value="2.875">2 7/8"</option>
						<option value="3">3"</option>
						<option value="3.125">3 1/8"</option>
						<option value="3.25">3 1/4"</option>
						<option value="3.375">3 3/8"</option>
						<option value="3.5">3 1/2"</option>
					</select>
				</div>

			</div>

		</div>
	</div>
</div>

<script>
    // Create option for select element
	function renderHeightWidth(id,minVal,maxVal){
		const widthSelect = document.getElementById(id);
		// Loop through each inch value
		for (let i = minVal; i <= maxVal; i++) {
			// Create the main whole inch option
			const mainOption = document.createElement("option");
			mainOption.value = i;
			mainOption.textContent = i;
			widthSelect.appendChild(mainOption);

			// Array of fractions to add
			const fractions = ["1/8", "1/4", "3/8", "1/2", "5/8", "3/4", "7/8"];
			fractions.forEach(fraction => {
				const option = document.createElement("option");
				option.value = `${i} (${fraction})`;
				option.textContent = `${i} ${fraction}`;
				widthSelect.appendChild(option);
			});
		}
	}
	renderHeightWidth('width',24,78)
	renderHeightWidth('height',30,78)

</script>

<?php
add_action('woocommerce_before_add_to_cart_button', 'custom_hidden_product_field', 11);

function custom_hidden_product_field() {
    global $product;
    $product_price = $product->get_price();
}
