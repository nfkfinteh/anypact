<?//print_r($arResult["ARR_ITEM_MENU"]);?>

<ul class="navbar-nav mr-4" style="margin-right: 0 !important;">
    <li class="mobile-location">
        <span class="location">Выберите город</span>
    </li>
	<?foreach($arResult["ARR_ITEM_MENU"] as $MenuItem) {?>
		<li class="nav-item" data-href="<?=$MenuItem["URL"]?>">
			<a class="nav-link <?=$MenuItem["CLASS"]?>" href="<?=$MenuItem["URL"]?>"><?=$MenuItem["NAME"]?></a> 
		</li>
	<?}?>
	<li class="nav-item"><a href="/AnyPact инструкция.pdf" class="manual nav-link" target="_blank" onclick="ym(64629523,'reachGoal','manual');">Инструкция</a></li>
</ul>