$(document).ready(function(){
    const listType = get_cookie ( 'list_style' );
    if (listType==1){
        $("button.btn-list").addClass("active");
        $("button.btn-tiled").removeClass("active");
        $('.tender-block').addClass("tender-horizon").removeClass("tender-block col-md-6 col-lg-4");
        $('.grid-view').addClass("list-view").removeClass("grid-view");
    }

    $(document).on('click', '.js-add-frends', function(){
        let that = $(this);
        let login = $(this).attr('data-login');
        let data = {
            'login':login,
            'action':'add'
        };
        $.ajax({
            url: '/response/ajax/add_frends.php',
            data: data,
            type: 'POST',
            success: function(data){
                result = JSON.parse(data);
                if(result.TYPE == 'SUCCESS'){
                    that.hide();
                }
                else{
                    console.log(result.VALUE);
                }
            }
        });

    });

});

$(document).on('click', '.city-choose-btn-city', function(){
    let city = $(this).text();
    set_cookie('CITY_ANYPACT', city);
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
    if ( path )
        cookie_string += "; path=" + escape ( path );
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
$("span.location").click(function () {
    $("div.city-choose").slideDown(600);
});