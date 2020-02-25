$(document).ready(function() {

    $("#save_descript").on('click', function() {

        var text_descript = $(".cardPact-EditText-Descript .editbox").html();
        var id_element = $(".cardPact-box").attr("data");
        console.log(text_descript);
        console.log(id_element);
        $.post(
            "http://anypact.ru/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: id_element,
                atrr_text: 'descript'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            alert('ответ : ' + data);
        }
    });

    $("#save_conditions").on('click', function() {
        var text_descript = $(".cardPact-EditText-Сonditions .editbox").html();
        var id_element = $(".cardPact-box").attr("data");
        console.log(text_descript);
        console.log(id_element);
        $.post(
            "http://anypact.ru/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: id_element,
                atrr_text: 'conditions'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            alert('ответ : ' + data);
        }
    });
    $("#save_summ").on('click', function() {
        var text_descript = $("#cardPact-EditText-Summ").text();
        var id_element = $(".cardPact-box").attr("data");
        console.log(text_descript);
        console.log(id_element);
        $.post(
            "http://anypact.ru/response/ajax/up_pact_text.php", {
                text: text_descript,
                id_element: id_element,
                atrr_text: 'summ'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            alert('ответ : ' + data);
        }
    });
});