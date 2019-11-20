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
                <div class="row" id="mess_form">
                    <? if($USER->IsAuthorized()){ ?>                        
                        <div style="display:none;">
                            <input type="text" value="">
                            <input type="email" placeholder="E-mail">
                        </div>
                    <?}else {?>
                        <div class="col-10 col-md-5 col-lg-6 offset-1 offset-lg-0">
                            <input type="text" placeholder="ФИО" id="textFIO">
                        </div>
                        <div class="col-10 col-md-5 col-lg-6 offset-1 offset-md-0 mt-4 mt-md-0">
                            <input type="email" placeholder="E-mail" id="textEmail">
                        </div>
                    <?}?>
                    <div class="col-10 col-lg-12 mt-4 offset-1 offset-lg-0">
                        <textarea name="" rows="4" id="textText"></textarea>
                    </div>
                    <? if(!$USER->IsAuthorized()){ ?>
                    <div class="col-10 col-lg-12 mt-3 offset-1 offset-lg-0">                            
                        <label for="empty_rules" class="radio-transform">
                            <input type="checkbox" class="radio__input" name="template_type" value="empty" id="empty_rules">
                            <span class="radio__label" id="empty_rules_span">Нажимая на кнопку, вы даете <a href="/upload/rules/noreg_user_rules.pdf" target="_blank">согласие на обработку персональных данных</a></span>
                        </label>
                    </div>
                    <? } ?>
                    <div class="col-10 col-lg-12 mt-2 offset-1 offset-lg-0">
                        <button class="btn btn-nfk send-btn" id="send_mess_button" disabled>Отправить</button>
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
<div style="width: 100%; height: 80px;"></div>
<script>        
        async function sendMess(strObject){
            
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
            let mess_form = document.getElementById('mess_form')
            let strObject = []
            strObject[0]  = document.getElementById('textFIO').value
            strObject[1]  = document.getElementById('textEmail').value
            strObject[2]  = document.getElementById('textText').value

            var res = sendMess(strObject).then(function(data) {
               console.log(mess_form)
               mess_form.innerHTML = '<h3>Ваша заявка отправлена!</h3>'
           });
        }
    
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>