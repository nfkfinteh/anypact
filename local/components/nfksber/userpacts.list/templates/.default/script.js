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
        console.log('Активность кнопки');
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
});