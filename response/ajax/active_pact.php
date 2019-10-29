<?
/*
    изменение активности объявления
*/

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

$el = new CIBlockElement;

$arLoadProductArray = Array(
    "MODIFIED_BY"    => $USER->GetID(),     // элемент изменен текущим пользователем    
    "ACTIVE"         => $_POST['Active']    // активен
    );
  
  $PRODUCT_ID = $_POST['IDElement'];  // изменяем элемент с кодом (ID) 2
  $res = $el->Update($PRODUCT_ID, $arLoadProductArray);

print_r($res);