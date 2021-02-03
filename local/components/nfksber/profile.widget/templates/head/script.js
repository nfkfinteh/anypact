$( document ).ready(function (){
    var user_menu = $('.navbar.navbar-expand-md').find('.user-menu-overflow');
    user_menu.attr('id', 'user_menu');
    user_menu.detach().appendTo('body');
    $('.navbar.navbar-expand-md').find('.link-messageList').on('click', function(el) {
        el.preventDefault;
        user_menu.show(300);
        setTimeout(function(){user_menu.children('.user-menu-block').toggleClass('user-menu-block-show');}, 1);
        $('body').css("overflow", "hidden");
        return false;
    });
    user_menu.on('click', function(){
        user_menu.hide(300);
        setTimeout(function(){user_menu.children('.user-menu-block').toggleClass('user-menu-block-show');}, 1);
        $('body').css("overflow", "auto");
    });
});