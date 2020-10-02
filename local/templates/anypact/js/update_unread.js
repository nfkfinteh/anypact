// $(document).ready(function(){
//     var count_unread = $('.count_unread');

//     setInterval(function() {
//         $.post(
//             "/response/ajax/get_unread_message.php", {},
//             function(data){
//                 if(data>0){
//                     count_unread.addClass('active');
//                 }
//                 else{
//                     count_unread.removeClass('active');
//                 }
//                 count_unread.text(data);
//             }
//         );
//     },1000*5);

// });