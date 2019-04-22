<?//print_r($arResult["ARR_ITEM_MENU"]);?>

<ul class="navbar-nav mr-4">
	<?foreach($arResult["ARR_ITEM_MENU"] as $MenuItem) {?>
		<li class="nav-item">
			<a class="nav-link <?=$MenuItem["CLASS"]?>" href="<?=$MenuItem["URL"]?>"><?=$MenuItem["NAME"]?></a> 
		</li>
	<?}?>
</ul>