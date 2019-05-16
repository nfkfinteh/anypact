<? //print_r($arResult['TREE_CATEGORY']);?>
<ul id="select_category_main">
    <? foreach ($arResult['TREE_CATEGORY'] as $item_category){?>
        <li><a href="#"><?=$item_category["NAME"]?></a></li>
    <?}?>
</ul>
