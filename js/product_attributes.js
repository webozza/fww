jQuery(document).ready(function($){
    $('.product_attribute input, .product_attribute select').on('change',function () {
        let fieldName = $(this).attr('name');
        let fieldValue
        if ($(this).is(':checkbox')) {
            fieldValue = $(this).is(':checked') ? 'on' : 'off';
        } else {
            fieldValue = $(this).val();
        }

        console.log('fieldName : ' , fieldName)
        console.log('fieldValue : ' , fieldValue)
    })
})