<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('highloadblock');

//print_r($_POST);

// todo  проверки 

$hlblock        = HL\HighloadBlockTable::getById(3)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
$NewInfo = array(
    'UF_ID_SEND_USER' => $_POST["IDUser"], 
    'UF_STATUS' => 0
    );

$result = $entityClass::update($_POST["IDItem"], $NewInfo);

//обновление текста договора  
$hlblock        = HL\HighloadBlockTable::getById(7)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();

$rsData = $entityClass::getList(array(
    "select" => array("ID"),    
    "filter" => array("UF_ID_SEND_ITEM" => $_POST["IDItem"])
));
$arData = $rsData->Fetch();
$NewInfo = array(
    "UF_TEXT_CONTRACT" => $_POST["Text"]
);

$result = $entityClass::update($arData["ID"], $NewInfo);

echo $arData["ID"];

//UF_ID_SEND_ITEM
// сделать отправку на почтовый ящик

?>