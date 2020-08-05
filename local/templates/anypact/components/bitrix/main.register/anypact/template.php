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
	$LOGIN = $arResult["SHOW_FIELDS"][0];
	$EMAIL = $arResult["SHOW_FIELDS"][1];
	$PASSWORD = $arResult["SHOW_FIELDS"][2];
	$CONFIRM_PASSWORD = $arResult["SHOW_FIELDS"][3];
?>
<div class="regpopup_content_form">
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" onsubmit="ym(64629523,'reachGoal','reg');">
		<div class="regpopup_content_form_fild">
		<? // print_r($arResult["SHOW_FIELDS"]);?>
			<?=bitrix_sessid_post();?>
			<input type="text" name="fax" style="display:none;">
			<input hidden size="30" class="regpopup_content_form_input" id="user_login_fild" name="REGISTER[<?=$LOGIN?>]" value="<?=$arResult["VALUES"][$LOGIN]?>" placeholder="<?=GetMessage($LOGIN)?>"  autocomplete="off" />
            <input hidden class="regpopup_content_form_input" name="UF_TYPE_REGISTR" value="<?=$_GET['type_registr']?>"  autocomplete="off" />
			<input size="30" class="regpopup_content_form_input" id="user_email_fild" name="REGISTER[<?=$EMAIL?>]" value="<?=$arResult["VALUES"][$EMAIL]?>" placeholder="<?=GetMessage($EMAIL)?>"  autocomplete="off" />
			<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
				<?
					switch ($FIELD) {
						case 'PASSWORD':
							?><input size="30" class="regpopup_content_form_input" id="user_password_fild"  type="password" 
							name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="Введите пароль не менее 9 символов" disabled/><?
						break;
						case 'CONFIRM_PASSWORD':
							?><input size="30" class="regpopup_content_form_input" id="user_con_password_fild" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="<?=GetMessage($FIELD)?>" disabled/><?
						break;
						case 'PERSONAL_PHONE':
							?><input type="text" placeholder="<?=GetMessage($FIELD)?>" name="REGISTER[<?=$FIELD?>]" maxlength="50" value="<?=$arResult["VALUES"][$FIELD]?>" class="js-mask__phone"><?
						break;
						/*case 'LOGIN':
							?><input size="30" class="regpopup_content_form_input" id="user_login_fild" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?> " placeholder="<?=GetMessage($FIELD)?>"  autocomplete="off" /><?
						break;
						case 'EMAIL':
							?><input size="30" class="regpopup_content_form_input" id="user_email_fild" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" placeholder="<?=GetMessage($FIELD)?>" disabled/><?
						break;
						default:
							?> <input size="30" class="regpopup_content_form_input" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" placeholder="<?=GetMessage($FIELD)?>" /> <?
						break;*/
					}
				?>						
			<?endforeach?>
			<div id="message_error_login"></div>
			<p class="regpopup_content_form_soglashenie">Регистрируясь, Вы подтверждаете, что принимаете 
				<a href="/upload/rules/user_rules.pdf" target="_blank">Пользовательское соглашение</a>					
			</p>
					<div id="box_submit_button">
						<input class="regpopup_content_form_submit" type="submit" name="register_submit_button" id="submit_button_registration" value="<?=GetMessage("AUTH_REGISTER")?>" disabled/>
					</div>
			<p class="text-center">Есть аккаунт? <a href="#" id="regpopup_btn_aut">Войти</a></p>
		</div>
	</form>
</div>