<?//print_r($arResult['CONTENT']['PROPERTIES']['USER1']) ;
  $User_initiator = $arResult['CONTENT']['PROPERTIES']['USER1']['DATA_USER'];  

?>
<h2><?=$arResult['CONTENT']['NAME']?></h2>
<h4>Инициатор: <?=$User_initiator['LAST_NAME']?> <?=$User_initiator['NAME']?> <?=$User_initiator['SECOND_NAME']?></h4>


