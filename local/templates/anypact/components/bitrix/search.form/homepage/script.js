$(document).ready(function() { 
    // выбор категории
    $('#button_select_category').on('click', function(){
        let select = $('#select_category_main');
        let visual = $(select).css('display');        
        if(visual == 'none'){
            $(select).css('display', 'block');
        }else {
            $(select).css('display', 'none');
        }
    });
    
    $(document).mouseup(function (e) {
        var popup = $('#button_select_category');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('#select_category_main').css('display', 'none');
        }
    });
});