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
});