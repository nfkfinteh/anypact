$(document).ready(function(){
    var button_send_contract = document.getElementById('send_contract');

    window.onload = function() {
        console.log('подпись!');

        button_send_contract.onclick = function(e) {
            console.log(e);
        }

    };

    //генерация и скачивани договора в PDF
    $(document).on('click touchstart', '#download_pdf', function() {
        let canvas_contr = $('.view-pdf');
        let canvas_contr_context = String(canvas_contr.html());
        let id = $(this).attr('data-id');
        // загружаем содержимое категории
        $.post(
            "/response/ajax/get_pdf_dogovor.php", {
                contect: canvas_contr_context
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            console.log(data);
            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
            /*let result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                console.log($result['VALUE']);
                alert(result['VALUE']);
            }
            if(result['TYPE']=='SUCCESS'){
                console.log(result['VALUE']);
                //alert(result['VALUE']);
                window.location.href = "/my_pacts/edit_my_pact/?ELEMENT_ID="+result['ID']+"&ACTION=EDIT";
            }*/

        }

    });
});



