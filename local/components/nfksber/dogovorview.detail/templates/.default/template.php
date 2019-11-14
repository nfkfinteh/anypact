<?
/*
    $SendUserProperty  данные ползователя еоторый подписывает договор
*/

$SendUserProperty = $arResult["USER_PROP"];

/*
    $arrayNameParams массив подстановки названий в реквизиты таблицы
*/

$arrayNameParams = array(
    "fio" => "ФИО",
    "adress" => "Адрес",
    "phone" => "Телефон",
    "passport" => "Паспорт",
    "pay_params" => "Платежные реквизиты"
);
?>
<!-- <pre>
<? //print_r($arResult["USER_PROP"]);?>
</pre> -->
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool">
                <?if ($arResult["USER_ID"] == $arResult["PROPERTY"]["PACT_USER"]["VALUE"]){
                   ?>
                    <h3>Это ваш договор:</h3>
                    <a herf="/my_pacts/" class="btn btn-nfk" > Внести изменения </a>
                   <?
                }else {?>
                    <? // блокировка кнопки от повторного подписания темже пользователем
                    //if($arResult["USER_ID"] != $arResult['SIGN_DOGOVOR']['UF_ID_USER_B']):                    
                    ?>
                        <? if(isset($arResult['PROPERTY']["INCLUDE_FILES"])){?>
                            <? foreach($arResult['PROPERTY']["INCLUDE_FILES"] as $Unclude_file){?>
                                <a href="<?=$Unclude_file["URL"]?>" class="cardPact-rightPanel-url" target="_blank" style="padding: 20px 0; display:block;">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/icon-contract.png"> Дополнительный файл
                                </a>
                            <?}?>
                        <? }?>
                        <button class="btn btn-nfk <?if(!empty($arResult["DOGOVOR_IMG"][0]['URL'])):?>canvas-img<?endif?>" id="send_contract" data="signed">
                            Подписать договор
                        </button>
                    <? // endif?>                    
                    <?if(empty($arResult['NEW_REDACTION'])):?>
                        <button class="btn btn-nfk" id="new_redaction" data-id_element="<?=$_GET['ELEMENT_ID']?>">Предложить свою редакцию</button>
                    <?endif?>

                <?}?>
            </div>            
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12 js-dogovor">
            <?/*
            <div class="tools_redactor">
                <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn"><span class="glyphicon glyphicon-floppy-disk"></span></button>
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false"><span class="glyphicon glyphicon-pencil"></span></button>                                    
            </div>
              */?>
            <? // если договор в сканах или файлах изображений ?>
            <?if(!empty($arResult["DOGOVOR_IMG"][0]['URL'])):?>
                <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">
                    <?foreach ($arResult["DOGOVOR_IMG"] as $item):?>
                        <div class="document-img" style="text-align: center">
                            <img src="<?=$item['URL']?>">
                        </div>
                        <br>
                    <?endforeach?>
                </div>
            <?else:?>
                <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">
                    <?=$arResult["CONTRACT_PROPERTY"]["CONTRACT"]["DETAIL_TEXT"]?>
                    <?
                    // вывод видимых реквизитов пользователя                    
                    $userProperty = json_decode($arResult["CONTRACT_PROPERTY"]["CONTRACT_PROPERTY"]["USER_PROPERTY"]["VALUE"], true);                    
                    ?>
                    <table cellpadding="5" border="1" bordercolor="#cecece" cellspacing="0" width="100%">
                        <?
                        if(!empty($userProperty)) {
                            print_r($userProperty);
                            foreach($userProperty as $key => $Item){
                                if($Item["view"] == "Y"){	
                                    ?>
                                        <tr>
                                            <td style="width: 20%;padding: 5px;"><b><?=$arrayNameParams[$key]?></b></td>
                                            <td style="width: 30%;padding: 5px;">
                                                <?
                                                    foreach ($Item["params"] as $value) {
                                                        echo $value.", ";
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 20%;padding: 5px;"><b><?=$arrayNameParams[$key]?></b></td>
                                            <td style="width: 30%;padding: 5px;"></td>
                                        </tr>
                                    <? 
                                }else { ?>
                                    <tr>
                                        <td style="width: 20%;padding: 5px;"><b><?=$arrayNameParams[$key]?></b></td>
                                        <td style="width: 30%;padding: 5px;">
                                        {данные будут доступны после подписания договора}
                                        </td>
                                        <td style="width: 20%;padding: 5px;"><b><?=$arrayNameParams[$key]?></b></td>
                                        <td style="width: 30%;padding: 5px;"></td>
                                    </tr>
                                <? }
                            }
                        }?>
                    </table>
                </div>
            <?endif?>
        </div>            
    </div>
</div>
<noindex>
    <div id="send_sms" class="bgpopup" >
        <div class="container">
        <div class="row align-items-center justify-content-center">            
            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <div class="regpopup_win">                                            
                        <!--форма подписания-->
                        <div class="regpopup_autorisation" id="regpopup_autarisation">
                            <label for="smscode">
                                <span>Вам отправлен sms-код</span>
                                <img src="https://shop.nfksber.ru/local/templates/main/images/card/clock.png" style = "width: 18px; margin: 0 5px 0 10px;" />
                                <span id="timer" class=""><span id="timer_n" id-con="<?=$arResult["CONTRACT_PROPERTY"]["CONTRACT"]["ID"]?>" id-cont="<?=$arResult['USER_ID']?>">80</span> сек.</span>
                            </label>                            
		                    <input class="regpopup_content_form_submit" id="smscode" name="logout_butt" value="" maxlength="6">
	                    </div>                        
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</noindex>