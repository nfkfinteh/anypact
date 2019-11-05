<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>

<div class="container content-service">	
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
            margin-bottom: 130px;
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
    <h1 class="mb-4">Все вопросы по работе с AnyPact</h1>
    <div class="short-divider"></div>
    <div class="form-card">
        <div class="row pt-2 pb-4">
            <div class="col-10 col-lg-4 offset-1  align-self-center">
                <h5>Получить консультацию</h5>
                <p>Заполните форму и мы ответим на интересующие вас вопросы в ближайшее время</p>
            </div>
            <div class="col-12 col-lg-6">
                <form action="">
                    <div class="row">
                        <? if($USER->IsAuthorized()){ ?>                        
                            <div style="display:none;">
                                <input type="text" value="">
                                <input type="email" placeholder="E-mail">
                            </div>
                        <?}else {?>
                            <div class="col-10 col-md-5 col-lg-6 offset-1 offset-lg-0">
                                <input type="text" placeholder="ФИО">
                            </div>
                            <div class="col-10 col-md-5 col-lg-6 offset-1 offset-md-0 mt-4 mt-md-0">
                                <input type="email" placeholder="E-mail">
                            </div>
                        <?}?>
                        <div class="col-10 col-lg-12 mt-4 offset-1 offset-lg-0">
                            <textarea name="" id="" rows="4"></textarea>
                        </div>
                        <? if(!$USER->IsAuthorized()){ ?>
                        <div class="col-10 col-lg-12 mt-3 offset-1 offset-lg-0">                            
                            <label for="empty" class="radio-transform">
                                <input type="checkbox" class="radio__input" name="template_type" value="empty" id="empty">
                                <span class="radio__label">Нажимая на кнопку, вы даете согласие на обработку персональных данных и соглашаетесь с <a
                                        href="#" target="_blank">Политикой конфиденциальности</a></span>
                            </label>
                        </div>
                        <? } ?>
                        <div class="col-10 col-lg-12 mt-2 offset-1 offset-lg-0">
                            <button class="btn btn-nfk send-btn">Отправить</button>
                        </div>
                    </div>
                </form>
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
<div style="width: 100%; height: 80px;"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>