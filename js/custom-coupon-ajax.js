jQuery(function ($) {
  let isCheckout = $("body").hasClass("woocommerce-checkout");

  let creditCardFix = () => {
    $(".sq-card-iframe-container iframe").attr(
      "style",
      "left:unset; position:relative"
    );
  };

  if (isCheckout) {
    setTimeout(() => {
      creditCardFix();
    }, 3000);
  }

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
  });
});
