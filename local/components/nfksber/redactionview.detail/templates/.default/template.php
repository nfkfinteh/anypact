<?
include_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/compareFile/compare.php';
$compareDogovor = getTextDiff($arResult["ELEMENT"]["PREVIEW_TEXT"], $arResult["ELEMENT"]["DETAIL_TEXT"], ' ');
$dogovorDisplayChange = $compareDogovor[1];
//$arResult['SEND_CONTRACT'] = 'N';
switch ($arResult['SEND_CONTRACT']) {
    // Процедура подписания
    case 'N':
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
                        <?//if($arResult["USER_ID"] != $arResult['PROPERTY']['USER_ID_INITIATOR']['VALUE']):?>
                            <?//if($arResult["USER_ID"] != $arResult['SIGN_DOGOVOR']['UF_ID_USER_B']):?>
                                <button class="btn btn-nfk" id="popup_send_contract" data="signed">Подписать договор</button>
                            <?//endif?>
                            <?/*?><button class="btn btn-nfk" id="new_redaction" data-id_element="<?=$_GET['ID']?>">Изменить редакцию</button><?*/?>
                            <a href="/my_pacts/send_redaction/edit/?ID=<?=$_GET['ID']?>" class="btn btn-nfk" >Изменить редакцию</a>
                            <button class="btn btn-nfk" id="recall_sign" data="<?=$_GET['ID']?>" data-user="1" style="width:100%">Отклонить</button>
                        <?//endif?>

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
                    <div class="cardDogovor-boxViewText block" id="canvas" contenteditable="false">
                        <?=$dogovorDisplayChange?>
                    </div>
                <?endif?>
            </div>            
        </div>
    </div>
<?/*
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
<?*/?>
    <!-- окно предупреждения подписания по ЕСИА -- -->
    <noindex>
        <div id="send_sms" class="bgpopup" >
            <div class="container">
                <div class="row align-items-center justify-content-center">            
                    <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                        <div class="regpopup_win">     
                            <div id="signpopup_close">Х</div>                                       
                            <!--форма подписания-->
                            <div class="regpopup_autorisation" id="regpopup_autarisation">
                                <?
                                    if (COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y") {
                                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/global_sign/attantion_sign_whit_pass.php", Array());
                                    }else{
                                        $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/global_sign/attantion_sign.php", Array());
                                    }
                                ?>
                                <? //путь для возврата на эту страницу
                                    $returnURL = base64_encode($_SERVER['REQUEST_URI']);
                                    if (COption::GetOptionString("anypact", "block_gosuslugi", "Y") == "Y") {
                                        ?>
                                        <a href="#" class="btn btn-nfk" id="reg_button_deal" style="width:45%;">Подписать</a>
                                        <?
                                    }else{
                                ?>
                                    <a href="http://anypact.ru/profile/aut_esia.php?returnurl=<?=$returnURL?>" class="btn btn-nfk" id="ref_esia" style="width:45%;">Подписать</a>
                                <?}?>
                                <button class="btn btn-nfk" id="close_sign_popup" style="width:45%">Отклонить</button>
                     
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </noindex>
    <!-- \\окно предупреждения подписания по ЕСИА -->   
<?break;
// контракт подписан
case 'Y':
    ?>
        <noindex>
            <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5">
                <img src="<?=SITE_TEMPLATE_PATH?>/image/ok_send.png" alt="Необходима регистрация">
                <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Ваша подпись поставлена!</h3>
                <p>Сейчас автоматически откроется страница с вашими договорами.</p>
                <p>Если страница не открылась перейдите самостоятельно по ссылке <a href="/my_pacts/">/my_pacts/</a></p>      
            </div>
            <script>
               $(document).ready(function() {
                    console.log('Редирект начало');
                    setTimeout(function () {
                        replaceMypact();
                    }, 7000);
    
                    function replaceMypact(){
                        console.log('Редирект');
                        location.replace('/my_pacts/');
                    }
                });
            </script>
        <noindex>    
    <?break;
        // ошибка ид ЕСИА несовпадает с ИД в профиле
        case 'ERR_ID':
    ?>
        <noindex>
            <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5">
                <img src="<?=SITE_TEMPLATE_PATH?>/image/err_send.png" alt="Необходима регистрация">
                <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Ошибка подписания!</h3>
                <p>Учетная запись на «Госуслугах» не совпадает с вашим профилем.</p>       
            </div>
        <noindex>
    <? break; 
    } 
    ?>