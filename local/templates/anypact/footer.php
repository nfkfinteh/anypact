
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)?>
<footer class="footer">
    <div class="container-fluid">
		<p align="center">
			<img src="<?=SITE_TEMPLATE_PATH.'/image/logo_ap.svg'?>"  style="width: 166px;"/>
		</p>
		<p align="center" size="16">
			&copy; 2019 АО «НФК-Сбережения», ООО «НФК - Структурные инвестиции», ООО «Пионер-Лизинг»<br>
			Электронная почта: <a href="mailto:info@anypact.ru">info@anypact.ru</a><br>
			<a href="/upload/rules/user_rules.pdf" target="_blank" class="polit_link">Условия использования сайта
				Аnypact.ru</a></span>
		</p>		
	</div>
	<a href="#header" class="up-arrow"></a>
</footer>
<?require_once($_SERVER['DOCUMENT_ROOT']."/local/include/form_modal.php");?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/slider/range_prices.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/js/js.js"></script>
<script src="<?=SITE_TEMPLATE_PATH.'/js/popup_script.js?1531732896309912'?>" ></script>
<?if($USER->IsAuthorized()):?>
<script src="<?=SITE_TEMPLATE_PATH.'/js/update_unread.js'?>" ></script>
<?endif?>

</body>
</html>