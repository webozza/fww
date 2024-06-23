jQuery(document).ready(function($) {
    if ($('body').hasClass('single-product')) {
        $('input[name="inside_mount"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[name="outside_mount"]').prop('checked', false);
            }
        });
    
        $('input[name="outside_mount"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[name="inside_mount"]').prop('checked', false);
            }
        });
    }




    




});