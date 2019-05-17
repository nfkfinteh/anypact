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
});