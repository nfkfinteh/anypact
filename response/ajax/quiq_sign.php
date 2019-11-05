<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER;

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}

$hlbl = 3;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$idForUpdate = $_POST['IDItem'];

// получим запись
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("ID" => $idForUpdate)
));

$Send_item = array();
while($arData = $rsData->Fetch()){
    $Send_item  = $arData;
}

// ид контракта, выбираем объявления с ид
$ID_Contract = (int) $Send_item["UF_ID_CONTRACT"];

// обновление статуса подписания
$result = $entity_data_class::update($idForUpdate, array(
  'UF_STATUS'   => '2'
));

////// проверяем если у элемента есть автоматическое удаление, то удаляем запись.///
if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль инфоблоки', 'TYPE'=> 'ERROR']);
    die();
}

$IBlock_id = 3;

$arFilter = array(
    'IBLOCK_ID' => $IBlock_id,
    'PROPERTY_ID_DOGOVORA' => $ID_Contract
);

// запрос с параметрами
$result = CIBlockElement::GetList(Array(), $arFilter, false, Array());
// массив элементов
$arElements = Array();
// получаем поля элементов
while($element = $result->GetNextElement()) {
    // поля элемента
    $arFields = $element->GetFields();
    // пользовательские свойства элемента
    $UserProperty = CIBlockElement::GetProperty($IBlock_id, $arFields['ID']);
    // если нужно получить значения без ~ (без тильды к значенинию применено htmlspecialcharsEx) ->GetNext(true, false)
    while($arProperty = $UserProperty->GetNext(true, false)){
        // записываем свойства в массив с кодом свойства
        $arFields['USER_PROPERTY'][$arProperty['CODE']] = $arProperty;
    }
}

$ID_AD = $arFields['ID'];

// если у объявления есть признак автоматического удаления то удаляем его
if(!empty($arFields['USER_PROPERTY']['AV_DELETE']) && $arFields['USER_PROPERTY']['AV_DELETE']['VALUE'] == 'Y' ) {
  
  if(CIBlockElement::Delete($ID_AD))
  {
      echo json_encode([ 'VALUE'=>'Элемент удален', 'TYPE'=> 'SUCCESS']);
  }
  else{
      echo json_encode([ 'VALUE'=>'Ошибка при удалении', 'TYPE'=> 'ERROR']);
      die();
  }
} 
