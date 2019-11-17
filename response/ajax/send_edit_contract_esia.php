<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*
 *  $ID_HL_Block_ItemSend - HL блок записей подписи
 *  $ID_HL_Block_TextContrat - HL блок записей текта подписанного договора
 *  $ID_Contracr - ид контракта
 *  $ID_IB_Contract
 *  $ID_IB_User_Contract - ID инфоблока контрактов пользователей
 *  $Property_User_Contract - свойства контракта владельца
 *  $ID_USER_SEND_CONTR - пользователь подписавший контракт
 *  $TEXT_CONTRACT - текст измененного контракта
 */

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
CModule::IncludeModule('highloadblock');

function addItemHL($hlbl, $Params){

    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    $result = $entity_data_class::add($Params);
    // возвращаем id записи
    if (!$result->isSuccess()) {
        $errors = $result->getErrorMessages();
    } else {
        $id = $result->getId();
    }

    return $id;
}

/*
 * Создаем запись в таблице с записями подписей и получив id  созданной записи передаем его для записи в журнал с текстами договоров
 */
$ID_HL_Block_ItemSend = 3;
$DATA = base64_decode($_POST['IDContract']);
$De_SCRIPT = explode(',', $DATA);

$ID_HL_Block_ItemSend   = 3;
$ID_USER_Contract       = $De_SCRIPT[0] ;
$ID_Contract            = $De_SCRIPT[1] ;
$ID_USER_SEND_CONTR     = $De_SCRIPT[2] ;

$ParamsItemSend = array(
    'UF_VER_CODE_USER_A'    => '',
    'UF_ID_USER_A'          => $ID_USER_Contract, // владелец договора
    'UF_TEL_CODE_USER_A'    => '', //пока не заполняем авторизация через ЕСИА
    'UF_TIME_SEND_USER_A'   => ConvertTimeStamp(time(), "FULL"),
    'UF_ID_CONTRACT'        => $ID_Contract,
    'UF_ID_USER_B'          => $ID_USER_SEND_CONTR, // подписавшая сторона
    'UF_VER_CODE_USER_B'    => '',
    'UF_TEL_CODE_USER_B'    => '',
    'UF_TIME_SEND_USER_B'   => ConvertTimeStamp(time(), "FULL"),
    'UF_STATUS'             => 0,
    'UF_HASH_SEND'          => '',
    'UF_ID_SEND_USER'       => $ID_USER_SEND_CONTR
);
$IDNewSendItem = addItemHL($ID_HL_Block_ItemSend, $ParamsItemSend);

// создание записи с текстом
$TEXT_CONTRACT              = $_POST['TextContract'];
$ID_HL_Block_TextContrat    = 7;

$Contract_params = array(
    'UF_ID_CONTRACT'    => $ID_Contract,
    'UF_ID_SEND_ITEM'   => $IDNewSendItem,
    'UF_TEXT_CONTRACT'  => $TEXT_CONTRACT,
    'UF_HASH'           => '',
    'UF_ID_USER_SEND'   => $ID_USER_SEND_CONTR,
);

$IDNewText = addItemHL($ID_HL_Block_TextContrat, $Contract_params);

print_r($IDNewSendItem);