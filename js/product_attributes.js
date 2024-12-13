jQuery(document).ready(function ($) {
  let hostName = window.location.host;
  selectedProduct = 465;

  let fitriteProduct;

  if (hostName == "fauxwoodwarehouse.com") {
    fitriteProduct = 1056;
  } else {
    fitriteProduct = 1058;
  }

  let measuringFee = 75;
  let InstallationCharge = 15;
  let discount = 10;

  //====================================//
  //  >> Dom Handaler
  //====================================//

  if ($("body").hasClass("single-product")) {
    if (currentProductID == selectedProduct) {
      $(".woocommerce-variation").after(
        `<div class="new_price"><h3>$${pricingTable["30"]["24"]}</h3></div>`
      );
    }

    if (currentProductID == fitriteProduct) {
    }
  }

  //====================================//
  //  >> Get Product Attributes from product page
  //====================================//

  if ($("body").hasClass("postid-465")) {
    console.log(pricingTable);
  }
  // const pricingTable = {
  //   30: {
  //     24: 111,
  //     28: 130,
  //     34: 147,
  //     40: 157,
  //     46: 170,
  //     52: 185,
  //     58: 216,
  //     64: 229,
  //     70: 246,
  //     76: 271,
  //   },
  //   34: {
  //     24: 118,
  //     28: 137,
  //     34: 157,
  //     40: 168,
  //     44: 180,
  //     52: 202,
  //     58: 229,
  //     64: 245,
  //     70: 271,
  //     76: 298,
  //   },
  //   40: {
  //     24: 128,
  //     28: 149,
  //     34: 166,
  //     40: 179,
  //     46: 199,
  //     52: 217,
  //     58: 245,
  //     64: 266,
  //     70: 291,
  //     76: 320,
  //   },
  //   46: {
  //     24: 134,
  //     28: 158,
  //     34: 177,
  //     40: 190,
  //     46: 210,
  //     52: 231,
  //     58: 261,
  //     64: 285,
  //     70: 313,
  //     76: 344,
  //   },
  //   52: {
  //     24: 147,
  //     28: 172,
  //     34: 194,
  //     40: 213,
  //     46: 231,
  //     52: 255,
  //     58: 291,
  //     64: 313,
  //     70: 337,
  //     76: 371,
  //   },
  //   58: {
  //     24: 157,
  //     28: 184,
  //     34: 208,
  //     40: 225,
  //     46: 244,
  //     52: 275,
  //     58: 291,
  //     64: 332,
  //     70: 362,
  //     76: 397,
  //   },
  //   64: {
  //     24: 165,
  //     28: 193,
  //     34: 218,
  //     40: 238,
  //     46: 260,
  //     52: 293,
  //     58: 330,
  //     64: 353,
  //     70: 400,
  //     76: 440,
  //   },
  //   70: {
  //     24: 176,
  //     28: 216,
  //     34: 236,
  //     40: 263,
  //     46: 293,
  //     52: 321,
  //     58: 353,
  //     64: 393,
  //     70: 426,
  //     76: 469,
  //   },
  //   76: {
  //     24: 192,
  //     28: 238,
  //     34: 259,
  //     40: 290,
  //     46: 323,
  //     52: 354,
  //     58: 388,
  //     64: 432,
  //     70: 469,
  //     76: 516,
  //   },
  // };

  function convertFractionToDecimal(fraction) {
    if (!fraction.includes("/")) return parseFloat(fraction);
    let [whole, frac] = fraction.split(" ");
    let [numerator, denominator] = frac.split("/");
    return parseFloat(whole) + parseFloat(numerator) / parseFloat(denominator);
  }

  function getNearestKey(value, keys) {
    let nearest = keys.reduce((prev, curr) =>
      Math.abs(curr - value) < Math.abs(prev - value) ? curr : prev
    );
    return nearest;
  }

  //====================================//
  //  >> Price update on height and width
  //====================================//

  let product759 = $("body").hasClass("postid-465");
  if (product759) {
    let currentPrice = $(".new_price h3").text().replaceAll("$", "").trim();
    $(".new_price h3").text(`$${Number(currentPrice) + 15}`);
  }

  // function updatePrice() {
  //   let height = $("#height").val();
  //   let width = $("#width").val();

  //   // Convert fractions to decimals for calculations
  //   height = convertFractionToDecimal(height.split(" ")[0]);
  //   width = convertFractionToDecimal(width.split(" ")[0]);

  //   // Predefined height and width points
  //   const predefinedHeights = [30, 36, 42, 48, 54, 60, 66, 72, 78];
  //   const predefinedWidths = [24, 30, 36, 42, 48, 54, 60, 66, 72, 78];

  //   // Helper function to find the closest valid range key
  //   function getValidRange(value, predefinedKeys) {
  //     let currentRange = predefinedKeys[0];
  //     for (let i = 1; i < predefinedKeys.length; i++) {
  //       if (value < predefinedKeys[i]) {
  //         return currentRange;
  //       }
  //       currentRange = predefinedKeys[i];
  //     }
  //     return currentRange;
  //   }

  //   // Get the valid height and width keys based on predefined points
  //   let validHeightKey = getValidRange(height, predefinedHeights);
  //   let validWidthKey = getValidRange(width, predefinedWidths);

  //   // Ensure the keys exist in the pricing table
  //   if (
  //     !pricingTable[validHeightKey] ||
  //     !pricingTable[validHeightKey][validWidthKey]
  //   ) {
  //     $(".new_price h3").text("Not Available");
  //     console.error("Price not available for given dimensions");
  //     return;
  //   }

  //   // Calculate the price from the table
  //   let basePrice = pricingTable[validHeightKey][validWidthKey];

  //   // Update the price display
  //   $(".new_price h3").text(`$${Number(basePrice) + InstallationCharge}`);

  //   console.log("Calculated price:", basePrice);

  //   // Return the calculated price for later use
  //   return Number(basePrice) + InstallationCharge;
  // }

  function updatePrice() {
    let height = $("#height").val();
    let width = $("#width").val();

    // Helper function to convert fractions to decimals
    function convertFractionToDecimal(value) {
      if (!value || typeof value !== "string") {
        console.error("Invalid input for conversion:", value);
        return 0;
      }

      try {
        // Remove parentheses if they exist around the fraction
        value = value.replace(/[()]/g, "").trim();

        // Check if the input includes a fraction
        if (value.includes("/")) {
          const parts = value.split(" ");
          let wholePart = 0;
          let fractionPart = "0/1";

          if (parts.length === 2) {
            wholePart = parseFloat(parts[0]);
            fractionPart = parts[1];
          } else if (parts.length === 1) {
            fractionPart = parts[0];
          }

          // Check if the fraction is valid
          if (fractionPart.includes("/")) {
            const [numerator, denominator] = fractionPart
              .split("/")
              .map(Number);

            if (isNaN(numerator) || isNaN(denominator) || denominator === 0) {
              throw new Error(`Invalid fraction: ${fractionPart}`);
            }

            return wholePart + numerator / denominator;
          } else {
            throw new Error(`Invalid fraction format: ${fractionPart}`);
          }
        }

        // If no fraction, try parsing as a float
        const parsedValue = parseFloat(value);
        if (isNaN(parsedValue)) {
          throw new Error(`Invalid numeric value: ${value}`);
        }

        return parsedValue;
      } catch (error) {
        console.error(
          `Error converting value to decimal: ${value} - ${error.message}`
        );
        return 0;
      }
    }

    // Convert height and width to decimals
    width = convertFractionToDecimal(width);
    height = convertFractionToDecimal(height);

    console.log("Converted Width:", width);
    console.log("Converted Height:", height);

    // Predefined height and width points
    const predefinedHeights = [30, 36, 42, 48, 54, 60, 66, 72, 78];
    const predefinedWidths = [24, 30, 36, 42, 48, 54, 60, 66, 72, 78];

    // Helper function to find the next valid predefined range
    function getNextRange(value, predefinedKeys) {
      for (let i = 0; i < predefinedKeys.length; i++) {
        if (value <= predefinedKeys[i]) {
          console.log(
            `Value ${value} is being rounded up to ${predefinedKeys[i]}`
          );
          return predefinedKeys[i];
        }
      }
      // If value exceeds all predefined ranges, return the last range
      console.log(
        `Value ${value} exceeds all ranges, using ${
          predefinedKeys[predefinedKeys.length - 1]
        }`
      );
      return predefinedKeys[predefinedKeys.length - 1];
    }

    // Get the next valid height and width keys
    const validWidthKey = getNextRange(width, predefinedWidths);
    const validHeightKey = getNextRange(height, predefinedHeights);

    console.log("Rounded Up Width Key:", validWidthKey);
    console.log("Rounded Up Height Key:", validHeightKey);

    // Ensure the keys exist in the pricing table
    if (
      !pricingTable[validHeightKey] ||
      !pricingTable[validHeightKey][validWidthKey]
    ) {
      $(".new_price h3").text("Not Available");
      console.error("Price not available for given dimensions");
      return;
    }

    // Calculate the price from the table
    const basePrice = pricingTable[validHeightKey][validWidthKey];

    // Update the price display
    $(".new_price h3").text(`$${Number(basePrice) + InstallationCharge}`);

    console.log("Calculated price:", basePrice);

    // Return the calculated price for later use
    return Number(basePrice) + InstallationCharge;
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  // Helper function to convert fractional strings to decimals
  function convertFractionToDecimal(fraction) {
    if (fraction.includes("/")) {
      let [numerator, denominator] = fraction.split("/").map(Number);
      return numerator / denominator;
    }
    return parseFloat(fraction);
  }

  //====================================//
  //  >> Price update Ajax
  //====================================//

  function ajaxCall(
    quantity,
    customQantity,
    productId,
    price,
    measuring,
    width,
    height,
    mount,
    window_name,
    blind,
    selectedColor
  ) {
    $.ajax({
      url: customPriceUpdateParams.ajax_url,
      type: "POST",
      data: {
        action: "add_custom_product_to_cart",
        product_id: productId,
        quantity: quantity,
        custom_quantity: customQantity,
        custom_price: price,
        measuring_fee: measuring,
        width: width,
        height: height,
        mount: mount,
        window_name: window_name,
        blind: blind,
        color: selectedColor,
      },
      success: function (response) {
        // Optionally, redirect to the cart page
        window.location.href = "/cart";
      },
    });
  }

  //====================================//
  //  >> Price update when Add to Cart" button is clicked
  //====================================//

  function sendFormData() {
    var selectedElement = $('li[aria-checked="true"]');
    var selectedColor;
    if (selectedElement.length) {
      selectedColor = selectedElement.data("wvstooltip");
    } else {
      selectedColor = "No color selected";
    }

    var quantity = $('input[name="quantity"]').val();
    let customQantity = $('input[name="custom_quantity"]').val();
    price = Number($(".new_price h3").text().replace("$", ""));
    let measuring = measuringFee;
    let productId = $("#productId").val();
    let width = $(".product_attribute .height_width .width select")
      .val()
      .replace(/[()]/g, "");
    let height = $(".product_attribute .height_width .height select")
      .val()
      .replace(/[()]/g, "");
    let mount = $('[name="inside_mount"]').is(":checked")
      ? "Inside Mount"
      : "Outside Mount";
    let window_name = $('[name="window_name"]').val();
    let blind = $("#return_size").val();
    if (blind == "select size") {
      blind = "No Returns";
    }

    ajaxCall(
      quantity,
      customQantity,
      productId,
      price,
      measuring,
      width,
      height,
      mount,
      window_name,
      blind,
      selectedColor
    );
  }

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
      $(".moutn_and_others .inside_outside").after(
        '<div class="error-message" style="color: red;">At least one checkbox must be checked.</div>'
      );
    }
    if ($('.moutn_and_others input[name="window_name"]').val().trim() === "") {
      isValid = false;
      $('.moutn_and_others input[name="window_name"]').after(
        '<div class="error-message" style="color: red;">Window name is required.</div>'
      );
    }
    // if ($('.moutn_and_others select[name="return_size"]').val() === "select size") {
    //   isValid = false;
    //   $('.moutn_and_others select[name="return_size"]').after('<div class="error-message" style="color: red;">Please select a valid return size.</div>');
    // }
    return isValid;
  }

  //====================================//
  //  >>   Validate Input Field For Cart Button
  //====================================//

  $(".variations_form").on("submit", function (event) {
    if (!validateForm()) {
      event.preventDefault();
      alert("Please complete all required fields.");
    } else {
      event.preventDefault();
      sendFormData();
    }
  });

  //====================================//
  //  >> Update the displayed price when dimensions change
  //====================================//

  $("#height, #width").on("change", function () {
    if (currentProductID == selectedProduct) {
      updatePrice();
    }
  });

  //====================================//
  //  >>   Add instaltion price
  //====================================//

  $("#check_zip_code").click(() => {
    setTimeout(() => {
      addInstallationPrice();
    }, 300);
  });

  function addInstallationPrice() {
    $("#zip_code_result input").on("change", function () {
      if ($(this).is(":checked")) {
        $(".button-variable-item-add-installation").click();
        $(".woocommerce-variation-price").prepend(
          '<p class="installation_fee">Installation Fee Added</p>'
        );
      } else {
        $(".button-variable-item-no-installation").click();
        $(".installation_fee").hide();
      }
    });
  }

  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("measuring") === "available") {
    $(".new_price").append(
      '<div class="measuring_price">Including $75 (For Measuring)</div>'
    );
  }

  //====================================//
  //  >>   Handle FITrite
  //====================================//

  //====================================//
  //  >>   Send firtire product data Ajax
  //====================================//

  function sendFormDataFitrite() {
    let productId = currentProductID;
    let quantity = 1;
    let customQantity = $("#window-count").val();
    let price = Number($(".new_price h3").text().replace("$", ""));
    let measuring = measuringFee;

    $.ajax({
      url: customPriceUpdateParams.ajax_url,
      type: "POST",
      data: {
        action: "add_custom_product_to_cart",
        product_id: productId,
        quantity: quantity,
        custom_quantity: customQantity,
        custom_price: price,
        measuring_fee: measuring,
      },
      success: function (response) {
        window.location.href = "/cart";
      },
    });
  }

  $(".fitrite-form").on("submit", function (event) {
    event.preventDefault();
    sendFormDataFitrite();
  });

  //====================================//
  //  >>   Coupon code generate
  //====================================//

  function discountPrice() {
    let fitRiteProductCount = 0;
    let windowCount = 0;

    $(".woocommerce-cart-form tbody tr").each(function () {
      let productID = $(this).data("product_id");
      if (productID === fitriteProduct) {
        windowCount = Number(
          $(this).find("dd.variation-Numberofwindow p").text()
        );
        fitRiteProductCount++;
      }
    });
    let discountPrice = windowCount * discount;
    return discountPrice;
  }

  //====================================//
  //  >>   Count FITrite Product
  //====================================//

  function fitriteCount() {
    let fitRiteProductCount = 0;
    $(".woocommerce-cart-form tbody tr").each(function () {
      let productID = $(this).data("product_id");
      if (productID === fitriteProduct) {
        windowCount = Number(
          $(this).find("dd.variation-Numberofwindow p").text()
        );
        fitRiteProductCount++;
      }
    });
    return fitRiteProductCount;
  }

  //====================================//
  //  >>   Remove Coupon Code
  //====================================//

  function removeDiscount() {
    let fitRiteProductCount = fitriteCount();
    if (fitRiteProductCount == 0) {
      setTimeout(() => {
        $(".woocommerce-remove-coupon").click();
      }, 500);
    }
    console.log("fitRiteProductCount", fitRiteProductCount);
  }
  //====================================//
  //  >>   handle Events
  //====================================//

  $(document).on("click", ".product-remove", function () {
    setTimeout(() => {
      removeDiscount();
      location.reload();
    }, 500);
    console.log("Product removed");
  });

  //====================================//
  //  >>   Apply Couponcode
  //====================================//

  $('[name="apply_coupon"]').click(function (e) {
    e.preventDefault();

    const couponCode = $('[name="coupon_code"]').val();

    $.ajax({
      url: customPriceUpdateParams.ajax_url,
      type: "POST",
      data: {
        action: "apply_custom_discount",
        coupon_code: couponCode,
        nonce: customPriceUpdateParams.nonce,
      },
      success: function (response) {
        if (response.success) {
          location.reload();
          console.log(response);
        } else {
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX Error:", error);
      },
    });
  });
});
