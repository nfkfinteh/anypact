<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init();
?>
<div class="regpopup_content_auform">
    <div>
        <?=GetMessage("HELP_MESS_SIGN")?>
    </div>
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
        <!--Пароль-->
        <input type="password" name="USER_PASSWORD" class="regpopup_content_form_input"  autocomplete="off" id="user_aut_pass_deal" placeholder="<?=GetMessage('AUTH_PASSWORD')?>"/>  
        <div id="message_error_aut_deal" class="error-message"></div>
        <a href="#" class="regpopup_content_form_submit" id="submit_button_aut_user_deal"><?=GetMessage("AUTH_LOGIN_BUTTON")?></a>
    </form>
</div>