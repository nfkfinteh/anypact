function validate_date(value)
{
    var arrD = value.split(".");
    arrD[1] -= 1;
    var d = new Date(arrD[2], arrD[1], arrD[0]);
    if ((d.getFullYear() == arrD[2]) && (d.getMonth() == arrD[1]) && (d.getDate() == arrD[0]))
        return true;
    else
        return false;
}

function addHistory(result, $dateFrom, $dateTo){
    $result = JSON.parse(result);
    preload('hide');
    if($result['STATUS']=='ERROR')
        showResult('#popup-error','Ошибка! ', $result['ERROR_DESCRIPTION']);
    else if($result['STATUS']=='SUCCESS'){
        if($result['HTML'].length > 0){
            var html = $.parseHTML( $result['HTML'] );
            if($(html).find('.pagination').length > 0){
                var dateFrom = $dateFrom.val();
                var dateTo = $dateTo.val();
                $(html).find('.pagination .page-item .page-link').click(function(e){
                    e.preventDefault;
                    preload('show');
                    $.ajax({
                        type: 'post',
                        url: MWH_component.ajaxUrl,
                        data: {
                            via_ajax: 'Y',
                            action: 'getHistory',
                            sessid: BX.bitrix_sessid(),
                            SITE_ID: MWH_component.siteID,
                            signedParamsString: MWH_component.signedParamsString,
                            data: {
                                'DATE_FROM': dateFrom,
                                'DATE_TO': dateTo
                            },
                            page: $(this).data('page')
                        },
                        success: function(result){addHistory(result, $dateFrom, $dateTo)},
                        error: function (a,b,c) {
                            console.log(a);
                            console.log(b);
                            console.log(c);
                            preload('hide');
                            showResult('#popup-error','Ошибка! Неизвестная ошибка, повторите позднее');
                        }
                    });
                    return false;
                });
            }
            if($('#history_table').length > 0)
                $('#history_table').html(html);
        }
    }else
        showResult('#popup-error','Ошибка! Неизвестная ошибка, повторите позднее');
}

$(document).ready(function(){
    var copyWalletBtn = document.querySelector('#copyText');  
    copyWalletBtn.addEventListener('click', function(event) {  
        var walletNumber = document.querySelector('#wallet-number');  
        var range = document.createRange();  
        range.selectNode(walletNumber);  
        window.getSelection().addRange(range);  
            
        document.execCommand('copy');   
            
        window.getSelection().removeAllRanges();
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Скопировано";

        window.getSelection().removeAllRanges();
    });
    $('#moneta_history input[name="dateFrom"]').on('click', function(){
        BX.calendar({node:this, field:'dateFrom', form: '', bTime: false})
    });
    $('#moneta_history input[name="dateTo"]').on('click', function(){
        BX.calendar({node:this, field:'dateTo', form: '', bTime: false})
    });
    $('#moneta_history button[name="show"]').on('click', function(){
        var $dateFrom = $('#moneta_history input[name="dateFrom"]');
        var $dateTo = $('#moneta_history input[name="dateTo"]');

        var check = true;

        if(!validate_date($dateFrom.val())){
            showResult('#popup-error','Ошибка! Некорректная Дата начала периода');
            check = false;
        }

        if(!validate_date($dateTo.val())){
            showResult('#popup-error','Ошибка! Некорректная Дата конца периода');
            check = false;
        }

        if(check){
            preload('show');
            $.ajax({
                type: 'post',
                url: MWH_component.ajaxUrl,
                data: {
                    via_ajax: 'Y',
                    action: 'getHistory',
                    sessid: BX.bitrix_sessid(),
                    SITE_ID: MWH_component.siteID,
                    signedParamsString: MWH_component.signedParamsString,
                    data: {
                        'DATE_FROM': $dateFrom.val(),
                        'DATE_TO': $dateTo.val()
                    },
                    page: 1
                },
                success: function(result){addHistory(result, $dateFrom, $dateTo)},
                error: function (a,b,c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                    preload('hide');
                    showResult('#popup-error','Ошибка! Неизвестная ошибка, повторите позднее');
                }
            })
        }
    });
})