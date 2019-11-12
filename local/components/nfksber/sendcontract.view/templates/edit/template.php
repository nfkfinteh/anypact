<? // print_r($arResult["TEMPLATE_CONTENT_PROPERTY"]) ;?>
<? //print_r($arResult["ID_USER"]) ;
    $ID_item = $_GET["ID"];
    $ID_USER = $arResult["ID_USER"];
?>
<script>
var Contract = {
    idItem: '<?=$ID_item?>',
    idUser: '<?=$ID_USER?>'    
}
</script>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool" style="margin: 8px 0;">
                <button class="btn btn-nfk" id="send_edit_contract" data="">Подписать договор</button>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="tools_redactor">
                <!-- <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn" data-id="">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                </button> -->
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
                    <?=$arResult['CONTRACT_TEXT']['TEXT']?>
                </div>
            <?endif?>
        </div>            
    </div>
</div>