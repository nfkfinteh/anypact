<?  //print_r($arResult["TEXT_CONTRACT"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool cardPact">
                <h5>Создать договор из наших шаблонов</h3>
                <p>В правой части выберите тему договора:</p>
                <button class="btn btn-nfk" id="template_con">Загрузить договор из шаблона</button>                
                <?if(!empty($arResult["TEMPLATE_CONTENT"])){ ?>
                    <div id="head_c">
                        <select class="form-control form-control-lg">
                            <option>Я Продавец</option>
                            <option>Я Покупатель</option>
                        </select>
                    </div>
                    <div class="steps">
                        <div class="t" id="step1"><div class="number">1</div>Условия договора</div>
                        <div class="t" id="step2"><div class="number">2</div>Информация о транспортном средстве</div>
                        <div class="t" id="step3"><div class="number">3</div>Порядок оплаты</div>
                        <div class="t" id="step4"><div class="number">4</div>Реквизиты договора</div>
                    </div>
                <?}?>
                <h5>Загрузить договор из вашего файла</h3>
                <p>поддерживаемые форматы(docx, rtf, txt)</p>
                <form enctype="multipart/form-data" method="post" name="loadcontract"> 
                    <label for="uploadbtn" class="btn btn-nfk" id="">Выберите собственный файл</label>
                    <input id="uploadbtn" type="file" name="file"  accept="docx/*" capture="camera" multiple="" required value="Сделать фото" style="display:none;"/>
                    <label for="load-contract" class="btn btn-nfk" id="btn-load">Загрузить в редактор</label>
                    <input type="submit" value="Обработать" id="load-contract" style="display:none;"/>
                </form>
                <h5>Создать договор используя наш редактор</h5>
                <p>Нажмите на кнопку "Создать договор в редакторе" и в поле справа наберайте текст используя инструменты для форматирования.</p>
                <button class="btn btn-nfk" id="create_con">Создать договор в редакторе</button>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="tools_redactor">
        <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn"><span class="glyphicon glyphicon-floppy-disk"></span></button>
                <!--<button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить"><span class="glyphicon glyphicon-print"></span></button>-->
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false"><span class="glyphicon glyphicon-pencil"></span></button>                
                <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Вставить изображение"><span class="glyphicon glyphicon-picture"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text"  id="btn-noedit" data-toggle="tooltip" data-placement="left" title="Запретить редактирование выделенного текста"><span class="glyphicon glyphicon-ban-circle"></span></button>
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-data" data-toggle="tooltip" data-placement="left" title="Вставить подстановку текущей даты"><span class="glyphicon glyphicon-calendar"></span></button>                
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-weight" data-toggle="tooltip" data-placement="left" title="Жирный текст" contenteditable="false"><span class="glyphicon glyphicon-bold"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text space_right" id="btn-italic" data-toggle="tooltip" data-placement="left" title="Курсив" contenteditable="false"><span class="glyphicon glyphicon-italic"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-question" data-toggle="tooltip" data-placement="left" title="Информация по инструментам" contenteditable="false"><span class="glyphicon glyphicon-question-sign"></span></button>
            </div>
            <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">

            <?if(!empty($arResult["TEMPLATE_CONTENT"])){ ?>                
                <? echo $arResult["TEMPLATE_CONTENT"]["DETAIL_TEXT"] ;
            }else {?>            
                <h3>Вы можете загрузить договор из шаблона или из вашего файла</h3>
                    <?foreach ($arResult["THREE_TEMPLATE"] as $Item_three){?>
                        <?if($Item_three["DEPTH_LEVEL"]==1){?>
                            <span data-id="<?=$Item_three["ID"]?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/folder_contract.png" /><?=$Item_three["NAME"]?></span>
                        <?}elseif ($Item_three["DEPTH_LEVEL"]==2){?>
                            <span data-id="<?=$Item_three["ID"]?>" style="padding-left: 35px;"><img src="<?=SITE_TEMPLATE_PATH?>/img/folder_contract.png" /><?=$Item_three["NAME"]?></span>
                        <?}elseif ($Item_three["DEPTH_LEVEL"]==3){?>
                            <span data-id="<?=$Item_three["ID"]?>" style="padding-left: 70px;"><img src="<?=SITE_TEMPLATE_PATH?>/img/folder_contract.png" /><?=$Item_three["NAME"]?></span>
                        <?}?>
                    <?}?>
                <?}?>
            </div>
        </div>            
    </div>
</div>