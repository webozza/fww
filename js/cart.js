jQuery(document).ready(function ($) {
  function calculateInstallationPrice() {
    // Select all product rows in the cart
    var $productRows = $(
      ".woocommerce-cart-form .woocommerce-cart-form__contents tbody tr"
    );

    // Initialize variables for counting widths
    var countWidthLessThan36 = 0;
    var countWidth36to72 = 0;
    var countWidthMoreThan72 = 0;

    // Iterate through each product row to count widths
    $productRows.each(function () {
      var widthText = $(this).find(".variation-Width p").eq(0).text();
      var width = parseInt(widthText);

      if (width <= 36) {
        countWidthLessThan36++;
      } else if (width > 36 && width <= 72) {
        countWidth36to72++;
      } else if (width > 72) {
        countWidthMoreThan72++;
      }
      console.log("width : ", width);
    });
    console.log("----------------");

    // Calculate additional fees based on the counts
    var additionalFee =
      countWidthLessThan36 * 25 +
      countWidth36to72 * 30 +
      countWidthMoreThan72 * 35;

    // Minimum installation fee is $75
    var installationFee = Math.max(75, additionalFee);

    return installationFee;
  }

  $(".cart-subtotal").after(`
            <tr class="Installation">
                <th>Installation</>
                <td class="installation-fee">No Instalation</td>
            </tr>
    `);

  function makeInstallationClick() {
    $(".zip-available input").change(function () {
      if ($(this).is(":checked")) {
        $("input#installation_required").click();
      } else {
        $("input#installation_required").click();
      }
    });

    $(".zip-available input").click(function () {
      let totalPrice = $(".order-total .woocommerce-Price-amount bdi")
        .text()
        .replace(",", "");
      let sanitizedPrice = parseFloat(totalPrice.replace("$", ""));
      let newPrice;
      let isChecked = $(this).is(":checked");
      if (isChecked) {
        if ($(".shop_table .fee").length != 1) {
          newPrice = sanitizedPrice + calculateInstallationPrice();
          $(".woocommerce-shipping-totals").after(`<tr class="fee">
                                <th>Installation Fee</th>
                                <td data-title="Installation Fee"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>${calculateInstallationPrice()}</bdi></span></td>
                            </tr>`);
        }
      } else {
        $(".shop_table .fee").remove();
        newPrice = sanitizedPrice - calculateInstallationPrice();
      }

      $(".order-total .woocommerce-Price-amount bdi").html(
        `<span class="woocommerce-Price-currencySymbol">$</span>${newPrice.toLocaleString()}`
      );
    });
  }

  $("#check_zip_code").on("click", function () {
    var zipCode = $("#zip_code").val();
    var resultDiv = $("#zip_code_result");

    if (
      !$("body").hasClass("woocommerce-cart") &&
      !$("body").hasClass("postid-465") &&
      !$("body").hasClass("page-id-759")
    ) {
      if (available_zip_codes.includes(zipCode)) {
        resultDiv.html(sucess_text);
        setTimeout(() => {
          let isFeeAdded = $(".cart_totals .shop_table .fee").length;
          if (isFeeAdded === 1) {
            $(".zip-available input").click();
          }
        }, 300);

        makeInstallationClick();
      } else {
        resultDiv.html(unavailable_text);
      }
    }
  });

  console.log("Cart Js is connected");
});
