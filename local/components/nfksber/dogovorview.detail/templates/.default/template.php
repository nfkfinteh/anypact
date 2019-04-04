<? // print_r($arResult["PROPERTY"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="cardDogovor-boxTool">
                <button class="btn btn-nfk" id="send_contract" >Подписать договор</button>
                <button class="btn btn-nfk">Предложить свою редакцию</button>
            </div>
        </div>               
        <div class="col-lg-8 col-md-8 col-sm-8"> 
            <div class="cardDogovor-boxViewText">
                <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
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
                                <span id="timer" class=""><span id="timer_n" id-con="<?=$arResult['ELEMENT_ID']?>" id-cont="<?=$arResult['USER_ID']?>">80</span> сек.</span>
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