<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
global $USER;
$postData = $_POST;

foreach ($postData as $key => $value){
    $data[$key] = htmlspecialcharsEx($value);
}

#проверка на аторизацию
if (!$USER->IsAuthorized()){
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die();
}

#получаем необходимые данные
if(!empty($data['login'])){
    $rsUser = CUser::GetByLogin($data['login']);
    $idUser = $rsUser->GetNext(true, false)['ID'];
    $data['UF_USER_B'] = $idUser;
}

$data['UF_USER_A'] = $USER->GetID();

if($data['UF_USER_B'] == $data['UF_USER_A']){
    echo json_encode([ 'VALUE'=>'Попытка добавления себя в друзья', 'TYPE'=> 'ERROR']);
    die();
}

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}

$hlblock = HL\HighloadBlockTable::getById(15)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array(array(
        "LOGIC" => "OR",
        array("UF_USER_A" => $data['UF_USER_A'],"UF_USER_B" => $data['UF_USER_B']),
        array("UF_USER_A" => $data['UF_USER_B'],"UF_USER_B" => $data['UF_USER_A']),
    ))
));
while($arData = $rsData->Fetch()){
    if($arData['UF_USER_A'] == $data['UF_USER_A']){
        echo json_encode([ 'VALUE'=>'Вы не можете добавить данного пользователя в друзья, т.к. вы добавили его в черный список', 'TYPE'=> 'ERROR']);
        die();
    }elseif($arData['UF_USER_B'] == $data['UF_USER_A']){
        echo json_encode([ 'VALUE'=>'Вы не можете добавить данного пользователя в друзья, т.к. вы находитесь в черном списке', 'TYPE'=> 'ERROR']);
        die();
    }
}

$hlblock = HL\HighloadBlockTable::getById(14)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array(array(
        "LOGIC" => "OR",
        array("UF_USER_A" => $data['UF_USER_A'],"UF_USER_B" => $data['UF_USER_B']),
        array("UF_USER_A" => $data['UF_USER_B'],"UF_USER_B" => $data['UF_USER_A']),
    ))
));
while($arData = $rsData->Fetch()){
    if($arData['UF_USER_A'] == $data['UF_USER_A']){
        switch($arData['UF_ACCEPT']) {
            case 3:
                if($data['action']=='add'){
                    echo json_encode([ 'VALUE'=>'Вы уже отправляли запрос данному пользователю', 'TYPE'=> 'ERROR']);
                    die();
                }elseif($data['action']=='delete'){
                    $delete = $arData['ID'];
                }
                break;
            case 2:
                if($data['action']=='add'){
                    echo json_encode([ 'VALUE'=>'Вы подписаны на обновления данного пользователя', 'TYPE'=> 'ERROR']);
                    die();
                }elseif($data['action']=='delete'){
                    $delete = $arData['ID'];
                }
                break;
            case 1:
                if($data['action']=='add'){
                    echo json_encode([ 'VALUE'=>'Вы уже дружите с данным пользователем', 'TYPE'=> 'ERROR']);
                    die();
                }elseif($data['action']=='delete'){
                    $delete = $arData['ID'];
                }
                break;
        }
    }elseif($arData['UF_USER_B'] == $data['UF_USER_A']){
        switch($arData['UF_ACCEPT']) {
            case 3:
            case 2:
                if($data['action']=='add'){
                    $arFields = array("ID" => $arData['ID'], "UF_ACCEPT" => 1);
                }elseif($data['action']=='delete'){
                    if($arData['UF_ACCEPT'] == 3){
                        $arFields = array("ID" => $arData['ID'], "UF_ACCEPT" => 2);
                    }else{
                        $delete = $arData['ID'];
                    }
                }
                break;
            case 1:
                if($data['action']=='add'){
                    echo json_encode([ 'VALUE'=>'Вы уже дружите с данным пользователем', 'TYPE'=> 'ERROR']);
                    die();
                }elseif($data['action']=='delete'){
                    $arFields = array("ID" => $arData['ID'], "UF_ACCEPT" => 2);
                }
                break;
        }
    }
}
if(!$arFields && $data['action']=='add'){
    $arFields = array(
        "UF_USER_A" => $data['UF_USER_A'],
        "UF_USER_B" => $data['UF_USER_B'],
        "UF_ACCEPT" => 3,
        "UF_DATE_CREATE" => date("d.m.Y"),
    );
}
if(isset($arFields['ID'])){
    $id = $arFields['ID'];
    unset($arFields['ID']);
    $result = $entity_data_class::update($id, $arFields);
    $ST = "ACCEPT";
}elseif($data['action']=='add'){
    $result = $entity_data_class::add($arFields);
    $ST = "NEW";
}elseif(!empty($delete) && $data['action']=='delete'){
    $result = $entity_data_class::Delete($delete);
}

if($result->isSuccess()){
    if($data['action']=='add'){
        echo json_encode([ 'VALUE'=>'добавлен в друзья', 'TYPE'=> 'SUCCESS', 'ST' => $ST]);
    }elseif($data['action']=='delete'){
        echo json_encode([ 'VALUE'=>'удален из друзей', 'TYPE'=> 'SUCCESS']);
    }
}else{
    echo json_encode([ 'VALUE'=>'Ошибка', 'TYPE'=> 'ERROR']);
}

?>