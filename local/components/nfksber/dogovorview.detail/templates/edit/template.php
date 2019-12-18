<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool cardPact">
                <h5>Вставить в договор:</h5>
				<p>Вы можете вставить в текст договора автоподстановку следующих реквизитов:</p>
				<button class="btn btn-nfk" data="signed">Таблица с реквизитами</button>
				<button class="btn btn-nfk" data="signed">Моё ФИО</button>
				<button class="btn btn-nfk" data="signed">Мой Адрес</button>
				<button class="btn btn-nfk" data="signed">Текущую дату</button>
				<button class="btn btn-nfk" data="signed">ФИО Контрагента</button>
				<button class="btn btn-nfk" data="signed">Адрес Контрагента</button>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="tools_redactor">
        <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn" data-id="<?=$arResult['ELEMENT_ID']?>">
            <span class="glyphicon glyphicon-floppy-disk"></span>
        </button>
                <!--<button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить"><span class="glyphicon glyphicon-print"></span></button>-->
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false"><span class="glyphicon glyphicon-pencil"></span></button>                
                <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Вставить изображение"><span class="glyphicon glyphicon-picture"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text"  id="btn-noedit" data-toggle="tooltip" data-placement="left" title="Запретить редактирование выделенного текста"><span class="glyphicon glyphicon-ban-circle"></span></button>
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-data" data-toggle="tooltip" data-placement="left" title="Вставить подстановку текущей даты"><span class="glyphicon glyphicon-calendar"></span></button>                
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-weight" data-toggle="tooltip" data-placement="left" title="Жирный текст" contenteditable="false"><span class="glyphicon glyphicon-bold"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text space_right" id="btn-italic" data-toggle="tooltip" data-placement="left" title="Курсив" contenteditable="false"><span class="glyphicon glyphicon-italic"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-question" data-toggle="tooltip" data-placement="left" title="Информация по инструментам" contenteditable="false"><span class="glyphicon glyphicon-question-sign"></span></button>
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
                    <? echo $arResult["TEMPLATE_CONTENT"]["DETAIL_TEXT"] ;?>
                </div>
            <?endif?>
        </div>            
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

    var full_name = {
        name: '<?= $arResult['JS_DATA']['USER']['NAME']?>',
        surname: '<?=$arResult['JS_DATA']['USER']['SURNAME']?>',
        midlname:'<?=$arResult['JS_DATA']['USER']['MIDLENAME']?>',
        phone: '<?=$arResult['JS_DATA']['USER']['PHONE']?>',
        passport: '<?=$arResult['JS_DATA']['USER']['PASSPORT']?>'
    }
</script>