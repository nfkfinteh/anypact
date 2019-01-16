/**
 * Created by Yakov on 24.04.2017.
 */

document.addEventListener("DOMContentLoaded", function(event)
{

    console.warn('SE');

    var SE = $('[sms-validate]');

    console.log(SE);

    var SE_Rules = {
        onkeyup: false,
        onblur: true,
        rules: {
            sms: {
                required: true,
                minlength: 6,
                maxlength: 6,
                range: [000001,999999],
                remote: {
                    url: "#URL#",
                    type: "post"
                }
            }
        },
        messages: {
            sms: {
                required: "дождитесь получения SMS",
                minlength: "6 цифр",
                maxlength: "6 цифр",
                rrange: "6 Цифр",
                remote: "неверный код"
            }
        },
        errorPlacement: function(error, element) {
            var er = element.attr("name");
            error.appendTo( element.parent().find("label[for='" + er + "']").find("span") );
            //document.getElementById("link_docs").style.display = "none;";
        },
        success: function(label) {
            label.html("&nbsp;").addClass("checked");
            //document.getElementById("link_docs").style.display = "block";
        }
    };

    console.log('SE_Rules');
    console.log(SE_Rules);

    SE.validate( SE_Rules );

    var jsBtnSign = $('[js-btn-sign]');

    jsBtnSign.each(function(i, elem)
    {
        var $this = $(this);

        $this.off();

        var Form = $this.parents('[sms-validate]');

        console.log('Form');
        console.log(Form);

        $this.on('click', function(e)
        {
            e.preventDefault();

            var V = Form.valid();

            if ( V == true )
            {
                Form.submit();
            }
        });
    });

});