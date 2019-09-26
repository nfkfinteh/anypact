<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


$postData['id'] = intval($_POST['id']);
$postData['text'] = htmlspecialcharsEx($_POST['text']);
$postData['action'] = $_POST['action'];

#проверка полей

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}

$hlbl = 9;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

if($postData['action'] == 'edit'){

    if(empty($postData['text'])){
        echo json_encode([ 'VALUE'=>'Не заполнено сообщение', 'TYPE'=> 'ERROR']);
        die();
    }

    $data = array(
        "UF_TEXT_MESSAGE"=>$postData['text'],
        "UF_TIME_CREATE_MSG"=>ConvertTimeStamp(time(), "FULL"),
    );

    $result = $entity_data_class::update($postData['id'],$data);
}
elseif($postData['action'] == 'delete'){
    $entity_data_class::Delete($postData['id']);
}


echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);