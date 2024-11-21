jQuery(function ($) {
  $("form.woocommerce-checkout").on(
    "click",
    'button[name="apply_coupon"]',
    function (e) {
      e.preventDefault();

      var coupon_code = $("input#coupon_code").val();

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
            // Trigger WooCommerce to update the checkout totals
            $("body").trigger("update_checkout");
          }
        },
      });
    }
  );
});
