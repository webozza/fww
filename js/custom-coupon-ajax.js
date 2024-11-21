jQuery(function ($) {
  let addCouponArea = async () => {
    var customCouponBox = `
  <div id="custom-coupon-box" style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
      <label for="custom_coupon_code" style="display: block; font-weight: bold; margin-bottom: 10px;">Enter your coupon code:</label>
      <input type="text" id="custom_coupon_code" style="width: calc(100% - 110px); padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;" />
      <button id="apply_custom_coupon" style="padding: 10px 20px; background-color: #52A37F; color: #fff; border: none; border-radius: 4px; text-transform: uppercase; cursor: pointer;">Apply</button>
      <div id="coupon-loading" style="display: none; margin-top: 10px; font-size: 14px; color: #52A37F;">Applying coupon...</div>
  </div>
`;

    // Append the custom coupon box after the order total
    $(".woocommerce-checkout-review-order-table").after(customCouponBox);
  };

  let runCouponFunc = async () => {
    await addCouponArea();

    setTimeout(() => {
      // Apply coupon functionality
      $("#apply_custom_coupon").on("click", function (e) {
        e.preventDefault();

        var couponCode = $("#custom_coupon_code").val();

        if (!couponCode) {
          alert("Please enter a coupon code.");
          return;
        }

        // Show the loader
        $("#coupon-loading").show();

        // Enter the code into WooCommerce's hidden field and trigger the apply action
        $("#coupon_code").val(couponCode);
        $('button[name="apply_coupon"]').trigger("click");

        // Hide the loader once the coupon is processed
        $(document.body).on("updated_checkout", function () {
          $("#coupon-loading").hide();
        });

        // Handle WooCommerce notices
        $(document.body).on("apply_coupon_error", function (event, message) {
          alert(message); // Display WooCommerce error messages in an alert
          $("#coupon-loading").hide();
        });
      });
    }, 600);
  };

  setTimeout(() => {
    runCouponFunc();
  }, 2400);
});
