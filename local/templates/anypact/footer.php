
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)?>
<footer class="footer">
    <div class="container-fluid">
		<div class="margin-bottom25px">
			<img src="<?=SITE_TEMPLATE_PATH.'/image/logo_ap.svg'?>"  style="width: 166px;" alt="Anypact">
		</div>
		<div class="margin-bottom25px">
			<?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
					"AREA_FILE_SHOW" => "file", 
					"AREA_FILE_SUFFIX" => "inc", 
					"AREA_FILE_RECURSIVE" => "Y",
					"PATH" => "/include/soc_link_footer.php"
					
				)
			);?>
		</div>
		<div>
			&copy; <?=date('Y');?> АО «НФК-Сбережения», ООО «НФК - Структурные инвестиции»<br>
			Электронная почта: <a href="mailto:info@anypact.ru">info@anypact.ru</a><br>
			<span><a href="/upload/rules/user_rules_7.pdf" target="_blank" class="polit_link">Условия использования сайта
				Аnypact.ru</a></span>
		</div>		
	</div>
	<a href="#panel" class="up-arrow"></a>
</footer>
<?require_once($_SERVER['DOCUMENT_ROOT']."/local/include/form_modal.php");?>
<?
if(!$USER->IsAuthorized() && $_COOKIE['PROMOTION_POPUP'] == "Y" || $_SESSION['PROMOTION_POPUP'] == "Y" || (empty($_COOKIE['PROMOTION_POPUP']) && empty($_SESSION['PROMOTION_POPUP'])) || ($_COOKIE['PROMOTION_POPUP'] < date('Ymd') && $_SESSION['PROMOTION_POPUP'] < date('Ymd'))){
	require_once($_SERVER['DOCUMENT_ROOT']."/local/include/promotion_popup.php");
}
?>
<?
if(!$USER->IsAuthorized())
	require_once($_SERVER['DOCUMENT_ROOT']."/local/include/gosuslugi_check.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/js.js"></script>
<script src="<?=SITE_TEMPLATE_PATH.'/js/popup_script.js?1531732896309915'?>" ></script>
<?if($USER->IsAuthorized()):?>
<script src="<?=SITE_TEMPLATE_PATH.'/js/update_unread.js'?>" ></script>
<?endif?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWQFXKG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>