var button_send_contract = document.getElementById('send_contract');

function loadTextBox(el) {
    let variable = $(el);
    let parent_var = variable.parent('edbox');
    let text_box = parent_var.children('useredittext')
    let content_text_box = text_box.text();
    let context_input = variable.val();
    if (context_input == '') {
        context_input = content_text_box;
    }
    text_box.text(context_input);
    variable.remove();
}

$(document).ready(function() {


    $('useredittext').on('click', function() {
        let select_box = $(this);
        let dom_nodes = $($.parseHTML('<input type="text" class="input_text" />'));
        select_box.after(dom_nodes);
        $(dom_nodes).focus();

    });

    // ввод текста во всплывающем окне
    $(document).on('focusout', '.input_text', function() {
        let variable = $(this);
        loadTextBox(variable);
    });
    $("body").keypress(function(e) {
        if (e.which == 13) {
            let variable = $('.input_text');
            loadTextBox(variable);
        }
    });

    //своя редакция
    $(document).on('click', '#new_redaction', function(){
        let url = '/response/ajax/new_redaction_prew_up.php';
        let id = $(this).attr('data-id_element');
        let data = {
            ELEMENT_ID: id
        };

        $(this).hide();

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $('.js-dogovor').html(result);
            },

        });

    });

});