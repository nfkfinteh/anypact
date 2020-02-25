<?
  $this->addExternalJS(SITE_TEMPLATE_PATH."/js/scripts.js"); 
   //print_r($arResult) ;
  $User_initiator = $arResult['CONTENT']['PROPERTIES']['USER1']['DATA_USER'];  
  //print_r($arResult["USER_PROP"]);
  $id_group = $arResult["USER_PROP"]["GROUP"];
  $id_user_group = $arResult["USER_PROP"]["USER_GROUP"];
  $id_dogovor = $_GET["id"];

  //echo "http://anypact.ru/upload/private/userfiles/".$id_group."/".$id_user_group."/pact/".$id_dogovor."/pact/dog.pdf";
?>
<h2><?=$arResult['CONTENT']['NAME']?></h2>
<iframe src="http://anypact.ru/upload/private/userfiles/<?=$id_group?>/<?=$id_user_group?>/pact/<?=$id_dogovor?>/pact/dog.pdf" 
style="width: 600px; height: 600px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>


