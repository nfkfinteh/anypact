/**
 * Created by Yakov on 24.04.2017.
 */

function js_show_form_send_code()
{
    console.warn('js_show_form_send_code');

    $('[sms-validate]').show("normal");
    $("#butt_send_code").hide("normal");
    $.ajax({
        type: 'POST',
        url: "#URL#",
        dataType: "html",
        beforeSend: function(data) {
            $(".send_kod_again").hide("normal");
            // $("#loading").show("normal"); // сoбытиe дo oтпрaвки
            //form.find("#sub1_esia").attr("disabled", "disabled");
        },
        success: function(response) {
            $('#butt_send_code').hide("normal");
        },
        error: function(response) {
            document.getElementById("res").innerHTML = "<p class='text-danger'>Возникла ошибка при отправке формы. Попробуйте еще раз</p>";
        },
        complete: function(response) { // сoбытиe пoслe любoгo исхoдa
            setTimeout(function(){
                $(".send_kod_again").show("normal");
            }, 30000);
        }
    });
};