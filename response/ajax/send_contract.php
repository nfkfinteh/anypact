<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');
if(!CModule::IncludeModule("iblock")) return;
define("FORMAT_DATETIME", "DD.MM.YYYY HH:MI:SS");
$id_contract = $_POST['id'];
$id_contragent = $_POST['contr'];


function getProperty($id_iblok, $id_element){        
    $db_props = CIBlockElement::GetProperty($id_iblok, $id_element, "sort", "asc", array());         
    $array_props = array();        
    $array_img = array();
    while($ar_props = $db_props->Fetch()){ 
        $array_props[$ar_props["CODE"]] = $ar_props ;                
    }
    return $array_props;
}

if (empty($id_contract)){
    $id_contract = 18;
}



$arrProperty_contract = getProperty(4, $id_contract);

//$rsUser = CUser::GetByID($arrProperty_contract['USER_A']['VAL']);
$idUser = $arrProperty_contract['USER_A']['VALUE']." | ".$id_contragent ;
$id_owner_contract = $arrProperty_contract['USER_A']['VALUE']; 

$filter = Array(
    "ACTIVE" => "Y",
    "ID" => $idUser
);
$arSel = array(
   "NAME", "PERSONAL_MOBILE",
);
$rsUsers = CUser::GetList(($by="name"), ($order="asc"), $filter); // выбираем пользователей
$rsUsers->NavStart(5); // разбиваем постранично по 5 записей
$rsUsers->bShowAll = false;
$arUser = array();

while($arr = $rsUsers->GetNext()){
    $arUser[$arr['ID']] = $arr;
}

// todo  проверки 

$hlblock        = HL\HighloadBlockTable::getById(3)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
//$Time = ;
$result = $entityClass::add(array(
        'UF_TIME_SEND_USER_B' => ConvertTimeStamp(time(), 'FULL'),
        'UF_TEL_CODE_USER_B' => $arUser[$id_contragent]['PERSONAL_PHONE'],
        'UF_VER_CODE_USER_B' => '7777',
        'UF_ID_USER_B'    => $id_contragent,
        'UF_ID_CONTRACT' => $id_contract,
        'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), 'FULL'),
        'UF_TEL_CODE_USER_A' => $arUser[$id_owner_contract]['PERSONAL_PHONE'], 
        'UF_ID_USER_A' => $id_owner_contract,
        'UF_VER_CODE_USER_A' => '5555',
        'UF_STATUS' => 1
   ));

// создать запись в таблицу с текстом договора.

$hlblock        = HL\HighloadBlockTable::getById(6)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
$result = $entityClass::add(array(
    'UF_TIME_CREATE_MSG' => ConvertTimeStamp(time(), 'FULL'),
    'UF_STATUS' => 1,
    'UF_TEXT_MESSAGE_USER' => 'Участник системы Anypact подписал ваш договор <a href="http://anypact.nfksber.ru/my_pacts/send_contract/?ID='.$id_contract.'">ссылка на договор</a>' ,
    'UF_ID_USER' => $id_owner_contract
));

// сделать отправку на почтовый ящик

?>