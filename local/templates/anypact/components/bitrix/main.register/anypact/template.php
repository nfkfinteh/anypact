<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<div class="regpopup_content_form">
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
		<div class="regpopup_content_form_fild">
			<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
				<?
					switch ($FIELD) {
						case 'PASSWORD':
							?><input size="30" class="regpopup_content_form_input" id="user_password_fild"  type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="<?=GetMessage($FIELD)?>" disabled/><?
						break;
						case 'CONFIRM_PASSWORD':
							?><input size="30" class="regpopup_content_form_input" id="user_con_password_fild" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="<?=GetMessage($FIELD)?>" disabled/><?
						break;
						case 'LOGIN':
							?><input size="30" class="regpopup_content_form_input" id="user_login_fild" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?> " placeholder="<?=GetMessage($FIELD)?>"  autocomplete="false" /><?
						break;
						case 'EMAIL':
							?><input size="30" class="regpopup_content_form_input" id="user_email_fild" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" placeholder="<?=GetMessage($FIELD)?>" disabled/><?
						break;
						default:
							?> <input size="30" class="regpopup_content_form_input" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="<?=GetMessage($FIELD)?>" /> <?
						break;
					}
				?>						
			<?endforeach?>
			<div id="message_error_login"></div>
			<p class="regpopup_content_form_soglashenie">Регистрируясь, Вы подтверждаете, что принимаете 
				<a href="#">Условия пользования</a> и <a href="#">Политику конфиденциальности</a>					
			</p>
					<div id="box_submit_button">
						<input class="regpopup_content_form_submit" type="submit" name="register_submit_button" id="submit_button_registration" value="<?=GetMessage("AUTH_REGISTER")?>" disabled/>
					</div>
			<p class="text-center">Есть аккаунт? <a href="#" id="regpopup_btn_aut">Войти</a></p>
		</div>
	</form>
</div>