$(document).ready(function(){
    const listType = get_cookie ( 'list_style' );
    if (listType==1){
        $("button.btn-list").addClass("active");
        $("button.btn-tiled").removeClass("active");
        $('.tender-block').addClass("tender-horizon").removeClass("tender-block col-md-6 col-lg-4");
        $('.grid-view').addClass("list-view").removeClass("grid-view");
    }
});



function get_cookie ( cookie_name )
{
    const results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if ( results )
        return ( unescape ( results[2] ) );
    else
        return null;
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