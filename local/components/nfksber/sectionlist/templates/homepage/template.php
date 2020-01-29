<? //print_r($arResult['TREE_CATEGORY']);?>
<ul id="select_category_main">
    <li><a href="/pacts/">Все категории</a></li>
    <? foreach ($arResult['TREE_CATEGORY'] as $item_category){?>
        <li style = "padding-left:<?echo $item_category["DEPTH_LEVEL"]*5?>px">
            <a href="/pacts/?SECTION_ID=<?=$item_category["ID"]?>">
                <?=$item_category["NAME"]?>
                <?if ($item_category["DEPTH_LEVEL"]==1) echo '<span></span>'; //class="chevron-down"?>
            </a>
        </li>
    <?}?>
</ul>
