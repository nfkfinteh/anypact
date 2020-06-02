$(document).ready(function(){
    var deletLine;
    $(document).on('click', '.modal_deleteItem', function(){
        let idItem = $(this).attr('data-id');
        deletLine = $(this).parents('tr').eq(0);
        $('.deleteItem').attr('data-id', idItem);
    });
    $(document).on('click', '.deleteItem', function(e){
        e.preventDefault();
        let url = '/response/ajax/delete_item.php';
        let idItem = $(this).attr('data-id');
        let data = {
            id: idItem
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    console.log($result['VALUE']);
                    alert($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                   $('.deleteItem-modal_close').click();
                   $(deletLine).remove();

                }
            },

        });
    });
    // переключить активность объявления
    $(document).on('click', '.onActive', function(e){        
        var buttonActive = $(this).attr('active');
        var buttonIDPact = $(this).attr('iditem');
        var activeStatus = 'Y';
        
        if(buttonActive == 'Y'){
            activeStatus = 'N';
            $(this).attr('active', 'N');
        }else{
            activeStatus = 'Y';
            $(this).attr('active', 'Y');
        }
        
        e.preventDefault();
        let url = '/response/ajax/active_pact.php';        
        let data = {
            IDElement: buttonIDPact,
            Active: activeStatus
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    location.reload()
                }
            },

        });
    });
    // отозвать подпись
    $('.recall_send').on('click', function(e){
        console.log('Отзыв подписи')
        let id = $(this).attr('data');
        
        $('#delete_deal').attr('data-type', 'recall_send');
        $('#delete_deal').attr('data', id);
        $('#dealDeleteWarning').show();

        $('#deactive_send_label').hide();
        $('#recall_send_label').show();

        e.preventDefault();
        /*let url = '/response/ajax/deactive_send.php';        
        let data = {
            IDItem: id            
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    location.reload()
                }
            },

        });*/

        return false;
    });

    $('#delete_deal').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('data');
        if($(this).attr('data-type') == 'deactive_send'){
            var url = '/response/ajax/active_pact.php';        
            var data = {
                IDElement: id,
                Active: 'N'
            };
        }else if($(this).attr('data-type') == 'recall_send'){
            var url = '/response/ajax/deactive_send.php';        
            var data = {
                IDItem: id            
            };
        }
        console.log(url);
        console.log(data);
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    location.reload()
                }
            },

        });
        
        return false;
    })

    $('.deactive_send').on('click', function(e){
        console.log('Отзыв подписи')
        let id = $(this).attr('data');
        
        $('#delete_deal').attr('data-type', 'deactive_send');
        $('#delete_deal').attr('data', id);
        $('#dealDeleteWarning').show();

        $('#recall_send_label').hide();
        $('#deactive_send_label').show();
        
        e.preventDefault();
        /*let url = '/response/ajax/active_pact.php';        
        let data = {
            IDItem: id,
            Active: 'N'
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                console.log(result);
                if(result==1){
                    location.reload()
                }
            },

        });*/

        return false;
    });

    $('#signpopup_close').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

    $('#close_sign_popup').on('click', function () {
        $('#dealDeleteWarning').hide();
    });

    $('.hide-show-scroll').on('click', function (e) { 
        e.preventDefault();
        var count = $(this).parent().prev().children('.d-md-table-row.collapse-body').length;
        $(this).parent().prev().children('.d-md-table-row.collapse-body').each(function (index, el){
            if(index !== 0){
                if($(el).css('display') === 'none'){
                    $(el).show(index * 100);
                }else{
                    $(el).hide((count - index - 1) * 40);
                }
            }
        });
        $(this).toggleClass('open');
        if($(this).text() == "Скрыть"){
            $(this).text("Показать все");
        }else{
            $(this).text("Скрыть");
        }
        return false;
    });

    if(document.documentElement.clientWidth < 768){
        $('.d-md-table-row.collapse-body').show();
    }
});