<? // print_r($arResult["PROPERTY"]) ;?>
<? //print_r($arResult) ;?>
<style>
        .btn-img{
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 0;
            padding: 0;
            margin-left: 20px;
            width: 37px;
        }
        .btn-img:hover{
            padding-top: 5px;
        }
        ul.list-document{
            padding: 0;
        }
        ul.list-document li{
            border-bottom: 2px solid #f2f2f2;
            list-style: none;
            display: flex;
            align-items: center;
            cursor: pointer;
            flex-wrap: wrap;
            padding-top: 20px;
            padding-bottom: 22px;
            transition: all 1s ease;
        }
        ul.list-document li:hover{
            color: #ff6416;
        }
        ul.list-document li:last-child{
            border:none;
        }
        ul.list-document li::before{
            content: "";
            background-image: url(image/icon-document-sprite.png);
            background-repeat: no-repeat;
            display: inline-block;
            width: 22px;
            height: 28px;
            background-position: -5px -5px;
            margin-right: 23px;
            margin-left: 23px;
        }
        ul.list-document li:hover::before{
            background-position: -37px -5px;
        }
        ul.list-document button{
            min-height: 47px;
            margin-top: 26px;
            margin-bottom: 8px;
            display: block;
        }
        ul.list-document li:hover button{
            display: block;
        }

     #canvas_view_text{
            padding: 20px 10px 10px 30px;
            overflow: hidden auto;
            height: 100%;
        }
    </style>
    <div>
        <h1 class="mb-4">Подписанные договора</h1>
        <div class="row pt-2 mb-5 pb-5">
            <div class="col-md-4 col-sm-12">
                <h3 class="font-weight-bold">Файлы</h3>
                <ul class="list-document">
                    <li class="icon-document">
                        <span>Договор №1</span>
                        <button class="btn btn-nfk-invert w-100">Подписан</button>
                    </li>
                    <!--
                    <li class="icon-document">
                        <span>Спецификация №1</span>
                        <button class="btn btn-nfk-invert w-100">Подписан</button>
                    </li>
                    <li class="icon-document">
                        <span>Спецификация №2</span>
                        <button class="btn btn-nfk-invert w-100">Подписан</button>
                    </li>
                    <li class="icon-document">
                        <span>Доп. соглашение</span>
                        <button class="btn btn-nfk-invert w-100">Подписан</button>
                    </li>
                    -->
                </ul>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="d-flex">
                    <h3 class="font-weight-bold flex-grow-1">Просмотр файла</h3>
                    <button class="btn-img"><img src="image/icon-pdf-gray.png" alt=""></button>
                    <button class="btn-img"><img src="image/icon-printer-gray.png" alt=""></button>
                </div>
                <!--Поле просомтра договора-->
                <div class="w-100 mt-4" style="height: 1000px; background-color: #f1f4f4">
                    <div style="wight:100%" id="canvas_view_text">
                        <?=$arResult['CONTRACT_TEXT']?>
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