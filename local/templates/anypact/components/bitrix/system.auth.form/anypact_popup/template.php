<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init();
?>
<div class="regpopup_content_auform">
    <?if($USER->IsAuthorized()):?>
        <p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>
        <input type="hidden" name="logout" value="yes" />
		<input type="submit" class="regpopup_content_form_submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
	<?else:?>
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
                <input type="text" name="USER_LOGIN" class="regpopup_content_form_input"  value="" placeholder="<?=GetMessage('AUTH_LOGIN')?>" />
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
                <input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" />        
            <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                    <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /></td>
                    <label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
            <?endif?>
                   <!-- <noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex>-->
                    <input type="submit" name="Login" class="regpopup_content_form_submit" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />           
        </form>
        <p class="text-center">Нет аккаунта? <a href="#" id="regpopup_btn_reg">Зарегистриуйтесь</a></p>
    <?endif?>
</div>