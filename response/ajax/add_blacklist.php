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

if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
    echo json_encode([ 'VALUE'=>'Не подключен модуль highloadblock', 'TYPE'=> 'ERROR']);
    die();
}

#получаем необходимые данные
if(!empty($data['login'])){
    $rsUser = CUser::GetByLogin($data['login']);
    $idUser = $rsUser->GetNext(true, false)['ID'];
    $data['UF_USER_B'] = $idUser;
}else{
    echo json_encode([ 'VALUE'=>'Не передан логин', 'TYPE'=> 'ERROR']);
    die();
}

$data['UF_USER_A'] = $USER->GetID();

if($data['UF_USER_A'] == $data['UF_USER_B']){
    echo json_encode([ 'VALUE'=>'Попытка добавления себя в черный список', 'TYPE'=> 'ERROR']);
    die();
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
    $arDeleteFriends[] = $arData['ID'];
}

if(is_array($arDeleteFriends) && !empty($arDeleteFriends)){
    foreach($arDeleteFriends as $delete){
        $result = $entity_data_class::Delete($delete);
    }
}

$hlblock = HL\HighloadBlockTable::getById(15)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("ID" => "ASC"),
    "filter" => array(array(
        "LOGIC" => "OR",
        array("UF_USER_A" => $data['UF_USER_A'],"UF_USER_B" => $data['UF_USER_B'])
    ))
));
while($arData = $rsData->Fetch()){
    if($data['action']=='add'){
        echo json_encode([ 'VALUE'=>'Этот пользователь уже состоит в вашем черном списке', 'TYPE'=> 'ERROR']);
        die();
    }elseif($data['action']=='delete'){
        $delete = $arData['ID'];
    }
    break;
}

if($data['action']=='add'){
    $arFields = array(
        "UF_USER_A" => $data['UF_USER_A'],
        "UF_USER_B" => $data['UF_USER_B'],
        "UF_DATE_CREATE" => date("d.m.Y"),
    );
}

if($data['action']=='add'){
    $result = $entity_data_class::add($arFields);

    if (!\Bitrix\Main\Loader::includeModule('iblock')) {
        echo json_encode([ 'VALUE'=>'Не подключен модуль iblock', 'TYPE'=> 'ERROR']);
        die();
    }

    $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 8, "PROPERTY_DIRECTOR_ID" => $data['UF_USER_A'], array("LOGIC" => "OR", array("PROPERTY_STAFF" => $data['UF_USER_B']), array("PROPERTY_STAFF_NO_ACTIVE" => $data['UF_USER_B']))), false, false, array("ID", "IBLOCK_ID", "PROPERTY_STAFF", "PROPERTY_STAFF_NO_ACTIVE"));
    while($ob = $res->GetNextElement())
    {
        $arProperty = $ob->GetProperties();
        $arEl = $ob->GetFields();
        $key = array_search($data['UF_USER_B'], $arProperty['STAFF']['VALUE']);
        if($key !== FALSE){
            $arNew = array_unique($arProperty['STAFF']['VALUE']);
            unset($arNew[$key]);
            $arFields['STAFF'] = $arNew;
        }
        $key = array_search($data['UF_USER_B'], $arProperty['STAFF_NO_ACTIVE']['VALUE']);
        if($key !== FALSE){
            $arNew = array_unique($arProperty['STAFF_NO_ACTIVE']['VALUE']);
            unset($arNew[$key]);
            $arFields['STAFF_NO_ACTIVE'] = $arNew;
        }
        CIBlockElement::SetPropertyValuesEx($arEl['ID'], $arEl['IBLOCK_ID'], $arFields);
    }

    $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => 3, "PROPERTY_PACT_USER" => $data['UF_USER_A'], "PROPERTY_ACCESS_USER" => $data['UF_USER_B']), false, false, array("ID", "IBLOCK_ID", "PROPERTY_ACCESS_USER"));
    while($ob = $res->GetNextElement())
    {
        $arProperty = $ob->GetProperties();
        $arEl = $ob->GetFields();
        $key = array_search($data['UF_USER_B'], $arProperty['ACCESS_USER']['VALUE']);
        if($key !== FALSE){
            $arNew = array_unique($arProperty['ACCESS_USER']['VALUE']);
            unset($arNew[$key]);
            $arFields['ACCESS_USER'] = $arNew;
        }
        CIBlockElement::SetPropertyValuesEx($arEl['ID'], $arEl['IBLOCK_ID'], $arFields);
    }

}elseif(!empty($delete) && $data['action']=='delete'){
    $result = $entity_data_class::Delete($delete);
}

if($result->isSuccess()){
    if($data['action']=='add'){
        echo json_encode([ 'VALUE'=>'добавлен в черный список', 'TYPE'=> 'SUCCESS']);
    }elseif($data['action']=='delete'){
        echo json_encode([ 'VALUE'=>'удален из черного списка', 'TYPE'=> 'SUCCESS']);
    }
}else{
    echo json_encode([ 'VALUE'=>'Ошибка', 'TYPE'=> 'ERROR']);
}
?>