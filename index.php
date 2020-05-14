<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Бесплатный сервис для дистанционного заключения сделок");
global $USER;
?>
<style>
        .form-card{
            border-radius: 5px;
            box-shadow: 1px 2px 10px rgba(0,0,0,0.2);
            margin-top: 38px;
            padding-top: 22px;
        }
        input, textarea{
            border-radius: 5px;
            background-color: #f2f3f5;
            border-color: #f2f3f5;
            width: 100%;
            min-height: 52px;
        }
        textarea{
            resize: none;
        }
        .send-btn{
            height: 46px;
            width: 262px;
            max-width: 100%;
        }
        .contact-container{
            margin-top: 90px;
        }
        .radio__label:before{content:' ';display:block;height:16px;width:16px;position:absolute;top:0;left:0;background: #f1f4f4;border:1px solid #e8e8e8;border-radius: 4px;}
        .radio__label:after{content:' ';display:block;height:8px;width:15px;position:absolute;top:1px;left:4px;}
        .radio__input{display: none;}
        .radio__input:checked ~ .radio__label:after{border-bottom:2px solid #ff6416;border-left:2px solid #ff6416;-ms-transform:rotate(-45deg);transform:rotate(-45deg);}
        .radio-transform{position:relative;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
        .radio__label:after:hover,.radio__label:after:active{border-color:green}
        .radio__label{margin-left:2.5rem;line-height:.75;    font-weight: 300;}
        #input-buffer{
            display: inline-block;
            /*position:*/
        }
        .pb-4, .py-4 {
            padding-bottom: 3.5rem!important;
        }
    </style>
<!--Форма поиска-->
<h2>Поиск людей, компаний и сделок</h2>
<?// компонент поисковой строки
$APPLICATION->IncludeComponent(
    "bitrix:search.form",
    "homepage",
    Array(
        "LOCATION" => $getGeo['cityName']
    )
);
?>
</div>
<? // вывод карты и поинтов на ней
$APPLICATION->IncludeComponent(
    "nfksber:yamap",
    "",
    Array(
        "CACHE_TIME" => 36000,
        "CACHE_TYPE" => "A",
        "COUNT_POINT" => "10",
        "IBLOCK_ID" => "3",
        "IBLOCK_TYPE" => "4",
        "LOCATION" => $getGeo['cityName'],
        "MAP_HEIGHT" => "500px",
        "MAP_WIDTH" => "100%"
    )
);
?>
<!-- О сервисе -->
<div class="container">
    <h2 style="margin-top:30px;">С сервисом AnyPact</h2>
    <div class="short-divider"></div>
    <p>Вы можете заключить договор в Сети прямо сейчас! Anypact позволяет найти контрагента, сформировать условия договора, подписать его и приобрести по нему все права и обязанности. Что бы Вы ни делали, покупали или продавали имущество, искали для себя надежного исполнителя работ или сами оказывали услуги - теперь Вам не нужно волноваться о том, будет ли исполнена сделка, которую Вы заключили через Интернет. Ваше соглашение приобретает юридическую силу.</p>
</div>
<div class="container container-slider">
    <div class="owl-carousel owl-theme cards-how">
        <div class="card-how-container">
            <a href="/service/#prosto" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-1"></i>
                    <h5>ПРОСТО</h5>
                    <p>Для заключения электронного договора Вам достаточно иметь подтверждённую учётную запись на портале Госуслуг.</p>
                </div>
            </a>
        </div>
        <div class="card-how-container">
            <a href="/service/#nadezhno" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-2"></i>
                    <h5>НАДЁЖНО</h5>
                    <p>Договоры с электронной подписью имеют такую же юридическую силу, как и бумажные, собственноручно подписанные документы.</p>
                </div>
            </a>
        </div>
        <div class="card-how-container">
            <a href="/service/#bezopasno" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-3"></i>
                    <h5>БЕЗОПАСНО</h5>
                    <p>Система обеспечивает защиту размещённой в ней информации в соответствии с законодательством Российской Федерации.</p>
                </div>
            </a>
        </div>
        <div class="card-how-container">
            <a href="/service/#udobno" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-4"></i>
                    <h5>УДОБНО</h5>
                    <p>Вы можете использовать готовый шаблон документа или изменить его согласно Вашим пожеланиям и требованиям.</p>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- Список категорий -->
<div class="deal-container">
    <div class="container">
        <h2>Заключить сделку</h2>
        <div class="short-divider"></div>
    </div>
    <div class="container container-slider">
        <?$APPLICATION->IncludeComponent("bitrix:catalog.section.list","sdelki_main",
            Array(
                "VIEW_MODE" => "TEXT",
                "SHOW_PARENT_NAME" => "Y",
                "IBLOCK_TYPE" => "",
                "IBLOCK_ID" => "3",
                "SECTION_ID" => '',
                "SECTION_CODE" => "",
                "SECTION_URL" => "",
                "COUNT_ELEMENTS" => "Y",
                "TOP_DEPTH" => "1",
                "SECTION_FIELDS" => "",
                "SECTION_USER_FIELDS" => "",
                "ADD_SECTIONS_CHAIN" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_NOTES" => "",
                "CACHE_GROUPS" => "Y"
            )
        );?>
    </div>
</div>
<!-- Описание и регистрация -->
<?if(!$USER->IsAuthorized()):?>
<div class="client-container">
    <div class="container">
        <h2>Стать участником</h2>
        <div class="short-divider"></div>
        <div class="row">
            <div class="col-md-6 order-2 order-md-1">
                Регистрация, авторизация и заключение договоров на площадке AnyPact проходят в режиме онлайн.
                Для заключения сделок вам понадобится подтвержденная учетная запись на портале Госуслуг.
                Подтвердить учетную запись портала Госуслуг можно в любом Многофункциональном центре Вашего города.
                <button class="btn btn-nfk send-btn new-reg-button" id="open_reg_form">Зарегистрироваться</button>
            </div>
            <div class="col-md-6 order-2 order-md-1">
                <?/*<div  <?if(!$USER->IsAuthorized()):?>id="open_reg_form"<?endif?>>
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/img_reg_us.png" alt="Подпись" style="max-width: 100%">
                </div>*/?>
                <div class="new-auth">
                    <div class="new-auth-block">
                        <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                            <!-- <?if($arResult["BACKURL"] <> ''):?>
                                <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                            <?endif?>
                            <?foreach ($arResult["POST"] as $key => $value):?>
                                <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
                            <?endforeach?>
                                <input type="hidden" name="AUTH_FORM" value="Y" />
                                <input type="hidden" name="TYPE" value="AUTH" /> -->
                                <div class="user_credentials"><img src="<?=SITE_TEMPLATE_PATH?>/image/icons8_user_credentials_100px.png"></div>
                                <h2>Авторизация</h2>
                                <!--Логин-->
                                <p>Логин</p>
                                <input type="text" name="USER_LOGIN_ERROR" class="regpopup_content_form_input" data-mess="" value="" id="user_aut_login_main" placeholder="" />
                                        <script>
                                            BX.ready(function() {
                                                var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                                                if (loginCookie)
                                                {
                                                    var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                                                    var loginInput = form.elements["USER_LOGIN"];
                                                    loginInput.value = loginCookie;
                                                }
                                            });
                                        </script>
                                <!--Пароль-->
                                <p>Пароль</p>
                                <input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" id="user_aut_pass_main" placeholder=""/>
                            <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                                    <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /></td>
                                    <label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
                            <?endif?>
                            <div id="message_error_aut_main"></div>
                            <a href="javascript:undefined" class="regpopup_content_form_submit" id="submit_button_aut_user_main"><?=GetMessage("AUTH_LOGIN_BUTTON")?></a>
                        </form>
                    </div>
                    <div class="lock-img">
                        <img src="<?=SITE_TEMPLATE_PATH?>/image/icons8_password_check_127px_1.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?endif?>
<!-- Пошаговая инструкция -->
<div class="all-easy">
    <div class="container">
        <h2>Все просто</h2>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <span class="big-number">1.</span>
                <div class="short-divider"></div>
                <p>Предоставьте данные Вашего паспорта, ИНН и СНИЛС в МФЦ.</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <span class="big-number">2.</span>
                <div class="short-divider"></div>
                <p>Получите уведомление с Вашим личным логином и паролем.</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <span class="big-number">3.</span>
                <div class="short-divider"></div>
                <p>Введите эти данные в личном кабинете на сайте Госуслуг.</p>
            </div>
            <div class="col-md-3 col-sm-6">
                <span class="big-number">4.</span>
                <div class="short-divider"></div>
                <p>Зарегистрируйтесь на сайте AnyPact и заключайте сделки онлайн.</p>
            </div>
        </div>
    </div>
</div>
<!-- Контакты -->
<div class="container content-service">	
    <h1 class="mb-4">Остались вопросы?</h1>
    <div class="short-divider"></div>
    <div class="form-card">
        <div class="row pt-2 pb-4">
            <div class="col-10 col-lg-4 offset-1  align-self-center">
                <h5>Получить консультацию</h5>
                <p>Заполните форму и мы ответим на интересующие вас вопросы в ближайшее время</p>
            </div>
            <div class="col-12 col-lg-6">                
                <div class="row" id="mess_form">
                    <? if($USER->IsAuthorized()){ ?>
                        <input type="hidden" value="<?=$USER->GetFullName()?>" id="textFIO">
                        <input type="hidden" value="<?=$USER->GetEmail()?>" id="textEmail">
                    <?}else {?>
                        <div class="col-10 col-md-5 col-lg-6 offset-1 offset-lg-0">
                            <input type="text" placeholder="ФИО" id="textFIO">
                        </div>
                        <div class="col-10 col-md-5 col-lg-6 offset-1 offset-md-0 mt-4 mt-md-0">
                            <input type="email" placeholder="E-mail" id="textEmail">
                        </div>
                    <?}?>
                    <div class="col-10 col-lg-12 mt-4 offset-1 offset-lg-0">
                        <textarea rows="4" id="textText"></textarea>
                    </div>

                    <div class="col-10 col-lg-12 mt-3 offset-1 offset-lg-0">                            
                        <label for="empty_rules" class="radio-transform">
                            <input type="checkbox" class="radio__input" name="template_type" value="empty" id="empty_rules" checked>
                            <span class="radio__label" id="empty_rules_span">Нажимая на кнопку, вы даете <a href="/upload/rules/noreg_user_rules.pdf" target="_blank">согласие на обработку персональных данных</a></span>
                        </label>
                    </div>

                    <div class="col-10 col-lg-12 mt-2 offset-1 offset-lg-0">
                        <button class="btn btn-nfk send-btn" id="send_mess_button">Отправить</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<div class="contact-container">
    <div class="container">
        <h2>Контакты</h2>
        <div class="short-divider"></div>
        <div class="row">
            <div class="col-lg-6">
                <a href="tel:88000000000">
                    <div class="contact-phone-icon">
                        <i class="icon-main icon-11"></i>
                    </div>
                    <div class="contact-phone">
                        <span class="contact-big-text">
                            8 (800) 200-84-84
                        </span>
                        <span class="text-gray">
                            Менеджер ответит на Ваши вопросы по телефону
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a href="mailto:info@anypact.ru">
                    <div class="contact-mail-icon">
                        <i class="icon-main icon-12"></i>
                    </div>
                    <div class="contact-mail">
                        <span class="contact-big-text">
                            info@anypact.ru
                        </span>
                        <span class="text-gray">
                            Свяжитесь с нами по электронной почте
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('.main-carousel_sdel').owlCarousel({
            dotsEach: false,
            //dotsData: true,
            margin: 30,
            stagePadding: 5,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 5,
                    margin: 0,
                    dots: false,
                    nav: false,
                    autoWidth:true
                },
                544: {
                    items: 2,
                    stagePadding: 5,
                    margin: 15,
                    dots: true,
                    nav: true
                },
                768: {
                    items: 2,
                    stagePadding: 5,
                    margin: 15,
                    nav: true,
                    dots: true
                },
                992: {
                    stagePadding: 5,
                    margin: 20,
                    nav: true,
                    dots: true,
                    items: 4
                }
            }
        });
        $('.cards-how').owlCarousel({
            dotsEach: true,
            //dotsData: true,
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 5,
                    margin: 0,
                    dots: false,
                    nav: false,
                    autoWidth:true
                },
                544: {
                    items: 2,
                    stagePadding: 5,
                    margin: 15,
                    dots: true,
                    nav: true
                },
                768: {
                    items: 2,
                    stagePadding: 5,
                    margin: 15,
                    nav: true,
                    dots: true
                },
                992: {
                    stagePadding: 5,
                    margin: 20,
                    nav: true,
                    dots: true,
                    items: 4
                }
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
        async function sendMess(strObject){
        preload('show');
        var url = '/response/ajax/send_mess.php'

        var mainData = JSON.stringify({
            FIO  : strObject[0],
            IMAIL: strObject[1],
            TEXT : strObject[2],
        });

        var formData = new FormData();
            formData.append( 'checkin', mainData );


        const response = await fetch(url, {
            method: 'post',
            body:formData
        });
        const data = await response.text();
        return data
    }

    let empty_rules = document.getElementById('empty_rules')
    let send_mess_button = document.getElementById('send_mess_button')
    empty_rules.onclick = function(){
        if(this.checked){
            send_mess_button.disabled = false
        }else{
            send_mess_button.disabled = true
        }

    }
    send_mess_button.onclick = function(){
        let mess_form = document.getElementById('mess_form');
        let strObject = []
        strObject[0]  = document.getElementById('textFIO').value;
        strObject[1]  = document.getElementById('textEmail').value;
        strObject[2]  = document.getElementById('textText').value;

        if(strObject[2].length==0){
            preload('hide');
            showResult('#popup-error','Ошибка сохранения', 'Введите текст сообщения');
            return;
        }

        var res = sendMess(strObject).then(function(data) {
            preload('hide');
            if(data == 'ERROR'){
                showResult('#popup-error','Ошибка сохранения');
            }
            else{
                //showResult('#popup-success', 'Срок объявления продлен');
                mess_form.innerHTML = '<h3>Ваша заявка отправлена!</h3>'
            }

       });
    }
    })
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
