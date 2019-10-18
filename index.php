<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
	<!--Форма поиска-->
	<h2>Поиск контрагентов и сделок</h2>
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
            "MAP_HEIGHT" => "715px",
            "MAP_WIDTH" => "100%"
        )
    );
?>
<!-- О сервисе -->
<div class="container">
    <h2 style="margin-top:80px;">С сервисом AnyPact</h2>
    <div class="short-divider"></div>
    <p>Вы можете заключить договор в Сети прямо сейчас! Anypact позволяет найти контрагента, сформировать условия договора, подписать его и приобрести по нему все права и обязанности. Что бы Вы ни делали, покупали или продавали имущество, искали для себя надежного исполнителя работ или сами оказывали услуги - теперь Вам не нужно волноваться о том, будет ли исполнена сделка, которую Вы заключили через Интернет. Ваше соглашение приобретает юридическую силу.</p>
    <div class="row cards-how">
        <div class="col-md-6 col-lg-3">
            <a href="/service/#prosto" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-1"></i>
                    <h5>ПРОСТО</h5>
                    <p>Для заключения электронного договора Вам достаточно иметь подтверждённую учётную запись на портале Госуслуг.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/service/#nadezhno" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-2"></i>
                    <h5>НАДЁЖНО</h5>
                    <p>Договора с электронной подписью имеют такую же юридическую силу, как и бумажные, собственноручно подписанные документы.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="/service/#bezopasno" style="color: #333;text-decoration: none;">
                <div class="card-how">
                    <i class="icon-main icon-3"></i>
                    <h5>БЕЗОПАСНО</h5>
                    <p>Система обеспечивает защиту размещённой в ней информации в соответствии с законодательством Российской Федерации.</p>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
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
<div class="client-container">
    <div class="container">
        <h2>Стать участником</h2>
        <div class="short-divider"></div>
        <div class="row">
            <div class="col-md-6">
                Регистрация, авторизация и заключение договоров на площадке AnyPact проходят в режиме онлайн. Для заключения сделок вам понадобится подтвержденная учетная запись на портале Госуслуг. Подтвердить учетную запись портала Госуслуг можно в любом Многофункциональном центре Вашего города.
            </div>
            <div class="col-md-6 text-center">
                <a href="#" id="open_reg_form">
                    <img src="<?=SITE_TEMPLATE_PATH?>/image/FX.png" alt="Подпись" style="margin-top:-170px;margin-left: -140px;">
                </a>
            </div>
        </div>
    </div>
</div>
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
<script>
    $(document).ready(function() {
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            dotsEach: true,
            //dotsData: true,
            margin: 30,
            stagePadding: 5,
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                590: {
                    items: 2,
                    nav: true
                },
                768: {
                    nav: true,
                    items: 2
                },
                992: {
                    nav: true,
                    items: 4
                }
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
    })
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
