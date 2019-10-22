<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');
if(!CModule::IncludeModule("iblock")) return;
define("FORMAT_DATETIME", "DD.MM.YYYY HH:MI:SS");
$id_send_item = $_POST['id'];
$id_owner = $_POST['owner'];
$vf_code = $_POST['smscode'];


// todo  проверки 

$hlblock        = HL\HighloadBlockTable::getById(3)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
$NewInfo = array('UF_VER_CODE_USER_A' => $vf_code, 'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), 'FULL'), 'UF_STATUS' => 2);
$result = $entityClass::update($id_send_item, $NewInfo);

// сделать отправку на почтовый ящик

?>