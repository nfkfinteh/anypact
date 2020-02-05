$(document).ready(function(){
    var count_unread = $('.count_unread');

    setInterval(function() {
        $.post(
            "/response/ajax/get_unread_message.php", {},
            function(data){
                count_unread.text(data);
            }
        );
    },1000*5);

});