<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact");
?>
	<!---->
	<?
    // проверяем авторизован ли пользователь
    global $USER;
    if ($USER->IsAuthorized()){
    ?>
	<!--Форма поиска-->
		<h2>Поиск контрагентов</h2>
		<div class="search">
			<form>
				<span class="magnifier"></span>
				<input type="text" name="fio" placeholder="ФИО / Логин">
				<span class="region">Чебоксары</span>
				<span class="deal-type">Вид сделки</span>
				<button class="btn btn-nfk-invert btn-search" type="submit">Поиск</button>
			</form>
		</div>
		<div class="map-space"></div>
	</div>
	<!--// Форма поиска-->
		<div class="signature-container">
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
	</div>
	<div class="container">
		<h4>Об электронной подписи</h4>
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
		<div class="long-divider"></div>
		<h2>С сервисом AnyPact</h2>
		<div class="short-divider"></div>
		<p>Вы решаете свои повседневные задачи без визита в различные<br>
			госорганы, тем самым экономя время, силы и средства!</p>
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
					<p>Договора с электронной подписью имеют такую же юридическую силу, как и бумажные, собственноручно подписанные документы.</p>
				</div>
			</div>
			<div class="col-md-6 col-lg-3">
				<div class="card-how">
					<i class="icon-main icon-3"></i>
					<h5>БЕЗОПАСНО</h5>
					<p>Для заключения электронного договора Вам достаточно иметь подтверждённую учётную запись на портале Госуслуг.</p>
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
    <? }else { ?> 
	<!--//-->
	<!--Форма авторизации-->
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
		</p>
	<?}?>
	</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>