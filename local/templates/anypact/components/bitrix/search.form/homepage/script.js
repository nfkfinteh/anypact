$(document).ready(function() { 
    //открытие меню профиля в шапке
    $('.widget_user_profile_name__title').on('click', function(){
        let select = $('.widget_user_profile_select');
        let visual = $(select).css('display');        
        if(visual == 'none'){
            $(select).css('display', 'block');
            $('.dropdown-arrow-profile').addClass('active-arrow');
        }else {
            $(select).css('display', 'none');
            $('.dropdown-arrow-profile').removeClass('active-arrow');
        }
    });
    $(document).mouseup(function (e) {
        var popup = $('#widget_user_profile_name__title');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('#widget_user_profile_select').css('display', 'none');
            $('.dropdown-arrow-profile').removeClass('active-arrow');
        }
    });
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