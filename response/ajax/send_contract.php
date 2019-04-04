<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом

// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

CModule::IncludeModule('highloadblock');
if(!CModule::IncludeModule("iblock")) return;
define("FORMAT_DATETIME", "DD.MM.YYYY HH:MI:SS");
$id_contract    = $_POST['id'];
$id_contragent  = $_POST['contr'];
$sms_code       = $_POST['smscode'];
$hash_Send      = md5($_POST['id'].$_POST['contr'].$_POST['smscode']); 


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
// Таблица СМС
$result = $entityClass::add(array(
        'UF_TIME_SEND_USER_B' => ConvertTimeStamp(time(), 'FULL'),
        'UF_TEL_CODE_USER_B' => $arUser[$id_contragent]['PERSONAL_PHONE'],
        'UF_VER_CODE_USER_B' => $sms_code,
        'UF_ID_USER_B'    => $id_contragent,
        'UF_ID_CONTRACT' => $id_contract,
        'UF_TIME_SEND_USER_A' => ConvertTimeStamp(time(), 'FULL'),
        'UF_TEL_CODE_USER_A' => $arUser[$id_owner_contract]['PERSONAL_PHONE'], 
        'UF_ID_USER_A' => $id_owner_contract,
        'UF_VER_CODE_USER_A' => '',
        'UF_STATUS' => 1,
        'UF_HASH_SEND' => $hash_Send
   ));

// создать запись в таблицу с текстом договора.

// получить по id  текст контракта,
$res = CIBlockElement::GetByID($id_contract);
$arrContractProperty = array();
if($ar_res = $res->GetNext()){
    $arrContractProperty = $ar_res;
}

// записать в файл
$url_root           = $_SERVER['DOCUMENT_ROOT'].'/upload/private/contract/';
$name_root_dir      = substr($hash_Send, 0, 1);
$name_reroot_dir    = substr($hash_Send, 2, 3);
// урл новой папки
$url_root_dir       = $url_root.'/'.$name_root_dir;
$url_contract_dir   = '/'.$url_root.'/'.$name_reroot_dir;

if (!file_exists($url_root_dir)) {
    mkdir($url_contract_dir, 0777, true);
}else {
    mkdir($url_contract_dir, 0777, true);
}

$file_contract_text = fopen($url_contract_dir.'/'.$hash_Send.'.txt', 'w');
$text_contract = $arrContractProperty['PREVIEW_TEXT'];
fwrite($file_contract_text, $text_contract);
fclose($file_contract_text);


// записать в таблицу SendContractText
$hlblock        = HL\HighloadBlockTable::getById(7)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
//$Time = ;
// Таблица СМС
$result = $entityClass::add(array(
        'UF_ID_CONTRACT'    => ConvertTimeStamp(time(), 'FULL'),
        'UF_ID_SEND_ITEM'   => 0,
        'UF_TEXT_CONTRACT'  => $text_contract,
        'UF_HASH'           => $hash_Send,

   ));

// сообщение пользователю
$hlblock        = HL\HighloadBlockTable::getById(6)->fetch();
$entity         = HL\HighloadBlockTable::compileEntity($hlblock); 
$entityClass    = $entity->getDataClass();
$result = $entityClass::add(array(
    'UF_TIME_CREATE_MSG' => ConvertTimeStamp(time(), 'FULL'),
    'UF_STATUS' => 1,
    'UF_TITLE_MESSAGE' => 'Подписан ваш договор',
    'UF_TEXT_MESSAGE_USER' => 'Участник системы Anypact подписал ваш договор <a href="http://anypact.nfksber.ru/my_pacts/send_contract/?ID='.$id_contract.'">ссылка на договор</a>' ,
    'UF_ID_USER' => $id_owner_contract
));

// сделать отправку на почтовый ящик

?>