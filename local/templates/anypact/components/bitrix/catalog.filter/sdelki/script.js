//отображение кнопки сброса даты в фильтре
function displayResetDate(){
    let arInputDate = $('.js-filter-date');
    if($(arInputDate[0]).val().length>0 || $(arInputDate[1]).val().length>0){
        $('.filter-date_reset').show();
    }
    else{
        $('.filter-date_reset').hide();
    }
}
$(document).ready(function(){
    var minCost2 = '#minmax0';
    var maxCost2 = '#minmax1';
    displayResetDate();

    //ползунок
    $("#slider").slider({
        min: Number(smartFilter.PRICE_BORDERS.LEFT),
        max: Number(smartFilter.PRICE_BORDERS.RIGHT),
        values: [Number(smartFilter.PRICE.LEFT), Number(smartFilter.PRICE.RIGHT)],
        range: true,
        stop: function(event, ui) {
            $(minCost2).val($("#slider").slider("values",0));
            $(maxCost2).val($("#slider").slider("values",1));
        },
        slide: function(event, ui){
            $(minCost2).val($("#slider").slider("values",0));
            $(maxCost2).val($("#slider").slider("values",1));
        }
    });


    $(document).on('click', '.js-filter-date', function(){
        BX.calendar({node:this, field:this, form: '', bTime: false})
    });

    $(document).on('change', '.js-filter-date', function(){
        displayResetDate();
    });

    $(document).on('click', '.filter-date_reset', function(){
        $('.js-filter-date').val('');
        displayResetDate();
    });

    var select_lcation_city =  $('#LOCATION_CITY').selectize({
        sortField: 'text'
    });

    $('.btn-filter').on('click', function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('.filter-tender').hide(500);
        }else{
            $(this).addClass('active');
            $('.filter-tender').show(500);
        }
    })

});