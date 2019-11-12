<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('highloadblock');

print_r($_POST);

// todo  проверки 

$hlblock        = HL\HighloadBlockTable::getById(3)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
$NewInfo = array(
    'UF_ID_SEND_USER' => $_POST["IDUser"], 
    'UF_STATUS' => 3
    );

$result = $entityClass::update($_POST["IDItem"], $NewInfo);

// сделать отправку на почтовый ящик

?>