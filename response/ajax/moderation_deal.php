<?
/*  АО "НФК-Сбережения" 09.06.2020 */
/*
    модерация сделки
*/

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

$el = new CIBlockElement;

$arLoadProductArray = Array(
    "MODIFIED_BY"     => $USER->GetID(),     // элемент изменен текущим пользователем
    "ACTIVE"          => "Y"     // активен
);
  
  $PRODUCT_ID = $_POST['IDElement'];  // изменяем элемент с кодом (ID) 2
  $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
  $el -> SetPropertyValuesEx($PRODUCT_ID, 3, array("MODERATION" => $_POST['Moderation']));    // Промодерирован

print_r($res);