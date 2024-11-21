jQuery(function ($) {
  $("form.woocommerce-checkout").on(
    "click",
    'button[name="apply_coupon"]',
    function (e) {
      e.preventDefault();

      var $button = $(this);
      var coupon_code = $("input#coupon_code").val();

      if (!coupon_code) {
        console.log("No coupon code entered.");
        return;
      }

      // Show a loader and disable the button
      $("form.woocommerce-checkout").block({
        message: null,
        overlayCSS: {
          background: "#fff",
          opacity: 0.6,
        },
      });

      $.ajax({
        type: "POST",
        url: customCouponAjax.ajax_url,
        data: {
          action: "apply_coupon",
          coupon_code: coupon_code,
          nonce: customCouponAjax.nonce,
        },
        success: function (response) {
          if (response.success) {
            console.log("Coupon applied successfully.");
            $("body").trigger("update_checkout");
          } else if (response.data && response.data.message) {
            console.log("Error applying coupon: ", response.data.message);
          } else {
            console.log("An unknown error occurred while applying the coupon.");
          }

          // Unblock the form
          $("form.woocommerce-checkout").unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log("AJAX error: " + textStatus, errorThrown);
          $("form.woocommerce-checkout").unblock();
        },
      });
    }
  );
});
