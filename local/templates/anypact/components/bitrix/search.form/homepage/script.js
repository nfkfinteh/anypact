$(document).ready(function() { 
    //открытие меню профиля в шапке
    $('#widget_user_profile_name__title').on('click', function(){
        let select = $('#widget_user_profile_select');
        let visual = $(select).css('display');        
        if(visual == 'none'){
            $(select).css('display', 'block');
            $('.widget_user_profile_name__title:after').addClass('active-arrow');
        }else {
            $(select).css('display', 'none');
            $('.widget_user_profile_name__title:after').removeClass('active-arrow');
        }
    });
    $(document).mouseup(function (e) {
        var popup = $('#widget_user_profile_name__title');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('#widget_user_profile_select').css('display', 'none');
        }
    });
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