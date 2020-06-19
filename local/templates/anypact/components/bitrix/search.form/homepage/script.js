$(document).ready(function() { 
    
    // выбор категории
    $('#button_select_category').on('click', function(){
        let select = $('#select_category_main');
        let visual = $(select).css('display');        
        if(visual == 'none'){
            $(select).css('display', 'block');
            $('.dropdown-arrow-deal').addClass('active-arrow');
        }else {
            $(select).css('display', 'none');
            $('.dropdown-arrow-deal').removeClass('active-arrow');
        }
    });
    
    $(document).mouseup(function (e) {
        var popup = $('#button_select_category');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('#select_category_main').css('display', 'none');
            $('.dropdown-arrow-deal').removeClass('active-arrow');
        }
    });
});

if(window.innerWidth < 767){
    $('.magnifier').on('click', function(){
        $(this).parent('form').submit();
    })
}