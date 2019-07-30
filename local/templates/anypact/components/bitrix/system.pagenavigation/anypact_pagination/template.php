<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>
<ul class="pagination justify-content-center">
<?
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<?
	$bFirst = true;
	if ($arResult["NavPageNomer"] > 1):
		if($arResult["bSavePage"]):
        ?>
            <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">&larr;</a></li>
            <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a></li>
        <?
		else:
			if ($arResult["NavPageNomer"] > 2):
            ?>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">&larr;</a></li>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">Назад</a></li>
            <?
			else:
            ?>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">&larr;</a></li>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">Назад</a></li>
            <?
			endif;
		endif;
        if ($arResult["nStartPage"] > 1):
            $bFirst = false;
            if($arResult["bSavePage"]):
                ?>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1">1</a></li>
            <?
            else:
                ?>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>">1</a></li>
            <?
            endif;
            if ($arResult["nStartPage"] > 2):
                ?>
                <li class="page-item"><a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>">...</a></li>
            <?
            endif;
        endif;
    else:
    ?>
        <li class="page-item disabled"><a class="page-link" href="#">&larr;</a></li>
        <li class="page-item disabled"><a class="page-link" href="#">Назад</a></li>
    <?
	endif;

    do {
        if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
            ?>
            <li class="page-item <?= ($bFirst ? "modern-page-first " : "") ?> active">
                <a class="page-link" href="#"><?= $arResult["nStartPage"] ?></a>
            </li>
        <?
        elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
            ?>
            <li class="page-item <?= ($bFirst ? "modern-page-first" : "") ?>">
                <a class="page-link" href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>">
                    <?= $arResult["nStartPage"] ?>
                </a>
            </li>
        <?
        else:
            ?>
            <li class="page-item">
                <a class="page-link <?= ($bFirst ? "modern-page-first" : "") ?>" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>">
                    <?= $arResult["nStartPage"] ?>
                </a>
            </li>
        <?
        endif;
        $arResult["nStartPage"]++;
        $bFirst = false;
    } while ($arResult["nStartPage"] <= $arResult["nEndPage"]);

    if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):
        if ($arResult["nEndPage"] < $arResult["NavPageCount"]):
            if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):

                ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2) ?>">...</a>
                </li>
            <?
            endif;
            ?>
            <li class="page-item">
                <a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">
                    <?= $arResult["NavPageCount"] ?>
                </a>
            </li>
        <?
        endif;
        ?>
        <li class="page-item"><a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">Вперед</a></li>
        <li class="page-item"><a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">&rarr;</a></li>
    <?
    else:
    ?>
        <li class="page-item disabled"><a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">Вперед</a></li>
        <li class="page-item disabled"><a class="page-link" href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>">&rarr;</a></li>
    <?
    endif;
    ?>
</ul>