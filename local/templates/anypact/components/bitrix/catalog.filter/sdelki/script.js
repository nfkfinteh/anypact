$(document).ready(function(){
    var minCost2 = '#minmax0';
    var maxCost2 = '#minmax1';

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
            console.log($(minCost2));
            console.log($(maxCost2));
            $(minCost2).val($("#slider").slider("values",0));
            $(maxCost2).val($("#slider").slider("values",1));
        }
    });


    $(document).on('click', '.js-filter-date', function(){
        BX.calendar({node:this, field:this, form: '', bTime: false})
    });

    var select_lcation_city =  $('#LOCATION_CITY').selectize({
        sortField: 'text'
    });
    //var control_location_city = select_lcation_city[0].selectize;


});