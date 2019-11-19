<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// if (is_string($_REQUEST["backurl"]) && strpos($_REQUEST["backurl"], "/") === 0)
// {
// 	LocalRedirect($_REQUEST["backurl"]);
// }

/* 	получить код и ид пользователя из строки 

	получить код из БД пользователя если совпадают активировать если нет удаляем пользователя, что бы не занимал логин почтой
	http://anypact.nfksber.ru/auth/index.php?confirm_registration=yes&confirm_user_id=44&confirm_code=RgdZApuR
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
	?><p>Вы зарегистрированы и успешно авторизовались.</p><?

}else {
	// удаляем пользователя

}

echo "<pre>"; print_r($arUser["CONFIRM_CODE"]); echo "</pre>";


?>
<p>Вы зарегистрированы и успешно авторизовались.</p>
 
<p>Используйте административную панель в верхней части экрана для быстрого доступа к функциям управления структурой и информационным наполнением сайта. Набор кнопок верхней панели отличается для различных разделов сайта. Так отдельные наборы действий предусмотрены для управления статическим содержимым страниц, динамическими публикациями (новостями, каталогом, фотогалереей) и т.п.</p>
 
<p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>