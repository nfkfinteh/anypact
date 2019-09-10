<?
/*include_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/compareFile/compare.php';
$compareDogovor = SelectedDiffs($arResult["ELEMENT"]["PREVIEW_TEXT"], $arResult["ELEMENT"]["DETAIL_TEXT"], $text1, $text2);*/
?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool">
                <?/*
                <?if ($arResult["USER_ID"] == $arResult["PROPERTY"]["PACT_USER"]["VALUE"]){
                   ?>
                    <h3>Это ваш договор:</h3>
                    <a herf="/my_pacts/" class="btn btn-nfk" > Внести изменения </a>
                   <?
                }else {?>
                */?>
                    <?if($arResult["USER_ID"] != $arResult['SIGN_DOGOVOR']['UF_ID_USER_B']):?>
                        <button class="btn btn-nfk" id="send_contract" >Подписать договор</button>
                    <?endif?>

                    <?if($arResult["USER_ID"] != $arResult['PROPERTY']['USER_ID_INITIATOR']['VALUE']):?>
                        <button class="btn btn-nfk" id="new_redaction" data-id_element="<?=$_GET['ID']?>">Изменить редакцию</button>
                    <?endif?>

                <?//}?>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12 js-dogovor">
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
                                <span id="timer" class="">
                                    <span id="timer_n" id-iblock="<?=$arResult['INFOBLOCK_ID']?>" id-con="<?=$arResult["ELEMENT_ID"]?>"
                                                                id-cont="<?=$arResult['USER_ID']?>">80</span> сек.
                                </span>
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