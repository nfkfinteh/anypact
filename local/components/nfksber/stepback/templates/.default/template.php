<? 
//print_r($arResult);
if($arResult['SECTION_INFO']['IBLOCK_SECTION_ID'] > 0){
    $back_Url = $arResult['SECTION_INFO']['IBLOCK_SECTION_ID'];
} else {
    $back_Url = 0 ;
}

?>
<a class="navbar-brand" href="/pacts/?SECTION_ID=<?=$back_Url?>">&larr;&emsp;Назад</a>