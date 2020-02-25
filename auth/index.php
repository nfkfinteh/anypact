<?
//define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

	// if (is_string($_REQUEST["backurl"]) && strpos($_REQUEST["backurl"], "/") === 0)
	// {
	// 	LocalRedirect($_REQUEST["backurl"]);
	// }

	/* 	получить код и ид пользователя из строки 
		получить код из БД пользователя если совпадают активировать если нет удаляем пользователя, что бы не занимал логин почтой
		http://anypact.ru/auth/index.php?confirm_registration=yes&confirm_user_id=44&confirm_code=RgdZApuR
	*/

	$APPLICATION->SetTitle("Авторизация");

	$getIDUser 		= $_GET["confirm_user_id"];
	$getUserCode 	= $_GET["confirm_code"];

	$rsUser = CUser::GetByID($getIDUser);
	$arUser = $rsUser->Fetch();

	if($getUserCode == $arUser["CONFIRM_CODE"]){
		// активируем пользователя
		$fields = Array(
			"ACTIVE"            => "Y"
			);
		$user = new CUser;
		$user->Update($getIDUser, $fields);
		$user->Authorize($getIDUser);
		?>
			<div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
				<img src="<?=SITE_TEMPLATE_PATH?>/image/ok_reg.png" alt="Необходима регистрация">
				<h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Спасибо за регистрацию в сервисе AnyPact!</h3>
				<p align="center" style="padding: 30px 0;">
					Для того, что бы пользоваться всеми предоставленными возможностями,
					<br>Вам необходимо заполнить профиль и пройти подтверждение ваших регистрационных данных.
				</p>
				<!--<a href="#" class="btn btn-nfk mt-4" style="width: 262px; height: 46px; padding-top: 10px;">Региcтрация</a>-->
				<a href="/profile/" class="mt-3">Пройти регистрацию через портал "Госуслуг"</a>
			</div>
		<?

	}else {
		// удаляем пользователя
		CUser::Delete($getIDUser);
		?>
				<div class="d-flex flex-column align-items-center text-center mt-5 pt-5 mb-5" >
					<img src="<?=SITE_TEMPLATE_PATH?>/image/no_reg.png" alt="Необходима регистрация">
					<h3 class="text-uppercase font-weight-bold mt-3" style="max-width: 550px">Ссылка подтверждения не действительна!</h3>
					<p align="center" style="padding: 30px 0;">
						Вам необходимо подтвердить ваш электронный ящик указанный при регистрации, перейдя по высланной ссылке.
					</p>
				</div>
		<?
	}
	?>
</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>