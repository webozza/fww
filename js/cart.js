jQuery(document).ready(function($) {

    $('.cart-subtotal').after(`
            <tr class="Installation">
                <th>Installation</>
                <td class="installation-fee">No Instalation</td>
            </tr>
    `)




    $('#check_zip_code').on('click', function() {
        var zipCode = $('#zip_code').val();
        var resultDiv = $('#zip_code_result');
        
        if (available_zip_codes.includes(zipCode)) {
            resultDiv.html(sucess_text);
            addInstallationPrice();
        } else {
            resultDiv.html(unavailable_text);
        }
    });

    function addInstallationPrice() {
        $("#zip_code_result input").on("change", function () {
            var installationValue = $(this).is(":checked") ? 'Add Installation' : 'No Installation';
            updateCartVariant(installationValue);
        });
    }
    
    function updateCartVariant(value) {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_cart_variant',
                variant_value: value,
                product_id: product_id,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.woocommerce-Price-amount.amount').replaceWith(response.data.new_total);
                    
                    if(response.data.installation_value == 'No Installation'){
                        $('.installation-fee').text(response.data.installation_value)
                        $('dd.variation-Installation p').text(response.data.installation_value)


                    } else{
                        $('dd.variation-Installation p').text("Installation Added")
                        $('.installation-fee').text('$200.00')
                    }
                    
                    console.log(response.data.installation_value)

                } else {
                    console.log('Error updating variant:', response.data);
                }
            },
            error: function(error) {
                console.log('Error updating variant:', error);
            }
        });
    }

    console.log('Cart Js is connected')
});
