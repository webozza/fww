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
  let isProduct = $("body.single-product").length;
  let isSupport = $("body").hasClass("page-id-882");

  let noShippingForMeasurement = () => {
    let measurementAddedToCart = $(
      '.mini_cart_item a[data-product_id="1056"]'
    ).length;

    if (measurementAddedToCart) {
      $("tr.woocommerce-shipping-totals.shipping").hide();
      $(".woocommerce-shipping-fields").hide();

      if (isCheckout) {
        setTimeout(() => {
          $("tr.woocommerce-shipping-totals.shipping").hide();
        }, 1200);
      }
    }
  };

  let cartItems3 = () => {
    let itemsOnCartCount = $(".woocommerce-cart-form__cart-item").length;

    if (itemsOnCartCount >= 3) {
      $("tr.woocommerce-shipping-totals.shipping > td").hide();
      $("tr.woocommerce-shipping-totals.shipping > th").text(`FREE SHIPPING`);
    }
  };

  let removeFractions78 = () => {
    $(
      ".product_attribute .width select, .product_attribute .height select"
    ).select2();
    $(
      '.product_attribute .width select option[value="78 (1/8)"], .product_attribute .height select option[value="78 (1/8)"], .product_attribute .width select option[value="78 (1/4)"], .product_attribute .height select option[value="78 (1/4)"], .product_attribute .width select option[value="78 (3/8)"], .product_attribute .height select option[value="78 (3/8)"], .product_attribute .width select option[value="78 (1/2)"], .product_attribute .height select option[value="78 (1/2)"], .product_attribute .width select option[value="78 (5/8)"], .product_attribute .height select option[value="78 (5/8)"], .product_attribute .width select option[value="78 (3/4)"], .product_attribute .height select option[value="78 (3/4)"], .product_attribute .width select option[value="78 (7/8)"], .product_attribute .height select option[value="78 (7/8)"]'
    ).remove();
    $(
      ".product_attribute .width select, .product_attribute .height select"
    ).trigger("change.select2");
  };

  let enableSelect2 = () => {
    $(".product_attribute select").select2();
  };

  let alterCartTextFITrite = () => {
    if (isCart) {
      $(".product-name a").each(function () {
        if ($(this).text().trim() === "FITrite: Professional Measurement") {
          $(this).html(
            `<span style="color:#52A37F">FIT</span>rite: Professional Measurement`
          );
        }
      });
    } else if (isCheckout) {
      setTimeout(() => {
        $("td.product-name").each(function () {
          let productName = $(this)
            .contents()
            .filter(function () {
              return this.nodeType === Node.TEXT_NODE; // Get the text node directly
            })
            .text()
            .trim();

          if (productName.startsWith("FITrite: Professional Measurement")) {
            $(this).html(function (index, oldHtml) {
              return oldHtml.replace(
                "FITrite: Professional Measurement",
                '<span style="color:#52A37F">FIT</span>rite: Professional Measurement'
              );
            });
          }
        });
      }, 2400);
    }
  };

  let videoPluginModifier = () => {
    $(".aiovg-player").click(function () {
      let videoSrc = $(this).find("iframe").attr("src");
      $(".video_popup .popup_content iframe").remove();
      $(".video_popup .popup_content").prepend(`
          <iframe src="${videoSrc}&autoplay=1">
      `);
      $(".video_popup iframe").click();
      $(".video_popup").fadeIn();
      // $(".video_popup video").attr("controls", "");
      // $(".video_popup video")[0].play();
    });

    $(".video_popup .close_button").click(function () {
      $(this).closest(".video_popup").hide();
      $(".video_popup iframe").remove();
    });
  };

  let videoPlayer = () => {
    $(".video_section .play_button").click(function () {
      const video = $(this).siblings("video");
      let videoSrc = $(this).parent().find("source").attr("src");

      $(".video_popup .popup_content video").remove();
      $(".video_popup .popup_content").prepend(`
          <video controls poster="/wp-content/uploads/2024/10/video_placeholder.png" playsinline preload="auto" style="width: 100%; height: 100%;" class="vjs-tech" id="player_outside_mount">
            <source type="video/mp4" src="${videoSrc}">
            Your browser does not support the video tag.
          </video>
      `);
      $(".video_popup").fadeIn();
      $(".video_popup video").attr("controls", "");
      $(".video_popup video")[0].play();
      // $(this).remove();
    });

    $(".video_popup .close_button").click(function () {
      $(this).closest(".video_popup").hide();
      $(".video_popup video")[0].pause();
    });

    $(".video_section video").click(function () {
      $(this).siblings(".play_button").trigger("click");
    });
  };

  let moveShippingFreeMsg = () => {
    var freeShippingMessage = $(".free-shipping-message").text(); // Get the message
    var shippingFee = $(
      '.fee td[data-title="Shipping"] .woocommerce-Price-amount'
    ); // Locate the shipping fee

    if (freeShippingMessage && shippingFee.length) {
      // Append the message to the shipping fee in brackets
      var currentShippingText = shippingFee.html();
      shippingFee.html(
        currentShippingText +
          ' <span style="font-size: 12px; color: #666;">(' +
          freeShippingMessage +
          ")</span>"
      );

      // Remove the original message from its location
      $(".free-shipping-message").remove();
    }
  };

  let updateCartTotalWithInstallationFee = () => {
    let newCartTotalWithInstallationFee;

    $("#installation-required").change(function () {
      let installationFee = Number(
        $(".place-installation-fee").eq(0).text().replaceAll("$", "").trim()
      );
      let cartTotal = Number(
        $(".order-total .woocommerce-Price-amount bdi")
          .text()
          .replaceAll("$", "")
          .replaceAll(",", "")
          .trim()
      );

      let isChecked = $(this).prop("checked");

      if (isChecked) {
        newCartTotalWithInstallationFee = installationFee + cartTotal;
      } else {
        newCartTotalWithInstallationFee = cartTotal - installationFee;
      }

      // Update the price in the order-total row
      let formattedTotal = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
      }).format(newCartTotalWithInstallationFee);
      $(".order-total .woocommerce-Price-amount bdi").text(formattedTotal);
    });

    $("tr.fee th").each(function () {
      if ($(this).text() == "Installation Fee") {
        $("#installation-required").prop("checked", true);
      }
    });
  };

  let replaceTwitterIcon = async () => {
    $(".list-social-icons .fa-twitter").append(`
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>
      `);
    $(".list-social-icons .fa-twitter").removeClass("fa-twitter");
  };

  if (isCart || isCheckout) {
    noShippingForMeasurement();
    alterCartTextFITrite();
  }

  if (isCart) {
    // moveShippingFreeMsg();
    updateCartTotalWithInstallationFee();
  }

  if (isProduct) {
    enableSelect2();
    removeFractions78();
    videoPlayer();
  }

  if (isSupport) {
    videoPluginModifier();
  }

  replaceTwitterIcon();
});
