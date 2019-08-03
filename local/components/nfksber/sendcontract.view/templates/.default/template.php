<? // print_r($arResult["PROPERTY"]) ;?>
<? //print_r($arResult) ;?>
<h4>Просмотр договора</h4>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
        <?=$arResult['SEND_BLOCK']['ID']?>
            <div class="cardDogovor-boxTool">
                <a href="/my_pacts/"> << Назад</a>
                <div class="tools_view_contract">
                    <a href="contract_pdf.php?ID=<?=$_GET['ID']?>" target="_blank" class="button_tool" id="download_pdf">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/downloading-file.svg" alt="">
                    </a>
                    <div class="button_tool">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/printer.svg" alt="">
                    </div>
                </div>
                <div class="view-pdf" style="border: #9E9E9E 13px solid; padding: 20px;">
                    <div style="wight:100%">
                        <?=$arResult['CONTRACT_TEXT']?>
                        <?=$arResult['SEND_BLOCK']['TEXT']?>
                    </div>
                </div>
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