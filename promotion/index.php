<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || 100 рублей на телефон за 5 минут!");
?>
<?
global $USER;
?>
</div>
<section class="section-100r">
    <div class="container-akcii">
        <div class="row-akcii">
            <div class="col-100r">
                <h1 class="h1-100r">
                        <span class="h1-small">Разместите объявление и получите</span><span class="h1-big">100 рублей</span>на телефон</h1>
                </div>
            <div class="col-100r">
                <img src="<?=SITE_TEMPLATE_PATH?>/image/iphone-100r.png" alt="">
            </div>
            <a href="#steps">
                <div class="arrow-7">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
        </div>
        <div class="regbox-akcii">
            <p>AnyPact.ru – это новая и очень удобная площадка, позволяющая совершать дистанционные сделки купли/продажи различных товаров, работ или услуг, а также совершать другие операции с использованием простой идентификации участников сделок через ГОСУСЛУГИ.</p>
            <span class="text-desc">Зарегистрируйтесь на сайте AnyPact.ru и получите деньги<br> на мобильный телефон, при выполнении указанных ниже <a href="#steps">условий</a>.*</span>
            <?/*if(!$USER -> IsAuthorized()){?>
                <button class="btn btn-nfk reg-btn-akcii" id="open_reg_form2">Регистрация</button>
            <?}*/?>
        </div>
        <div class="row-steps-container" id="steps">
            <h2>Для этого необходимо<br> пройти четыре обязательных шага!</h2>
            <div class="row-steps">
                <div class="steps_item">
                    <span>1</span>
                    <div class="desc-box">
                        <h3>Регистрация</h3>
                        <p>Регистрируемся на сайте <a href="https://anypact.ru/">AnyPact.ru</a>;</p>
                    </div>
                </div>
                <div class="steps_item">
                    <span>2</span>
                    <div class="desc-box">
                        <h3>Заполнение</h3>
                        <p>Заполняем свой профиль в настройках (обязательно с вашим фото и актуальным номером телефона - на него и произойдет зачисление средств);</p>
                    </div>
                </div>
                <div class="steps_item">
                    <span>3</span>
                    <div class="desc-box">
                        <h3>Подтверждение</h3>
                        <p>Подтверждаем свои данные через портал ГОСУСЛУГИ;</p>
                    </div>
                </div>
                <div class="steps_item">
                    <span>4</span>
                    <div class="desc-box">
                        <h3>Размещение</h3>
                        <p>Размещаем объявление с изображением о реальном намерении продать или купить товар/работу/услугу.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-akcii container-akcii-bg">
        <div class="row-end">   
            <h2>
                <span class="h1-big">
                    Всё!
                </span>
                <span class="h1-small">
                    Как только объявление будет проверено и опубликовано, баланс телефона будет пополнен.
                </span>
            </h2>
            <?if(!$USER -> IsAuthorized()){?>
                <button class="btn btn-nfk reg-btn-akcii" id="open_reg_form">Регистрация</button>
            <?}?>
            <span class="text-desc">*Предложение распространяется на первую тысячу зарегистрировавшихся пользователей, при выполении указанных выше пунктов, а также при условии, что ранее Вы не регистрировались на сайте AnyPact.ru. Предложение заканчивает свое действие 31.12.2020.</span>
        </div>
    </div>
</section>
<?/*}else{?>
    <div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5">
        <img src="/local/templates/anypact/image/forbidden.png" alt="Регистрация пройдена">
        <h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Вы уже прошли регистрацию на сайте</h3>
        <a href="/" class="mt-3">Вернуться на главную страницу</a>
    </div>
</div>
<?}*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>