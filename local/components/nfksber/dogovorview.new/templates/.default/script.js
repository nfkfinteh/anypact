// разрешение редактирование курсора
function on_contenteditable(element) {
    var element_atr = element.attr('contenteditable');
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
    let node = document.createElement('nedittext');
    node.setAttribute('contenteditable', false);
    node.innerHTML = text;
    range.insertNode(node);

    selection.modify("move", "right", "character");
    /*for (let position = 0; position != text.length; position++) {
        selection.modify("move", "right", "character");
    };*/
}
function rangeCompareNode(range, node) {
    var nodeRange = node.ownerDocument.createRange();
    try {
        nodeRange.selectNode(node);
    }
    catch (e) {
        nodeRange.selectNodeContents(node);
    }
    var nodeIsBefore = range.compareBoundaryPoints(Range.START_TO_START, nodeRange) == 1;
    var nodeIsAfter = range.compareBoundaryPoints(Range.END_TO_END, nodeRange) == -1;

    if (nodeIsBefore && !nodeIsAfter)
        return 0;
    if (!nodeIsBefore && nodeIsAfter)
        return 1;
    if (nodeIsBefore && nodeIsAfter)
        return 2;

    return 3;
}

function formatSelectText(id_name) {
    // получаем выделенный текст
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    let sel_string = selection.toString();
    let sel_html = range.cloneContents();
    // на основе id выбираем подстановку
    let key = id_name.replace('btn-', '');
    let arrTegs = {
        bold: 'b',
        italic: 'i',
        nedittext: 'nedittext'
    }

    if(sel_string.length == 0) return;

    if(key == 'nedittext'){

        //проверка находимся ли мы в диапазоне запрета редактирования
        let parentStartN = $(range.startContainer.parentElement).parents('nedittext').eq(0);
        let parentEndN = $(range.endContainer.parentElement).parents('nedittext').eq(0);

        if(
            range.startContainer.parentElement.tagName != key.toUpperCase() &&
            range.endContainer.parentElement.tagName != key.toUpperCase() &&
            (parentStartN.length!==1 && parentEndN.length!==1)
        ){
            let nedittext = document.createElement('nedittext'),
                wrap_sel = document.createElement('text');


            //если количестов 1 и равняеться nededitex
            //отмена запрета редактирование для всего
            if(
                sel_html.children.length==1 &&
                sel_html.children[0].tagName=='NEDITTEXT' &&
                $(sel_html.children[0]).text() == sel_string
            ){
                console.log('удаление всего');
                sel_html = sel_html.querySelector('nedittext');
                sel_html = sel_html.innerHTML;
                range.deleteContents();
                let insert_space = document.createElement('text');
                insert_space.innerHTML = sel_html;
                range.insertNode(insert_space);
            }
            else{
                console.log('добавление');
                //добавление запрета редактирования
                //удаление внутрених nedittext
                let arNedittext = sel_html.querySelectorAll('nedittext');
                for(let i=0; i<arNedittext.length; i++){
                    if(arNedittext[i].innerHTML.length>0){
                        let sp1 = document.createElement("text");
                        sp1.innerHTML = arNedittext[i].innerHTML;
                        $(arNedittext[i]).replaceWith(sp1);
                    }
                }

                if(range.startContainer.parentNode.innerText == range.endContainer.parentNode.innerText && range.startContainer.parentNode.innerText == sel_string && false){
                    //если какой либо селектор совподает с выделением
                    console.log('test');
                    //$(nedittext).append(range.startContainer.parentNode);
                    $(range.startContainer.parentNode).wrapInner(nedittext);
                    //range.insertNode(range.startContainer.parentNode);
                }
                else{
                    //для запрета редактирования внутри выделеного жирного или курсива
                    console.log('test2');
                    /*if(
                        range.startContainer.parentNode.tagName == range.endContainer.parentNode.tagName &&
                        (range.startContainer.parentNode.tagName == 'B' || range.startContainer.parentNode.tagName == 'I')
                    ){
                        let wrap_text_format = document.createElement(range.startContainer.parentNode.tagName);
                        //sel_html = $(wrap_text_format).append(sel_html);
                    }*/
                    $(nedittext).append(sel_html);

                    //заглушка
                    //покачто не коректно добавлнеи с блочнми элементами
                    let bung = false;
                    $(nedittext).children().each(function(i, element){
                        if(element.tagName == 'P' || element.tagName == 'DIV'){
                            bung = true;
                            return false;
                        }
                    });

                    if(!bung){
                        $(wrap_sel).append(nedittext);
                        range.deleteContents();
                        range.insertNode(wrap_sel);

                        //удаление временного селектора text
                        $('.cardDogovor-boxViewText text').replaceWith(function(){
                            return $(this).html();
                        });
                        //document.execCommand('insertHTML', null, $(wrap_sel).html());
                    }
                    else{
                        showResult('#popup-error','Запрет редактирование отменен. Запрет редактирования возможен тоько в пределах абзаца');
                    }
                }
            }
        }
        else if(range.startContainer.parentNode.innerText.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '') == sel_string.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '')){
            console.log('удаление2');

            $(range.startContainer.parentNode).remove();
            if(sel_html.querySelector('nedittext') === null){
                sel_html = sel_html.textContent;
            }else{
                sel_html = sel_html.querySelector('nedittext');
                sel_html = sel_html.innerHTML;
            }

            let insert_space = document.createElement('text');
            insert_space.innerHTML = sel_html;
            range.insertNode(insert_space);
        }
        else {
            console.log('удаление части');
            let arrayText = range.startContainer.parentNode.innerText.replace(new RegExp("\r\n",'g'), '<br>').replace(new RegExp("\n",'g'), '<br>').replace(new RegExp(" <br>",'g'), '<br>').split(sel_string.trim().replace(new RegExp("\r\n",'g'), '<br>').replace(new RegExp("\n",'g'), '<br>').replace(new RegExp(" <br>",'g'), '<br>'));
            if(range.startContainer.parentNode.tagName == key.toUpperCase()){
                console.log('у1');
                //если родительский елемент nedittext
                $(range.startContainer.parentNode).remove();
                if (range.startContainer.parentNode.innerText != sel_string) {
                    let text = document.createElement(key);
                    if (text && arrayText[1] != undefined) {
                        text.innerHTML = arrayText[1];
                        range.insertNode(text);
                    }
                    if (sel_string && sel_string != undefined){
                        let text = document.createElement('text');
                        $(text).append(sel_html);
                        range.insertNode(text);
                    } 
                    let text2 = document.createElement(key);
                    if (text2 && arrayText[0] != undefined) {
                        text2.innerHTML = arrayText[0];
                        range.insertNode(text2);
                    }
                }
            }
            else
            {
                console.log('у2');
                //если родительский елемент не nedittext
                let text = document.createElement('text'),
                    textWrap = document.createElement('text');

                //1й этап добавляем text для диапазона
                $(text).append(sel_html);
                range.deleteContents();
                range.insertNode(text);

                //2-й этап парсим html и добавляем nedittext
                let delimetr = text;
                text = $(parentStartN).html();
                let arrayText = text.split(delimetr.outerHTML);
                for(let i=0; i<arrayText.length; i++){
                    arrayText[i] = '<nedittext>'+arrayText[i]+'</nedittext>';
                }
                text = arrayText[0] + delimetr.innerHTML + arrayText[1];
                $(textWrap).html(text);
                $(parentStartN).remove();
                range.insertNode(textWrap);
            }
        }
        //добавление атрибута запрета редактирования
        $('nedittext').attr('contenteditable', false);
        //удаление временного селектора text
        $('.cardDogovor-boxViewText text').replaceWith(function(){
            return $(this).html();
        });
        //премешение курсора направо
        selection.modify("move", "right", "character");
    }else{
        formatDoc(key);
    }
}

function formatSelectTitle(id_name) {
    // получаем выделенный текст
    let selection = window.getSelection();
    let range = selection.getRangeAt(0);
    let sel_string = selection.toString();
    // на основе id выбираем подстановку
    let key = id_name.replace('btn-', '');
    let arrTegs = {
        title: 'h4',
    }

    let parentStartN = $(range.startContainer.parentElement).parents('nedittext').eq(0);
    let parentEndN = $(range.endContainer.parentElement).parents('nedittext').eq(0);
    if(
        range.startContainer.parentElement.tagName != "NEDITTEXT" &&
        range.endContainer.parentElement.tagName != "NEDITTEXT" &&
        (parentStartN.length!==1 && parentEndN.length!==1)
    ){
        if(range.startContainer.parentElement.tagName != arrTegs[key].toUpperCase()){
            // удаляем его, что бы замнить
            range.deleteContents();
            let insert_space = document.createElement(arrTegs[key]);
            insert_space.setAttribute('class', 'subtitle_contract');
            insert_space.setAttribute('style', 'font-family: Roboto, sans-serif;');
            insert_space.innerHTML = sel_string;
            range.insertNode(insert_space);
        }else{
            let text = range.startContainer.parentNode.innerText;
            $(range.startContainer.parentNode).remove();
            range.insertNode(document.createTextNode(text));
        }
    }

    selection.modify("move", "right", "character");
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
    let seller =
    {
        NAME : document.getElementsByClassName('fullnameseller'),
        PHONE : document.getElementsByClassName('seller_PHONE'),
        PASSPORT : document.getElementsByClassName('seller_PASSPORT'),
        DIRECTOR_ID : document.getElementsByClassName('seller_DIRECTOR_ID'),
        INN : document.getElementsByClassName('seller_INN'),
        KPP : document.getElementsByClassName('seller_KPP'),
        OGRN : document.getElementsByClassName('seller_OGRN'),
        ADRESS : document.getElementsByClassName('seller_ADRESS'),
        INDEX : document.getElementsByClassName('seller_INDEX'),
        REGION : document.getElementsByClassName('seller_REGION'),
        CITY : document.getElementsByClassName('seller_CITY'),
        DISTRICT : document.getElementsByClassName('seller_DISTRICT'),
        LOCALITY : document.getElementsByClassName('seller_LOCALITY'),
        HOUSE : document.getElementsByClassName('seller_HOUSE'),
        OFFICE : document.getElementsByClassName('seller_OFFICE'),
        BANK :  document.getElementsByClassName('seller_BANK'),
        BIK : document.getElementsByClassName('seller_BIK'),
        RAS_ACCOUNT : document.getElementsByClassName('seller_RAS_ACCOUNT'),
        KOR_ACCOUNT :  document.getElementsByClassName('seller_KOR_ACCOUNT'),
        INN_BANK : document.getElementsByClassName('seller_INN_BANK'),
        DIRECTOR_NAME : document.getElementsByClassName('seller_DIRECTOR_NAME'),
        STAFF : document.getElementsByClassName('seller_STAFF')
    };

    let customer =
        {
            NAME : document.getElementsByClassName('fullnamecustomer'),
            PHONE : document.getElementsByClassName('customer_PHONE'),
            PASSPORT : document.getElementsByClassName('customer_PASSPORT'),
            DIRECTOR_ID : document.getElementsByClassName('customer_DIRECTOR_ID'),
            INN : document.getElementsByClassName('customer_INN'),
            KPP : document.getElementsByClassName('customer_KPP'),
            OGRN : document.getElementsByClassName('customer_OGRN'),
            ADRESS : document.getElementsByClassName('customer_ADRESS'),
            INDEX : document.getElementsByClassName('customer_INDEX'),
            REGION : document.getElementsByClassName('customer_REGION'),
            CITY : document.getElementsByClassName('customer_CITY'),
            DISTRICT : document.getElementsByClassName('customer_DISTRICT'),
            LOCALITY : document.getElementsByClassName('customer_LOCALITY'),
            HOUSE : document.getElementsByClassName('customer_HOUSE'),
            OFFICE : document.getElementsByClassName('customer_OFFICE'),
            BANK :  document.getElementsByClassName('customer_BANK'),
            BIK : document.getElementsByClassName('customer_BIK'),
            RAS_ACCOUNT : document.getElementsByClassName('customer_RAS_ACCOUNT'),
            KOR_ACCOUNT :  document.getElementsByClassName('customer_KOR_ACCOUNT'),
            INN_BANK : document.getElementsByClassName('customer_INN_BANK'),
            DIRECTOR_NAME : document.getElementsByClassName('customer_DIRECTOR_NAME'),
            STAFF : document.getElementsByClassName('customer_STAFF')
        };


    //let type_user = document.getElementById('step0_text');
    switch (idname) {
        case 'seller':
            $.each(seller, function(code, arObj){
                if(seller[code].length>0 && user_req[code]) {
                    for (var i = 0; i < seller[code].length; i++) {
                        seller[code][i].innerText = user_req[code].VALUE.replace(/&quot;/g, '"');;
                    }
                }
                if(customer[code].length>0 && user_req[code]){
                    for (var i = 0; i < customer[code].length; i++) {
                        customer[code][i].innerText = '['+ user_req[code].NAME +' Покупателя]'
                    }
                }
            });
            break;
        case 'customer':
            $.each(customer, function(code, arObj){
                if(seller[code].length>0 && user_req[code]) {
                    for (var i = 0; i < seller[code].length; i++) {
                        seller[code][i].innerText = '[' + user_req[code].NAME + ' Продавца]';

                    }
                }
                if(customer[code].length>0 && user_req[code]) {
                    for (var i = 0; i < customer[code].length; i++) {
                        customer[code][i].innerText = user_req[code].VALUE.replace(/&quot;/g, '"');;

                    }
                }
            });
            break;
        default:
            $.each(seller, function(code, arObj){
                if(seller[code].length>0 && user_req[code]) {
                    for (var i = 0; i < seller[code].length; i++) {
                        seller[code][i].innerText = user_req[code].VALUE.replace(/&quot;/g, '"');
                    }
                }
                if(customer[code].length>0 && user_req[code]) {
                    for (var i = 0; i < customer[code].length; i++) {
                        customer[code][i].innerText = '[' + user_req[code].NAME + ' Покупателя]'
                    }
                }

            });
            break;
    }

}

function setBackgraundStepBox() {
    let box = document.getElementsByClassName('t');
    for (var i = 0; i < box.length; i++) {
        box[i].style.backgroundColor = '#fff';

    }
}

// функции добавления удаления строк таблиц
function addRow(thisBtn, n){
    let tbody = thisBtn.parentElement.previousElementSibling.tBodies[0];
    let tr = document.createElement('tr');
    const num = tbody.rows.length + 1;
    const numTextNode = document.createTextNode(num);
    const td = document.createElement('td');
    td.append(numTextNode);
    tr.append(td);
    for (var i = 1; i < n; i++) {
        const td = document.createElement('td');
        tr.append(td);
    }
    tbody.append(tr);
}
function deleteRow(thisBtn){
    let collection = thisBtn.parentElement.previousElementSibling.tBodies[0].rows;
    collection[collection.length-1].remove();
}

//для кнопок редактирования тектса
function formatDoc(sCmd, sValue) {
    if (validateMode()) {
        document.execCommand(sCmd, false, sValue);
    }
}

//проверка что редактируемая обоасть активна и курсор в зоне редактора
function validateMode() {
    if($('.cardDogovor-boxViewText').attr('contenteditable') == 'true' && $(window.getSelection().focusNode).parents('.cardDogovor-boxViewText').length) {
        return true;
    }
    else{
        return true
    }
}

////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function() {

    setHeaderFullName();

    $('#select_type_user').on('change', function() {
        let value = $(this).val();
        setHeaderFullName(value);
    });

    $('.cardDogovor-boxViewText .link_template').on('click', function() {
        var category = $(this);
        var id_category = category.attr('data-id');
        var canvas_contr = $('.cardDogovor-boxViewText');
        var id_element = $('#save_btn').attr('data-id');

        // загружаем содержимое категории
        $.post(
            "/response/ajax/get_template_contract.php", {
                idcontract: id_category,
                id_element: id_element,
                type: 'new_dogovor',
                return_url: re_url
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
        // загружаем содержимое шаблона договора
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
        $('nedittext[contenteditable="false"]').each( function () {
            if ($(this).text() == "") {
                $(this).remove();
            }
            $(this).attr('blockedit', true);
        });
        let canvas_contr = $('.cardDogovor-boxViewText');
        let canvas_contr_context = String(canvas_contr.html());
        let id = $(this).attr('data-id');
        let isImg = $(this).hasClass('canvas-img');
        preload('show');
        console.log(isImg);
        // загружаем содержимое категории
        if(isImg){
            let arImg = $('#canvas').find('.document-img img');
            let arUrl = [];
            arImg.each(function (index, value) {
                arUrl[index] = $(value).attr('src');
            });
            $.post(
                "/response/ajax/up_contract_img.php", {
                    contect: arUrl,
                    id: id,
                    type: 'create_sdelka'
                },
                onAjaxSuccess
            );
        }
        else{
            $.post(
                "/response/ajax/up_contract_text.php", {
                    contect: canvas_contr_context,
                    id: id,
                    type: 'create_sdelka'
                },
                onAjaxSuccess
            );
        }


        function onAjaxSuccess(data) {
            // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
            console.log(data);
            let result = JSON.parse(data);
            console.log(result);
            if(result['TYPE']=='ERROR'){
                preload('hide');
                showResult('#popup-error','Ошибка сохранения', result['VALUE']);
            }
            if(result['TYPE']=='SUCCESS'){
                preload('hide');
                showResult('#popup-success', 'Договор добавлен');
                window.location.href = "/my_pacts/edit_my_pact/?ACTION=ADD&dogovor="+result['VALUE'];
            }

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

        if (canvas.hasClass("block")) canvas.removeClass("block");
        else canvas.addClass("block");

        if (onof) {
            $(this).css("backgroundColor", "#ff6416");
            $(span_icon).css("color", "#fff");
            $('.js-disabled').attr('disabled', false);
        } else {
            $(this).css("backgroundColor", "#fff");
            $(span_icon).css("color", "#ff6416");
            $('.js-disabled').attr('disabled', true);
        }
    });
    
    //установка даты
    $(document).on('click', '.js-btn-data', function() {
        if($('.cardDogovor-boxViewText').attr('contenteditable') == 'true' && $(window.getSelection().focusNode).parents('.cardDogovor-boxViewText').length) {
            /*var date_ins = new Date();
            insertTextAtCursor(date_ins.getDate() + '.' + date_ins.getMonth() + '.' + date_ins.getFullYear());*/
            //var data_ins = "<edbox contenteditable='false'>%DATE%</edbox>";
            var data_ins = "%DATE%";
            insertTextAtCursor(data_ins);
        }
    });


    $('#btn-data').on('click', function() {
        if($('.cardDogovor-boxViewText').attr('contenteditable') == 'true' && $(window.getSelection().focusNode).parents('.cardDogovor-boxViewText').length) {
            /*var date_ins = new Date();
            insertTextAtCursor(date_ins.getDate() + '.' + date_ins.getMonth() + '.' + date_ins.getFullYear());*/
            var date_ins = '%DATE%';
            insertTextAtCursor(date_ins);
        }
    });

    $('.form_text').on('click', function() {
        if($('.cardDogovor-boxViewText').attr('contenteditable') == 'true' && $(window.getSelection().focusNode).parents('.cardDogovor-boxViewText').length) {
            let id_name = $(this).attr('id');
            formatSelectText(id_name);
        }
    });

    $(document).on('click', '#btn-title', function(){
        if($('.cardDogovor-boxViewText').attr('contenteditable') == 'true' && $(window.getSelection().focusNode).parents('.cardDogovor-boxViewText').length) {
            let id_name = $(this).attr('id');
            formatSelectTitle(id_name);
        }
    });

    $('rededittext').on('click', function() {
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



        $('.cardDogovor').prepend("<div class='document-load'></div>");

        oData.append("CustomField", "This is some extra data");

        var oReq = new XMLHttpRequest();
        oReq.open("POST", "/response/ajax/phpword.php?uploadfiles", true);
        oReq.onload = function(oEvent) {
            if (oReq.status == 200) {
                let canvas = document.getElementById('canvas');
                let result = JSON.parse(oEvent.srcElement.response);

                for(let i=0; i<result.length; i++){
                    if(result[i].FORMAT=='png' || result[i].FORMAT=='jpg'){
                        if(!$(canvas).hasClass('canvas-img')){
                            $(canvas).addClass('canvas-img');
                            $('#save_btn').addClass('canvas-img');
                            //здесь блокирум инструменты при загрухке картинки.
                            $('.tools_redactor button:not(#save_btn)').attr('disabled', true);
                            canvas.innerHTML = result[i].CONTENT;
                        }
                        else{
                            canvas.insertAdjacentHTML('beforeend',result[i].CONTENT);
                        }

                        $('.tools_redactor').show();
                    }
                    else{
                        canvas.innerHTML = result[i].CONTENT;
                    }
                }
            } else {
                oOutput.innerHTML = "Error " + oReq.status + " occurred when trying to upload your file.<br \/>";
            }
            $('.tools_redactor').show();
            $('.document-load').remove();
        };

        oReq.send(oData);
        ev.preventDefault();
    }, false);

function insertAfter(elementInstertAfter, parentElement) {
	return parentElement.parentNode.insertBefore(elementInstertAfter, parentElement.nextSibling);
}

    var uploadbtn = document.getElementById("uploadbtn");
        uploadbtn.addEventListener('change', e => {
        let label = e.target.labels[0];
        label.innerText = 'Заменить файл';
        label.classList.add("btn-nfk-invert");
        if(e.target.nextSibling.nodeName == "P31")
            e.target.nextSibling.remove();
        var file_name = document.createElement("p31");
        file_name.innerText = e.target.files[0].name;
        insertAfter(file_name, e.target);
        let load_contract = document.getElementById("load-contract");
        let label_lc = document.querySelector(`[for="load-contract"]`);
        load_contract.removeAttribute("disabled");
        label_lc.classList.remove("disabled");
    })

    $(document).on('click touchstart', '.cardDogovor-boxViewText', function() {
        $('.tools_redactor .btn-nfk-invert').removeClass('btn-nfk-invert');
        // получаем выделенный текст
        let selection = window.getSelection();
        let range = selection.getRangeAt(0);
        let sel_string = selection.toString();
        if(sel_string){
            if(range.startContainer.parentElement.tagName == 'B' && range.endContainer.parentElement.tagName == 'B'){
                $('#btn-bold').addClass('btn-nfk-invert');
            }
            if(range.startContainer.parentElement.tagName == 'I' && range.endContainer.parentElement.tagName == 'I'){
                $('#btn-italic').addClass('btn-nfk-invert');
            }
            if(range.startContainer.parentElement.tagName == 'NEDITTEXT' && range.endContainer.parentElement.tagName == 'NEDITTEXT'){
                $('#btn-nedittext').addClass('btn-nfk-invert');
            }
            if(range.startContainer.parentElement.tagName == 'H4' && range.endContainer.parentElement.tagName == 'H4'){
                $('#btn-title').addClass('btn-nfk-invert');
            }
        }
    });

    $(document).on('click', '#create_con', function(){
        $('.tools_redactor').show();
        $('#canvas').empty();
        $('#btn-edit').click();
    });

    // Блокировка удаления заблокированного текста
    $('#canvas').keydown(function (eventObject) {
        if (eventObject.which == 8 || eventObject.which == 46) {
            let selection = window.getSelection();
            if (eventObject.which == 8 && selection.focusNode.previousElementSibling !== null && selection.focusNode.previousElementSibling.tagName !== null && selection.focusOffset == 0){
                if(selection.focusNode.previousElementSibling.tagName == "NEDITTEXT"){
                    return false;
                }else if($(selection.focusNode.previousElementSibling).find('nedittext').length > 0){
                    var ndt_str = $(selection.focusNode.previousElementSibling).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '');
                    if(selection.focusNode.previousElementSibling.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(new RegExp('\\'+ndt_str+'$','ig')) !== null) return false;
                }
            }
            if (eventObject.which == 8 && selection.focusNode.previousSibling === null && selection.focusNode.parentElement.textContent == selection.focusNode.textContent && selection.focusNode.parentElement.previousSibling !== null && selection.focusNode.parentElement.previousSibling.tagName !== null && selection.focusOffset == 0){
                if(selection.focusNode.parentElement.previousElementSibling.tagName == "NEDITTEXT"){
                    return false;
                }else if($(selection.focusNode.parentElement.previousElementSibling).find('nedittext').length > 0){
                    var ndt_str = $(selection.focusNode.parentElement.previousElementSibling).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '');
                    if(selection.focusNode.parentElement.previousElementSibling.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(new RegExp('\\'+ndt_str+'$','ig')) !== null) return false;
                }
            }
            if (eventObject.which == 46 && selection.focusNode.nextSibling !== null && selection.focusNode.nextSibling.tagName !== null && selection.focusNode.nextSibling.tagName == "NEDITTEXT" && selection.focusOffset == selection.focusNode.length) return false;
            if (eventObject.which == 46 && selection.focusNode.nextSibling === null && selection.focusNode.parentElement.textContent == selection.focusNode.textContent && selection.focusNode.parentElement.nextSibling !== null && selection.focusNode.parentElement.nextSibling.tagName !== null && selection.focusOffset == selection.focusNode.length){
                if(selection.focusNode.parentElement.nextSibling.tagName == "NEDITTEXT")
                    return false;
                if($(selection.focusNode.parentElement.nextSibling).find('nedittext').length > 0){
                    var ndt_str = $(selection.focusNode.parentElement.nextSibling).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '');
                    if(selection.focusNode.parentElement.nextSibling.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(new RegExp('^\\'+ndt_str,'ig')) !== null) return false;
                }

            }
            
            //if (eventObject.which == 46 && selection.focusNode.nextElementSibling === null && selection.focusNode == selection.focusNode.length && selection.focusNode.parentElement.nextElementSibling !== null && (selection.focusNode.parentElement.nextElementSibling.childNodes[0].nodeName == "NEDITTEXT" || (selection.focusNode.parentElement.nextElementSibling.childNodes[0].nodeName == "#text" && selection.focusNode.parentElement.nextElementSibling.childNodes[0].textContent.trim() == ""))) return false;
            //if (eventObject.which == 8 && selection.anchorNode.previousElementSibling === null && selection.anchorOffset == selection.anchorNode.length && selection.focusNode.parentElement.previousElementSibling !== null && (selection.anchorNode.parentElement.previousElementSibling.childNodes[0].nodeName == "NEDITTEXT" || (selection.anchorNode.parentElement.previousElementSibling.childNodes[0].nodeName == "#text" && selection.anchorNode.parentElement.previousElementSibling.childNodes[0].textContent.trim() == ""))) return false;
            if ($(selection.focusNode).find('nedittext').length > 0 && selection.focusNode.textContent !== undefined){
                if (selection.focusNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '') == $(selection.focusNode).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '')) return false;
                var ndt_str = $(selection.focusNode).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '');
                if(eventObject.which == 8 && selection.focusNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(new RegExp('\\'+ndt_str+'$','ig')) !== null) return false;
                if(eventObject.which == 46 && selection.focusNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(new RegExp('^\\'+ndt_str,'ig')) !== null) return false;
            }
            //if ($(selection.focusNode).find('nedittext').length > 0 && selection.focusNode.textContent !== undefined && selection.focusNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '').match(/^\$(selection.focusNode).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '')/ig) == $(selection.focusNode).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '')) return false;
            //if (selection.focusNode.textContent !== undefined && selection.focusNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '') == '' && selection.focusNode.parentNode.id != "canvas" && $(selection.focusNode.parentNode).find('nedittext').length > 0 && selection.focusNode.parentNode.textContent.trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '') == $(selection.focusNode.parentNode).find('nedittext').text().trim().replace(new RegExp("\n",'g'), '').replace(new RegExp("\r",'g'), '')) return false;
            let range = selection.getRangeAt(0);
            let sel_html = range.cloneContents();
            if (sel_html.childNodes.length > 0){
                if($(sel_html.childNodes).find('NEDITTEXT').length > 0){
                    return false;
                }
                for(var i in sel_html.childNodes){
                    if(sel_html.childNodes[i].tagName !== null && sel_html.childNodes[i].tagName == "NEDITTEXT"){
                        return false;
                    }
                }
            }
            if (sel_html.firstElementChild !== null && sel_html.firstElementChild.tagName == "NEDITTEXT") return false;
            if (sel_html.lastElementChild !== null && sel_html.lastElementChild.tagName == "NEDITTEXT") return false;
        }
        let arKey = [32, 49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 189, 187, 81, 87, 69, 82, 84, 89, 85, 73, 79, 80, 219, 221, 65, 83, 68, 70, 71, 72, 74, 75, 76, 186, 222, 220, 226, 90, 88, 67, 86, 66, 78, 77, 188, 190, 191, 111, 106, 109, 103, 104, 105, 107, 100, 101, 102, 97, 98, 99, 96, 110, 13, 192];
        if (arKey.indexOf( eventObject.which ) != -1) {
            let selection = window.getSelection();
            let range = selection.getRangeAt(0);
            let sel_html = range.cloneContents();
            if (sel_html.childNodes.length > 0){
                if($(sel_html.childNodes).find('NEDITTEXT').length > 0){
                    return false;
                }
                for(var i in sel_html.childNodes){
                    if(sel_html.childNodes[i].tagName !== null && sel_html.childNodes[i].tagName == "NEDITTEXT"){
                        return false;
                    }
                }
            }
        }
    });

});