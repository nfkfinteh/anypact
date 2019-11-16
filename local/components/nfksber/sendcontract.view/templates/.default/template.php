<? // print_r($arResult["PROPERTY"]) ;?>
<?// print_r($arResult) ;?>
<!--------------------------------------1-------------------------------------------------->
<div>
        <h1 class="mb-4">Договор ожидающий моей подписи</h1>
        <div class="row pt-2 mb-5 pb-5">
            <div class="col-md-4 col-sm-12">
                <h3 class="font-weight-bold">Файлы</h3>
                <ul class="list-document">
                    <li class="icon-document">
                        <span>Договор №1</span>                        
                        <button class="btn btn-nfk" id="sign_contract" data-id="<?=$_GET['ID']?>" data-user="<?=$arResult["ID_USER"]?>" style="width:100%">Подписать договор</button>
                        <a class="btn btn-nfk" href="/my_pacts/send_contract/edit/?ID=<?=$arResult["ID"]?>" style="width:100%; min-height: 47px; margin-top: 26px; padding: 10px;">Внести изменения</a>
                        <button class="btn btn-nfk" id="recall_sign" data="<?=$_GET['ID']?>" data-user="1" style="width:100%">Отклонить</button>
                    </li>
                </ul>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="d-flex">
                    <h3 class="font-weight-bold flex-grow-1" style="flex-grow: 1!important;">Просмотр файла</h3>
                    <a href="contract_pdf.php?ID=<?=$_GET['ID']?>" target="_blank" class="btn-img" id="download_pdf"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-pdf-gray.png" alt=""></a>
                    <button class="btn-img"><img src="<?=SITE_TEMPLATE_PATH?>/image/icon-printer-gray.png" alt=""></button>
                </div>
                <!--Поле просмотра договора-->
                <div class="w-100 mt-4" style="height: 1000px; background-color: #f1f4f4">
                    <div style="wight:100%" id="canvas_view_text">
                        <?if(!empty($arResult["CONTRACT_TEXT"]['IMG'])):?>
                            <?foreach ($arResult["CONTRACT_TEXT"]['IMG'] as $item):?>
                                <div class="document-img" style="text-align: center">
                                    <img src="<?=CFile::GetPath($item)?>">
                                </div>
                                <br>
                            <?endforeach?>
                        <?else:?>
                            <?=$arResult['CONTRACT_TEXT']['TEXT']?>
                        <?endif?>
                        <?=$arResult['SEND_BLOCK']['TEXT']?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!---------------------------------------------------------------------------------------->
<noindex>
    <div id="send_sms" class="bgpopup" >
        <div class="container">
        <div class="row align-items-center justify-content-center">            
            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <div class="regpopup_win">     
                <div id="signpopup_close">Х</div>                                       
                        <!--форма подписания-->
                        <div class="regpopup_autorisation" id="regpopup_autarisation">
                            <label for="smscode" style="margin-bottom: 1.5rem;">
                                <span>Внимание!
                                    <p>Удостоверьтесь в том, что Вам полностью понятны условия, подписываемых Вами Документов.</p> 
                                    <p>При нажатии на кнопку «Подписать» Вы будете перенаправлены на сайт Госуслуг.</p> 
                                    <p>Успешная авторизация на сайте Госуслуг будет означать выражение Вашей воли на подписание Документов и совершение указанной в них сделки (сделок) в понимании ст. 160 ГК РФ.</p>
                                </span>
                            </label>
                            <button class="btn btn-nfk" id="sign_contract" data="<?=$_GET['ID']?>" data-user="1" style="width:45%;margin-right: 30px;">Подписать</button>
                            <button class="btn btn-nfk" id="recall_sign" data="<?=$_GET['ID']?>" data-user="1" style="width:45%">Отклонить</button>
                        </div>                        
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</noindex>
<!--
<noindex>
    <div id="send_sms" class="bgpopup" >
        <div class="container">
        <div class="row align-items-center justify-content-center">            
            <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                <div class="regpopup_win">                        
                        <div class="regpopup_autorisation" id="regpopup_autarisation">
                            <label for="smscode">
                                <span>Вам отправлен sms-код</span>
                                <img src="https://shop.nfksber.ru/local/templates/main/images/card/clock.png" style = "width: 18px; margin: 0 5px 0 10px;" />
                                <span id="timer" class=""><span id="timer_n" id-con="<?=$arResult['DOGOVOR_ID']?>" id-cont="<?=$arResult['USER_ID']?>">80</span> сек.</span>
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
-->