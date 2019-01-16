/**
 * Created by Яков on 17.04.2017.
 */

document.addEventListener("DOMContentLoaded", function(event)
{
    console.warn( 'js-federal-city' );

    var jsFederalCity = $('[js-federal-city]');

    console.log(jsFederalCity);

    jsFederalCity.on('blur change', function()
    {
        console.warn( 'jsFederalCity ~ blur change' );

        var $This = $(this);

        var $Target = $( $This.attr('js-federal-city') );

        console.log($Target);

        if ( $Target.length == 0 )
        {
            return;
        }

        var Value = $Target.val();
        Value = Value.trim();

        console.log(Value);

        if ( Value != '' )
        {
            return;
        }

        var MasterValue = $This.val();
        MasterValue = MasterValue.trim();

        console.log(MasterValue);

        switch ( MasterValue )
        {
            case 'Город Москва': $Target.val( 'Москва Город' ); break;
            case 'Город Санкт-Петербург': $Target.val( 'Санкт-Петербург Город' ); break;
        }
    });
});