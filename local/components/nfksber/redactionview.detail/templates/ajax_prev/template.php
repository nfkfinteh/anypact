<?/*?>
<div class="tools_redactor">
    <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn"
            data-id="<?=$arResult['PROPERTY']['ID_PACT']['VALUE']?>"
            data-redaction="<?=$arResult['ELEMENT_ID']?>"
    >
        <span class="glyphicon glyphicon-floppy-disk"></span>
    </button>
    <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false">
        <span class="glyphicon glyphicon-pencil"></span>
    </button>
</div>
<?if(!empty($arResult["DOGOVOR_IMG"][0]['URL'])):?>
    <div class="cardDogovor-boxViewText">
        <?foreach ($arResult["DOGOVOR_IMG"] as $item):?>
            <div class="document-img" style="text-align: center">
                <img src="<?=$item['URL']?>">
            </div>
            <br>
        <?endforeach?>
    </div>
<?else:?>
    <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">
        <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
    </div>
<?endif?>

<script>
    $(document).ready(function () {

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

        // разрешение редактирование текста
        $('#btn-edit').on('click', function() {
            var canvas = $('#canvas');
            var onof = on_contenteditable(canvas);
            var span_icon = $(this).find('span');

            if (onof) {
                $(this).css("backgroundColor", "#ff6416");
                $(span_icon).css("color", "#fff");
            } else {
                $(this).css("backgroundColor", "#fff");
                $(span_icon).css("color", "#ff6416");
            }
        });

        $(document).on('click touchstart', '#save_btn', function() {
            let canvas_contr = $('.cardDogovor-boxViewText');
            let canvas_contr_context = String(canvas_contr.html());
            let id = $(this).attr('data-id');
            let redaction = $(this).attr('data-redaction');
            // загружаем содержимое категории
            $.post(
                "/response/ajax/up_contract_redaction_text.php", {
                    contect: canvas_contr_context,
                    id: id,
                    redaction: redaction
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(data) {
                console.log(data);
                // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
                let result = JSON.parse(data);
                if(result['TYPE']=='ERROR'){
                    console.log(result['VALUE']);
                    alert(result['VALUE']);
                }
                if(result['TYPE']=='SUCCESS'){
                    console.log(result['VALUE']);
                    //alert(result['VALUE']);
                    window.location.href = "/my_pacts/";
                }

            }

        });

    });

</script>
<?*/?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <?if(empty($arResult['ERROR'])):?>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="cardDogovor-boxTool cardPact">
                    <h5>Вставить в договор:</h5>
                    <p>Вы можете вставить в текст договора автоподстановку следующих реквизитов:</p>
                    <button class="btn btn-nfk js-btn-rquised" data="signed">Таблица с реквизитами</button>
                    <button class="btn btn-nfk js-btn-fio" data="signed">Моё ФИО</button>
                    <button class="btn btn-nfk js-btn-address" data="signed">Мой Адрес</button>
                    <?/*<button class="btn btn-nfk js-btn-data" data="signed">Текущую дату</button>*/?>
                    <button class="btn btn-nfk js-btn-fio-contr" data="signed">ФИО Контрагента</button>
                    <button class="btn btn-nfk js-btn-adress-contr" data="signed">Адрес Контрагента</button>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="tools_redactor">
                <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn" data-id="<?=$arResult['PROPERTY']['ID_PACT']['VALUE']?>" data-redaction="<?=$arResult['ELEMENT_ID']?>">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                </button>
                <!--<button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить"><span class="glyphicon glyphicon-print"></span></button>-->
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false"><span class="glyphicon glyphicon-pencil"></span></button>
                <?/*<button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Вставить изображение"><span class="glyphicon glyphicon-picture"></span></button>*/?>
                <button type="button" class="btn btn-nfk btn-default form_text js-disabled"  id="btn-nedittext" data-toggle="tooltip" data-placement="left" title="Запретить редактирование выделенного текста" disabled><span class="glyphicon glyphicon-ban-circle"></span></button>
                <button type="button" class="btn btn-nfk btn-default space_right js-btn-data js-disabled" data-toggle="tooltip" data-placement="left" title="Вставить подстановку текущей даты" disabled><span class="glyphicon glyphicon-calendar"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text js-disabled" id="btn-bold" data-toggle="tooltip" data-placement="left" title="Жирный текст" contenteditable="false" disabled><span class="glyphicon glyphicon-bold"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text js-disabled" id="btn-italic" data-toggle="tooltip" data-placement="left" title="Курсив" contenteditable="false" disabled><span class="glyphicon glyphicon-italic"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text space_right js-disabled" id="btn-title" data-toggle="tooltip" data-placement="left" title="Заголовок" contenteditable="false" disabled><span class="glyphicon glyphicon-font"></span></button>
                <button type="button" class="btn btn-nfk btn-default" id="btn-question" data-toggle="tooltip" data-placement="left" title="Информация по инструментам" contenteditable="false"><span class="glyphicon glyphicon-question-sign"></span></button>
            </div>
                <?if(!empty($arResult["DOGOVOR_IMG"][0]['URL'])):?>
                    <div class="cardDogovor-boxViewText">
                        <?foreach ($arResult["DOGOVOR_IMG"] as $item):?>
                            <div class="document-img" style="text-align: center">
                                <img src="<?=$item['URL']?>">
                            </div>
                            <br>
                        <?endforeach?>
                    </div>
                <?else:?>
                    <div class="cardDogovor-boxViewText block" id="canvas" contenteditable="false">
                        <? echo $arResult["ELEMENT"]["DETAIL_TEXT"] ;?>
                    </div>
                <?endif?>
            </div>
        <?else:?>
            <div><?=$arResult['ERROR']?></div>
        <?endif?>
    </div>
</div>
<script type="text/javascript">
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

    var user_req =   <?=CUtil::PhpToJSObject($arResult['JS_DATA']['USER'])?>;
</script>