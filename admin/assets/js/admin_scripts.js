(function($){
"use strict";
    
    var contenttypeval = admin_wclocalize_data.contenttype;
    if( contenttypeval == 'fakes' ){
        $(".notification_fake").show();
        $(".notification_real").hide();
        $(".notification_randomize_order").show();
    }else{
        $(".notification_fake").hide();
        $(".notification_real").show();
        $(".notification_randomize_order").hide();
    }
    // When Change radio button
    $(".notification_content_type .radio").on('change',function(){
        if( $(this).is(":checked") ){
            contenttypeval = $(this).val();
        }
        if( contenttypeval == 'fakes' ){
            $(".notification_fake").show();
            $(".notification_real").hide();
            $(".notification_randomize_order").show();
        }else{
            $(".notification_fake").hide();
            $(".notification_real").show();
            $(".notification_randomize_order").hide();
        }
    });

    // Fakes data Reapeter Field Increase
    $( '#add-row' ).on('click', function() {
        var row = $( '.empty-row.screen-reader-text' ).clone(true);
        row.removeClass( 'empty-row screen-reader-text' );
        row.insertBefore( '#htrepeatable-fieldset tbody>tr:last' );
        return false;
    });

    // Fakes data Reapeter Field Decrease
    $( '.remove-row:not(.button-disabled)' ).on('click', function() {
        $(this).parents('tr').remove();
        return false;
    });


    
})(jQuery);