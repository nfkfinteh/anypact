<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule("highloadblock");
global $USER;
$idUser = $USER->GetID();
$hlblock = HL\HighloadBlockTable::getById(6)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array("UF_ID_RECIPIENT" => $idUser)
));

echo $rsData->getSelectedRowsCount();