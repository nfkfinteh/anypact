$(document).ready(function(){
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
        var popup = $('.widget_user_profile_name__title');
        if (e.target!=popup[0]&&popup.has(e.target).length === 0){
            $('.widget_user_profile_select').css('display', 'none');
            $('.dropdown-arrow-profile').removeClass('active-arrow');
        }
    });
    //блок с подтвержденной регистрацией на ЕСИА
    $('.check-esia-img').mousemove(function(e){
        var X = e.offsetX;
        var Y = e.offsetY;
        var top = Y  + 25 + 'px';
        var left = X  - 10 + 'px';
        var id = $(this).children(".check-esia-img-info");
        id.css({
            display:"block",
            top: top,
            left: left
        });
    });
    $('.check-esia-img').mouseout (function(){
        var id = $(this).children(".check-esia-img-info");
        id.css({
            display:"none"
        });
    });


    const listType = get_cookie ( 'list_style' );
    if (listType==1){
        $("button.btn-list").addClass("active");
        $("button.btn-tiled").removeClass("active");
        $('.tender-block').addClass("tender-horizon").removeClass("tender-block col-md-6 col-lg-4");
        $('.grid-view').addClass("list-view").removeClass("grid-view");
    }
    $(document).on("scroll", window, function () {
        if ($(window).scrollTop() >= 200) {
            $(".up-arrow").show();
        } 
        else {
            $(".up-arrow").hide();
        }
    });
});
$(document).on('click', '.city-choose-btn-city', function(){
    let city = $(this).text();
    set_cookie('CITY_ANYPACT', city);
    $.ajax({
        url: '/response/ajax/check_city.php',
        type: 'POST',
        data: {'city':city},
    });
    window.location.reload();
});


$(document).on('submit', '.sity-submit', function(e){
    e.preventDefault();
    let city = $(this).find('.sity-submit_input').val();
    $.ajax({
        url: '/response/ajax/check_city.php',
        type: 'POST',
        data: {'city':city},
        success: function(data){
            let result = JSON.parse(data);
            if(result.TYPE == 'SUCCESS'){
                set_cookie('CITY_ANYPACT', result.VALUE.NAME);
                $('.sity-submit_input').removeClass('error-city');
                window.location.reload();
            }
            else{
                $('.sity-submit_input').addClass('error-city');
                console.log(result.VALUE);
            }

        }
    });
    //проверка на существование города
    set_cookie('CITY_ANYPACT', city);
});



function get_cookie ( cookie_name )
{
    const results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if ( results )
        return ( unescape ( results[2] ) );
    else
        return null;
}

function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
    var cookie_string = name + "=" +  value;
    if ( exp_y )
    {
        var expires = new Date ( exp_y, exp_m, exp_d );
        cookie_string += "; expires=" + expires.toGMTString();
    }
    if ( path ){
        cookie_string += "; path=" + escape ( path );
    }
    else{
        cookie_string += "; path=/";
    }

    if ( domain )
        cookie_string += "; domain=" + escape ( domain );
    if ( secure )
        cookie_string += "; secure";
    document.cookie = cookie_string;
}

$("button.btn-tiled").click(function () {
    console.log('табличка')
    $("button.btn-tiled").addClass("active");
    $("button.btn-list").removeClass("active");
    $('.tender-horizon').addClass("tender-block col-md-6 col-lg-4").removeClass("tender-horizon");
    $('.list-view').addClass("grid-view").removeClass("list-view");
    document.cookie = "list_style=0";
});
$("button.btn-list").click(function () {
    console.log('список')
    $("button.btn-list").addClass("active");
    $("button.btn-tiled").removeClass("active");
    $('.tender-block').addClass("tender-horizon").removeClass("tender-block col-md-6 col-lg-4");
    $('.grid-view').addClass("list-view").removeClass("grid-view");
    document.cookie = "list_style=1";
});


$("button.city-choose-btn-close").click(function () {
    $("div.city-choose").slideUp(600);
});
// геолокация открытия окна
function openCloseWin(){
    let statusWin = $(".city-choose").css('display');    
    if(statusWin == 'none'){
        $("div.city-choose").slideDown(600);
    }else {
        $("div.city-choose").slideUp(600);
    }
}

$("span.location").click(function () {
    openCloseWin()
});

$("a.region").click(function () {
    openCloseWin()
});

function showResult(name, title='', text='') {
    var popups = $('.popup-wrapper');
    var activePopup;
    var w, h;
    popups.each(function (i, elem) {
        if ($(elem).css('display') == 'block') {
            activePopup = $(elem).find('.popup');
            w = activePopup.outerWidth();
            h = activePopup.outerHeight();
        }
    });
    
    var popup = $(name);
    var block = popup.find('.popup');

    popup.find('.popup__title').html(title + "<br>" + text);
    //popup.find('.popup__subtitle').text(text);

    block.css({
        'width': w + 'px',
        'height': h + 'px'
    });
    popup.fadeIn(500);
    $('.modals-wrap').show();
    //block.addClass('popup-anim');
    setTimeout(function () {
        popup.fadeOut(500);
        $('.modals-wrap').hide();
    }, 4000)
}

function preload($action){
    if($action == 'show'){
        $('body').addClass('load');
        $('#popup-load').show();
    }
    if($action == 'hide'){
        $('body').removeClass('load');
        $('#popup-load').hide();
    }
}

function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

$(document).on('keypress', '.js-number', function(e){
    validate(e);
});

//макси для поле формы
$('.js-mask__email').inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace("mailto:", "");
    },
    definitions: {
        '*': {
            validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
            casing: "lower"
        }
    }
});
$('.js-mask__phone').inputmask('8(999) 999-99-99');