// разрешение редактирование курсора
function on_contenteditable(element) {
    var element_atr = element.attr('contenteditable');
    console.log(element_atr);
    if (element_atr == 'true') {
        element.attr('contenteditable', false);
        return false;
    } else {
        element.attr('contenteditable', true);
        return true;
    }
}

// вставка текста на место курсора
function insertTextAtCursor(text) {
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    range.deleteContents();
    let node = document.createTextNode(text);
    range.insertNode(node);

    for (let position = 0; position != text.length; position++) {
        selection.modify("move", "right", "character");
    };
}

function insertBoldAtCursor(text) {
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    let string = selection.toString();
    range.deleteContents();

    let insert_space = document.createElement('b');
    //insert_space.className = "alert alert-success";
    insert_space.innerHTML = string;
    range.insertNode(insert_space);

    for (let position = 0; position != text.length; position++) {
        selection.modify("move", "right", "character");
    };
}

function formatSelectText(id_name) {
    // получаем выделенный текст
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    let sel_string = selection.toString();
    console.log(selection.focusNode.parentNode);
    // удаляем его, что бы замнить
    range.deleteContents();
    // на основе id выбираем подстановку    
    let key = id_name.replace('btn-', '');
    let arrTegs = {
        weight: 'b',
        italic: 'i',
        noedit: 'noedit'
    }

    let insert_space = document.createElement(arrTegs[key]);
    if (key == 'noedit') {
        insert_space.setAttribute('contenteditable', false);
    }
    insert_space.innerHTML = sel_string;
    range.insertNode(insert_space);

    for (let position = 0; position != text.length; position++) {
        selection.modify("move", "right", "character");
    };
}

function loadTextCanvas(text, canvas_name) {
    let canvas = document.getElementById(canvas_name);
    canvas.innerHTML = text;

}

// подстановка текста из всплывающего инпута
function loadTextBox(el) {
    let variable = $(el);
    let parent_var = variable.parent('edbox');
    let text_box = parent_var.children('rededittext')
    let content_text_box = text_box.text();
    let context_input = variable.val();
    if (context_input == '') {
        context_input = content_text_box;
    }
    text_box.text(context_input);
    variable.remove();
}

// автоподстановка реквизитов пользователя
function setHeaderFullName(idname) {
    let seller = document.getElementsByClassName('fullnameseller');
    let customer = document.getElementsByClassName('fullnamecustomer');
    //let type_user = document.getElementById('step0_text');
    switch (idname) {
        case 'seller':
            for (var i = 0; i < seller.length; i++) {
                seller[i].innerText = full_name.surname + ' ' + full_name.name + ' ' + full_name.midlname;

            }
            for (var i = 0; i < customer.length; i++) {
                customer[i].innerText = '[ФИО Покупателя]'

            }
            //type_user.setAttribute('type', 'seller');
            break;
        case 'customer':
            for (var i = 0; i < seller.length; i++) {
                seller[i].innerText = '[ФИО Продавца]';

            }
            for (var i = 0; i < customer.length; i++) {
                customer[i].innerText = full_name.surname + ' ' + full_name.name + ' ' + full_name.midlname;

            }
            //type_user.setAttribute('type', 'customer');
            break;

        default:
            for (var i = 0; i < seller.length; i++) {
                seller[i].innerText = full_name.surname + ' ' + full_name.name + ' ' + full_name.midlname;
            }
            for (var i = 0; i < customer.length; i++) {
                customer[i].innerText = '[ФИО Покупателя]';

            }
            //type_user.setAttribute('type', 'seller');
            break;
    }

}

function setBackgraundStepBox() {
    let box = document.getElementsByClassName('t');
    for (var i = 0; i < box.length; i++) {
        box[i].style.backgroundColor = '#fff';

    }
}

////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function() {

    setHeaderFullName();

    $('#select_type_user').on('change', function() {
        let value = $(this).val();
        setHeaderFullName(value);
    });

    $('.cardDogovor-boxViewText span').on('click', function() {
        var category = $(this);
        var id_category = category.attr('data-id');
        var canvas_contr = $('.cardDogovor-boxViewText');

        // загружаем содержимое категории
        $.post(
            "/response/ajax/get_template_contract.php", {
                idcontract: id_category
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.            
            canvas_contr.html(data);
        }


    });


    $(document).on('click touchstart', '.cardDogovor-boxViewText spandoc', function() {
        var category = $(this);
        var id_category = category.attr('data-id');
        var canvas_contr = $('.cardDogovor-boxViewText');
        // загружаем содержимое категории
        $.post(
            "/response/ajax/get_template_text_contract.php", {
                idcontract: id_category
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.             
            canvas_contr.html(data);
        }

    });

    $(document).on('click touchstart', '#save_btn', function() {
        let canvas_contr = $('.cardDogovor-boxViewText');
        let canvas_contr_context = String(canvas_contr.html());
        // загружаем содержимое категории
        $.post(
            "/response/ajax/up_contract_text.php", {
                contect: canvas_contr_context
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.             
            console.log(data);
        }

    });

    // прыжки по шагам заполнения
    $(document).on('click touchstart', '.steps .t', function() {
        var box = $(this);
        setBackgraundStepBox();
        box.css('backgroundColor', '#ff64160f');
        var text_box_id = box.attr("id");
        var text_box = $("#" + text_box_id + "_text");
        var text_box_js = document.getElementById(text_box_id + "_text");
        var canvas_contr = $('.cardDogovor-boxViewText');
        var scroll_position = text_box_js.offsetTop;
        canvas_contr.scrollTop(scroll_position - 60);
        text_box.css('backgroundColor', '#ff64160f');
    });

    // разрешение редактирование текста
    $('#btn-edit').on('click', function() {
        var canvas = $('#canvas');
        var onof = on_contenteditable(canvas);
        var span_icon = $(this).find('span');

        if (onof) {
            $(this).css("backgroundColor", "#ff6416", );
            $(span_icon).css("color", "#fff");
        } else {
            $(this).css("backgroundColor", "#fff", );
            $(span_icon).css("color", "#ff6416");
        }

    });


    $('#btn-data').on('click', function() {
        var date_ins = '/%DATE%/';
        insertTextAtCursor(date_ins);
    });

    $('.form_text').on('click', function() {
        let id_name = $(this).attr('id');
        formatSelectText(id_name);
    });

    $('rededittext').on('click', function() {
        console.log('Выделенная часть текста');
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

    var form = document.forms.namedItem("loadcontract");
    form.addEventListener('submit', function(ev) {
        var oOutput = document.querySelector("div"),
            oData = new FormData(form);


        oData.append("CustomField", "This is some extra data");

        var oReq = new XMLHttpRequest();
        oReq.open("POST", "/response/ajax/phpword.php?uploadfiles", true);
        oReq.onload = function(oEvent) {
            if (oReq.status == 200) {
                let canvas = document.getElementById('canvas');
                canvas.innerHTML = oEvent.srcElement.response;

            } else {
                oOutput.innerHTML = "Error " + oReq.status + " occurred when trying to upload your file.<br \/>";
            }
        };

        oReq.send(oData);
        ev.preventDefault();
    }, false);



});