<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init();
?>
<div class="regpopup_content_auform">
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
                <input type="text" name="USER_LOGIN_ERROR" class="regpopup_content_form_input" data-mess="" value="" id="user_aut_login_deal" placeholder="<?=GetMessage('AUTH_LOGIN')?>" />
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
                <input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" id="user_aut_pass_deal" placeholder="Пароль"/>  
                            <div id="message_error_aut_deal" class="error-message"></div>
                    <!-- <input type="submit" id="submit_button_aut_user" class="regpopup_content_form_submit" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /> -->
                    <a href="#" class="regpopup_content_form_submit" id="submit_button_aut_user_deal"><?=GetMessage("AUTH_LOGIN_BUTTON")?></a>
        </form>
        <?/*?><p class="text-center">Нет аккаунта? <a href="#" id="regpopup_btn_reg">Зарегистриуйтесь</a></p><?*/?>

</div>