<? //print_r($arResult["CONTRACT_PROPERTY"]["CONTRACT"]["ID"]) ;
   
?>
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
                    <button class="btn btn-nfk" id="send_contract" >Подписать договор</button>
                    <button class="btn btn-nfk">Предложить свою редакцию</button>                    
                <?}?>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12"> 
            <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">
                <?=$arResult["CONTRACT_PROPERTY"]["CONTRACT"]["DETAIL_TEXT"]?>
            </div>
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