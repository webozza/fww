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
  let InstallationCharge = 12;
  let discount = 10;

  //====================================//
  //  >> Dom Handaler
  //====================================//

  if ($("body").hasClass("single-product")) {
    if (currentProductID == selectedProduct) {
      $(".woocommerce-variation").after(
        `<div class="new_price"><h3>$111</h3></div>`
      );
    }

    if (currentProductID == fitriteProduct) {
    }
  }

  //====================================//
  //  >> Get Product Attributes from product page
  //====================================//

  const pricingTable = {
    30: {
      24: 111,
      30: 130,
      36: 147,
      42: 157,
      48: 170,
      54: 185,
      60: 216,
      66: 229,
      72: 246,
      78: 271,
    },
    36: {
      24: 118,
      30: 137,
      36: 157,
      42: 168,
      48: 180,
      54: 202,
      60: 229,
      66: 245,
      72: 271,
      78: 298,
    },
    42: {
      24: 128,
      30: 149,
      36: 166,
      42: 179,
      48: 199,
      54: 217,
      60: 245,
      66: 266,
      72: 291,
      78: 320,
    },
    48: {
      24: 134,
      30: 158,
      36: 177,
      42: 190,
      48: 210,
      54: 231,
      60: 261,
      66: 285,
      72: 313,
      78: 344,
    },
    54: {
      24: 147,
      30: 172,
      36: 194,
      42: 213,
      48: 231,
      54: 255,
      60: 291,
      66: 313,
      72: 337,
      78: 371,
    },
    60: {
      24: 157,
      30: 184,
      36: 208,
      42: 225,
      48: 244,
      54: 275,
      60: 258,
      66: 332,
      72: 362,
      78: 397,
    },
    66: {
      24: 165,
      30: 193,
      36: 218,
      42: 238,
      48: 260,
      54: 293,
      60: 330,
      66: 353,
      72: 400,
      78: 440,
    },
    72: {
      24: 176,
      30: 216,
      36: 236,
      42: 263,
      48: 293,
      54: 321,
      60: 353,
      66: 393,
      72: 426,
      78: 469,
    },
    78: {
      24: 192,
      30: 238,
      36: 259,
      42: 290,
      48: 323,
      54: 354,
      60: 388,
      66: 432,
      72: 469,
      78: 516,
    },
  };

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

  function updatePrice() {
    let height = $("#height").val();
    let width = $("#width").val();

    height = convertFractionToDecimal(height.split(" ")[0]);
    width = convertFractionToDecimal(width.split(" ")[0]);

    let heightKey = getNearestKey(
      height,
      Object.keys(pricingTable).map(Number)
    );
    let widthKey = getNearestKey(
      width,
      Object.keys(pricingTable[heightKey]).map(Number)
    );

    let price = pricingTable[heightKey][widthKey] || "Not Available";
    let priceIncludingInstallation = price + InstallationCharge;

    $(".new_price h3").text(`$${priceIncludingInstallation}`);

    console.log("price : ", price);

    // Return the calculated price for later use
    return priceIncludingInstallation;
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
  //  >>   Handle Fitrite
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
  //  >>   Count Fitrite Product
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
