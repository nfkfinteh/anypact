<? 
//print_r($arResult);
if($arResult['SECTION_INFO']['IBLOCK_SECTION_ID'] > 0){
    $back_Url = $arResult['SECTION_INFO']['IBLOCK_SECTION_ID'];
} else {
    $back_Url = 0 ;
}
?>
<? if($arResult["MAIN_PAGE"]){ ?>
    <a class="navbar-brand" href="/pacts/?SECTION_ID=<?=$arResult["HTTP_REFERER"]?>" >&larr;&emsp;Назад</a>
<? } ?>