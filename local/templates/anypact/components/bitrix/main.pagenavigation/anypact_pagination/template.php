<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();

$this->setFrameMode(true);
?>
<div class="wrap-pagination">
    <ul class="pagination justify-content-center">

        <?if ($arResult["CURRENT_PAGE"] > 1):?>
            <?if ($arResult["CURRENT_PAGE"] > 2):?>
                <?/*<li class="page-item bx-pag-prev"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>"><span><?echo GetMessage("round_nav_back")?></span></a></li>*/?>
                <li class="page-item bx-pag-prev"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>"><span>&larr;</span></a></li>
            <?else:?>
                <?/*<li class=" page-itembx-pag-prev"><a class="page-link" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span><?echo GetMessage("round_nav_back")?></span></a></li>*/?>
                <li class=" page-itembx-pag-prev"><a class="page-link" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span>&larr;</span></a></li>
            <?endif?>
                <li class="page-item"><a class="page-link" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span>1</span></a></li>
        <?else:?>
                <?/*<li class="page-item bx-pag-prev"><span><?echo GetMessage("round_nav_back")?></span></li>*/?>
                 <li class=" page-itembx-pag-prev"><span class="page-link">&larr;</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
        <?endif?>

        <?
        $page = $arResult["START_PAGE"] + 1;
        while($page <= $arResult["END_PAGE"]-1):
        ?>
            <?if ($page == $arResult["CURRENT_PAGE"]):?>
                <li class="page-item active"><span class="page-link"><?=$page?></span></li>
            <?else:?>
                <li class="page-item"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($page))?>"><span><?=$page?></span></a></li>
            <?endif?>
            <?$page++?>
        <?endwhile?>

        <?if($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
            <?if($arResult["PAGE_COUNT"] > 1):?>
                <li class="page-item"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"]))?>"><span><?=$arResult["PAGE_COUNT"]?></span></a></li>
            <?endif?>
                <?/*<li class="page-item bx-pag-next"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>"><span><?echo GetMessage("round_nav_forward")?></span></a></li>*/?>
                <li class="page-item bx-pag-next"><a class="page-link" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>"><span>&rarr;</span></a></li>
        <?else:?>
            <?if($arResult["PAGE_COUNT"] > 1):?>
                <li class="page-item active"><span class="page-link"><?=$arResult["PAGE_COUNT"]?></span></li>
            <?endif?>
                <?/*<li class="page-item bx-pag-next"><span><?echo GetMessage("round_nav_forward")?></span></li>*/?>
                <li class="page-item bx-pag-next"><span class="page-link">&rarr;</span></li>
        <?endif?>

    </ul>
</div>

