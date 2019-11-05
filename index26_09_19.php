<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
	<!---->
	<?
    // проверяем авторизован ли пользователь
    //global $USER;
   //if ($USER->IsAuthorized()){
    ?>
	<!--Форма поиска-->
		<h2>Поиск контрагентов</h2>
		<?// компонент поисковой строки
		$APPLICATION->IncludeComponent(
			"bitrix:search.form",
			"homepage",
			Array()
		);?>		
	</div>
<?$APPLICATION->IncludeComponent(
	"nfksber:yamap",
	"",
	Array(
		"CACHE_TIME" => 36000,
		"CACHE_TYPE" => "A",
		"COUNT_POINT" => "10",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "4",
		"LOCATION" => "Чебоксары",
		"MAP_HEIGHT" => "715px",
		"MAP_WIDTH" => "100%"
	)
);?>
	<!--// Форма поиска-->
	<!--div class="signature-container">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<h1>AnyPact - Ваш <br>личный сервис</h1>
					<div class="short-divider"></div>
					<h3>Для дистанционного заключения сделок с использованием простой электронной подписи</h3>
				</div>
				<div class="col-md-7 signature text-center">
					<img src="<?=SITE_TEMPLATE_PATH ?>/image/signature.png" alt="Подпись">
				</div>
			</div>
		</div>
	</div>-->
	<div class="container">
		<!--<h4>Об электронной подписи</h4>
		<div class="row">
			<div class="col-md-6">
				<p>
					Электронная подпись подтверждает авторство электронного документа, обеспечивает его подлинность, конфиденциальность и юридическую значимость. Она обладает полной юридической силой согласно законодательству Российской Федерации. Таким образом, договор, заключённый в системе AnyPact, создаёт права и обязанности для подписавших его сторон, а также служит доказательством осуществления сделки в государственных органах, в том числе и в судебных инстанциях.
				</p>
			</div>
			<div class="col-md-6">
				<p>
					Простая электронная подпись является цифровым аналогом подписи, написанной от руки, и представляет собой комбинацию из логина и пароля. В отличие от рукописной, электронной подписью Вы можете пользоваться удалённо, не выходя из дома. Изменить или подделать её невозможно, так как вся информация о её владельце представлена в зашифрованном виде. Благодаря этому электронная подпись гарантирует достоверность документа и безопасность обмена данными между участниками сделки.
				</p>
			</div>
		</div>
		<div class="long-divider"></div>-->
		<h2 style="margin-top:80px;">С сервисом AnyPact</h2>
		<div class="short-divider"></div>
		<p>С сервисом AnyPact Вы решаете свои повседневные задачи без визита в различные госорганы, тем самым экономя время, силы и средства!</p>
		<div class="row cards-how">
			<div class="col-md-6 col-lg-3">
				<div class="card-how">
					<i class="icon-main icon-1"></i>
					<h5>ПРОСТО</h5>
					<p>Для заключения электронного договора Вам достаточно иметь подтверждённую учётную запись на портале Госуслуг.</p>
				</div>
			</div>
			<div class="col-md-6 col-lg-3">
				<div class="card-how">
					<i class="icon-main icon-2"></i>
					<h5>НАДЁЖНО</h5>
					<p>Договоры с электронной подписью имеют такую же юридическую силу, как и бумажные, собственноручно подписанные документы.</p>
				</div>
			</div>
			<div class="col-md-6 col-lg-3">
				<div class="card-how">
					<i class="icon-main icon-3"></i>
					<h5>БЕЗОПАСНО</h5>
					<p>Система обеспечивает защиту размещённой в ней информации в соответствии с законодательством Российской Федерации.</p>
				</div>
			</div>
			<div class="col-md-6 col-lg-3">
				<div class="card-how">
					<i class="icon-main icon-4"></i>
					<h5>УДОБНО</h5>
					<p>Вы можете использовать готовый шаблон документа или изменить его согласно Вашим пожеланиям и требованиям.</p>
				</div>
			</div>
		</div>
	</div>
	<div class="deal-container">
    <div class="container">
        <h2>Заключить сделку</h2>
        <div class="short-divider"></div>
		<style>
		.owl-carousel .card-deal {
			width: 23%;
    		float: left;
    		margin-left: 22px;
		}
		</style>
        <div class="owl-carousel owl-theme">
            <div class="item card-deal">
                <i class="icon-main icon-5"></i>
                <h5>Купля-продажа</h5>
                <ul>
                    <li>Для заключения</li>
                    <li>Электронного договора</li>
                    <li>Вам достаточно иметь</li>
                    <li>Подтвержденную учетную</li>
                    <li>Запись на портале</li>
                </ul>
                <a href="http://anypact.nfksber.ru/pacts/?SECTION_ID=1"><button>Перейти</button></a>
            </div>
            <div class="item card-deal">
                <i class="icon-main icon-6"></i>
                <h5>Аренда</h5>
                <ul>
                    <li>Для заключения</li>
                    <li>Электронного договора</li>
                    <li>Вам достаточно иметь</li>
                    <li>Подтвержденную учетную</li>
                    <li>Запись на портале</li>
                </ul>
                <a href="http://anypact.nfksber.ru/pacts/?SECTION_ID=2"><button>Перейти</button></a>
            </div>
            <div class="item card-deal">
                <i class="icon-main icon-7"></i>
                <h5>Работа, услуги</h5>
                <ul>
                    <li>Для заключения</li>
                    <li>Электронного договора</li>
                    <li>Вам достаточно иметь</li>
                    <li>Подтвержденную учетную</li>
                    <li>Запись на портале</li>
                </ul>
                <a href="http://anypact.nfksber.ru/pacts/?SECTION_ID=3"><button>Перейти</button></a>
            </div>
            <div class="item card-deal">
                <i class="icon-main icon-8"></i>
                <h5>Заём</h5>
                <ul>
                    <li>Для заключения</li>
                    <li>Электронного договора</li>
                    <li>Вам достаточно иметь</li>
                    <li>Подтвержденную учетную</li>
                    <li>Запись на портале</li>
                </ul>
                <a href="http://anypact.nfksber.ru/pacts/?SECTION_ID=10"><button>Перейти</button></a>
            </div>           
        </div>
    </div>
</div>
	<div class="client-container">
    <div class="container">
        <h2>Стать клиентом</h2>
        <div class="short-divider"></div>
        <div class="row">
            <div class="col-md-6">
            Регистрация, авторизация и заключение договоров на площадке AnyPact проходят в режиме онлайн. Получить простую электронную подпись Вы можете в любом Многофункциональном центре Вашего города. Для этого Вам необходимо удостоверить свою личность с помощью данных всего трёх документов и оформить учётную запись на сайте www.gosuslugi.ru.
            </div>
			<div class="col-md-6 text-center">
                <img src="<?=SITE_TEMPLATE_PATH?>/image/desctop.png" alt="Подпись" style="margin-top:-50px;">
            </div>
        </div>
    </div>
</div>
<div class="all-easy">
    <div class="container">
        <h4>Все просто</h4>
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
                            8 (800) 000-00-00
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
    <? //}else { ?> 
	<!--//-->
	<!--Форма авторизации-->
		<!--
		<p align="center" style="padding: 50px 0;">
			<img src="<?=SITE_TEMPLATE_PATH.'/img/logo.png?ioi'?>" />
		</p>
		<p align="center" size="16">
			M-Group Investments Limited <br>
			Contact us: <a href="mailto:mail@m-group.investments">mail@m-group.investments</a>
		</p>	 
		<p align="center">
		<button type="button" class="btn btn-aut" id="reg_button">Войти</button>
		</p>
		<p align="center">
			© 2018
		</p>-->
	<?//}?>
	</div> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/slider/range_prices.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min.js"></script>
<script>
$(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        dotsEach: true,
        //dotsData: true,
        margin: 30,
        stagePadding: 5,
        //nav: true,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 4
            }
        }
    });
    $('[data-toggle="tooltip"]').tooltip();
})

</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>