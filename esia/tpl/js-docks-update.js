/**
 * Created by Yakov on 17.04.2017.
 */

document.addEventListener("DOMContentLoaded", function(event)
{
    console.warn( 'js-docks-update' );

    setTimeout(function(){
        $('#market_stock').trigger('change');
    }, 500);


});