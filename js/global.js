jQuery(document).ready(function ($) {
  $(".right-nav-menu").eq(0).find(".navbar-nav").append($("#menu-item-345"));

  if ($("body").hasClass("single-product")) {
    $('input[name="inside_mount"]').on("change", function () {
      if ($(this).is(":checked")) {
        $('input[name="outside_mount"]').prop("checked", false);
      }
    });

    $('input[name="outside_mount"]').on("change", function () {
      if ($(this).is(":checked")) {
        $('input[name="inside_mount"]').prop("checked", false);
      }
    });
  }

  let isCart = $("body.woocommerce-cart").length;
  let isCheckout = $("body.woocommerce-checkout").length;

  let noShippingForMeasurement = () => {
    let measurementAddedToCart = $(
      '.mini_cart_item a[data-product_id="1056"]'
    ).length;

    if (measurementAddedToCart) {
      $("tr.woocommerce-shipping-totals.shipping").hide();
    }
  };

  if (isCart || isCheckout) {
    noShippingForMeasurement();
  }
});
