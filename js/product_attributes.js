jQuery(document).ready(function ($) {
  //====================================//
  //  >>   Get Product Attributes from product page
  //====================================//

  const pricingTable = {
    30: { 24: 111, 30: 130, 36: 147, 42: 157, 48: 170, 54: 185, 60: 216, 66: 229, 72: 246, 78: 271 },
    36: { 24: 118, 30: 137, 36: 157, 42: 168, 48: 180, 54: 202, 60: 229, 66: 245, 72: 271, 78: 298 },
    42: { 24: 128, 30: 149, 36: 166, 42: 179, 48: 199, 54: 217, 60: 245, 66: 266, 72: 291, 78: 320 },
    48: { 24: 134, 30: 158, 36: 177, 42: 190, 48: 210, 54: 231, 60: 261, 66: 285, 72: 313, 78: 344 },
    54: { 24: 147, 30: 172, 36: 194, 42: 213, 48: 231, 54: 255, 60: 291, 66: 313, 72: 337, 78: 371 },
    60: { 24: 157, 30: 184, 36: 208, 42: 225, 48: 244, 54: 275, 60: 258, 66: 332, 72: 362, 78: 397 },
    66: { 24: 165, 30: 193, 36: 218, 42: 238, 48: 260, 54: 293, 60: 330, 66: 353, 72: 400, 78: 440 },
    72: { 24: 176, 30: 216, 36: 236, 42: 263, 48: 293, 54: 321, 60: 353, 66: 393, 72: 426, 78: 469 },
    78: { 24: 192, 30: 238, 36: 259, 42: 290, 48: 323, 54: 354, 60: 388, 66: 432, 72: 469, 78: 516 },
  };

  function convertFractionToDecimal(fraction) {
    if (!fraction.includes("/")) return parseFloat(fraction);
    let [whole, frac] = fraction.split(" ");
    let [numerator, denominator] = frac.split("/");
    return parseFloat(whole) + parseFloat(numerator) / parseFloat(denominator);
  }

  function getNearestKey(value, keys) {
    let nearest = keys.reduce((prev, curr) => (Math.abs(curr - value) < Math.abs(prev - value) ? curr : prev));
    return nearest;
  }

  //  >> Price update on height and width

  function updatePrice() {
    let height = $("#height").val();
    let width = $("#width").val();

    height = convertFractionToDecimal(height.split(" ")[0]);
    width = convertFractionToDecimal(width.split(" ")[0]);

    let heightKey = getNearestKey(height, Object.keys(pricingTable).map(Number));
    let widthKey = getNearestKey(width, Object.keys(pricingTable[heightKey]).map(Number));

    let price = pricingTable[heightKey][widthKey] || "Not Available";

    $('.woocommerce-Price-amount.amount bdi').text(`$${price}`);
    
    // Return the calculated price for later use
    return price;
}

function ajaxCall(price) {
    let productId = $("#productId").val(); 
    $.ajax({
        type: "POST",
        url: customPriceUpdateParams.ajax_url,
        data: {
            action: "update_product_price",
            product_id: productId,
            new_price: price,
        },
        success: function (response) {
            let newPrice = response.data.new_price;
            console.log(response.data.new_price);
            // Optionally, you can update the displayed price after the backend update
            // $('.woocommerce-Price-amount.amount bdi').text(`$${newPrice}`);
        },
    });
}

// Update the displayed price when dimensions change
$("#height, #width").on("change", function () {
    updatePrice();
});

// Send AJAX request when "Add to Cart" button is clicked
$('.single_add_to_cart_button').click(() => {
    let price = updatePrice(); // Calculate the price
    ajaxCall(price); // Send AJAX call with the calculated price
});

  


  //====================================//
  //  >>   Validate Input Function
  //====================================//

  function validateForm() {
    let isValid = true;
    let isCheckboxChecked = false;
    $(".error-message").remove();
    $('.moutn_and_others input[type="checkbox"]').each(function () {
      if ($(this).is(":checked")) {
        isCheckboxChecked = true;
      }
    });

    if (!isCheckboxChecked) {
      isValid = false;
      $(".moutn_and_others .inside_outside").after('<div class="error-message" style="color: red;">At least one checkbox must be checked.</div>');
    }
    if ($('.moutn_and_others input[name="window_name"]').val().trim() === "") {
      isValid = false;
      $('.moutn_and_others input[name="window_name"]').after('<div class="error-message" style="color: red;">Window name is required.</div>');
    }
    if ($('.moutn_and_others select[name="return_size"]').val() === "select size") {
      isValid = false;
      $('.moutn_and_others select[name="return_size"]').after('<div class="error-message" style="color: red;">Please select a valid return size.</div>');
    }
    return isValid;
  }

  //====================================//
  //  >>   Validate Input Field For Cart Button
  //====================================//

  $(".variations_form").on("submit", function (event) {
    if (!validateForm()) {
      event.preventDefault();
      alert("Please complete all required fields.");
    }
  });

  //====================================//
  //  >>   Change product Price for Atribures
  //====================================//

  $(".variations_form").on("change", 'select, input[type="radio"], input[type="checkbox"]', function () {
    var form = $(this).closest("form.variations_form");

    // Trigger WooCommerce's variation update
    form.trigger("woocommerce_variation_select_change");
    form.trigger("check_variations");
    form.trigger("woocommerce_update_variation_values");

    // Wait a moment to let WooCommerce update the variation_id
    setTimeout(function () {
      var data = form.serialize();

      $.ajax({
        type: "POST",
        url: customPriceUpdateParams.ajax_url,
        data: {
          action: "custom_update_price",
          data: data,
          nonce: customPriceUpdateParams.nonce,
        },
        success: function (response) {
          if (response.success) {
            // Update the price display
            $(".totalPrice.ginput_total").html(response.data.price_html);
            $("#total").val(response.data.price);
          } else {
            console.log("Error:", response.data);
          }
        },
        error: function (xhr, status, error) {
          console.log("AJAX Error:", error);
        },
      });
    }, 500); // Adjust timeout as necessary to ensure WooCommerce updates the form data
  });

  //====================================//
  //  >>   Add instaltion price
  //====================================//

  $("#check_zip_code").click(() => {
    setTimeout(() => {
      addInstallationPrice();
      console.log("click");
    }, 300);
  });

  function addInstallationPrice() {
    $("#zip_code_result input").on("change", function () {
      if ($(this).is(":checked")) {
        $(".button-variable-item-add-installation").click();
        $(".woocommerce-variation-price").prepend('<p class="installation_fee">Installation Fee Added</p>');
      } else {
        $(".button-variable-item-no-installation").click();
        $(".installation_fee").hide();
      }
    });
  }
});
