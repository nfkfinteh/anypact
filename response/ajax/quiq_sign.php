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

$result = $entity_data_class::update($idForUpdate, array(
  'UF_STATUS'   => '2'
));

//print_r($_POST['IDItem']);
echo "1";