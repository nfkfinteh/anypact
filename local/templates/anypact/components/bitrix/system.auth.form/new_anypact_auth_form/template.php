<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init();
?>
<?
if (strlen($_POST['sessid']) && check_bitrix_sessid()) {
   $APPLICATION->RestartBuffer();
   if (!defined('PUBLIC_AJAX_MODE')) {
      define('PUBLIC_AJAX_MODE', true);
   }
   header('Content-type: application/json');
   if ($arResult['ERROR']) {
      echo json_encode(array(
         'type' => 'error',
         'message' => strip_tags($arResult['ERROR_MESSAGE']['MESSAGE']),
      ));
   } else {
      echo json_encode(array('type' => 'ok'));
   }
   require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
   die();
}
?>

<div class="regpopup_content_auform">

	<?if($arResult["FORM_TYPE"] == "login"):?>

		<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="AUTH" />
				<!--Логин-->
				<input type="text" name="USER_LOGIN" class="regpopup_content_form_input" data-mess="" value="" id="user_aut_login" placeholder="<?=GetMessage('AUTH_LOGIN')?>" />
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
				<input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" id="user_aut_pass" placeholder="Пароль"/>  
			<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
				<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /></td>
				<label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
			<?endif?>
			<div id="message_error_aut">
				<?
					ShowMessage($arResult['ERROR_MESSAGE']);
				?>
			</div>
			<button type="submit" name="Login" value="Y" class="regpopup_content_form_submit" id="submit_button_aut_user"><?=GetMessage("AUTH_LOGIN_BUTTON")?></button>
			<?if(COption::GetOptionString("anypact", "block_gosuslugi", "Y") != "Y"){?>
				<div class="auth_with">Войти, используя
					<?
						$open_popup = '?open_auth_popup=Y';
						if(!empty($_GET))
							$open_popup = '&open_auth_popup=Y';
						$encodeURL = base64_encode($_SERVER['REQUEST_URI'].$open_popup);
					?>
					<a href="/profile/aut_esia.php?returnurl=<?=$encodeURL?>"><img src="/local/templates/anypact/img/gosuslugi.svg"></a>
				</div>
			<?}?>
		</form>
		<p class="text-center">Забыли пароль? <a href="#" id="regpopup_btn_fgpw">Восстановить</a></p>
		<p class="text-center">Нет аккаунта? <a href="#" id="regpopup_btn_reg" onclick="ym(64629523,'reachGoal','reg_link');">Зарегистриуйтесь</a></p>
	<?
	else:
	?>
		<form action="<?=$arResult["AUTH_URL"]?>">
			<?=GetMessage("auth_form_user_auth")?>
		</form>
	<?endif?>
</div>
