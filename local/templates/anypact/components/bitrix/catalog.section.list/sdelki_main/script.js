$(document).ready(function(){
    $(document).on('click', '.card-deal__button', function(){
        let href = $(this).attr('href');
        window.location.href = href;
    });
});