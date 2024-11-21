jQuery(function ($) {
  $("form.woocommerce-checkout").on(
    "click",
    'button[name="apply_coupon"]',
    function (e) {
      e.preventDefault();

      var $button = $(this);
      var coupon_code = $("input#coupon_code").val();

      if (!coupon_code) {
        alert("Please enter a coupon code.");
        return;
      }

      // Block the checkout form to show a loader
      $("form.woocommerce-checkout").block({
        message: null,
        overlayCSS: {
          background: "#fff",
          opacity: 0.6,
        },
      });

      $.ajax({
        type: "POST",
        url: "/wp-admin/admin-ajax.php",
        data: {
          action: "apply_coupon",
          coupon_code: coupon_code,
          nonce: customCouponAjax.nonce,
        },
        success: function (response) {
          if (response.success) {
            $("body").trigger("update_checkout"); // Update checkout totals dynamically
          } else if (response.data && response.data.message) {
            alert(response.data.message);
          }

          // Unblock the checkout form
          $("form.woocommerce-checkout").unblock();
        },
        error: function () {
          alert("An error occurred. Please try again.");
          $("form.woocommerce-checkout").unblock();
        },
      });
    }
  );
});
