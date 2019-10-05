<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
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
    $data['FREND_USER_ID'] = $idUser;
}

$data['CURRENT_USER_ID'] = $USER->GetID();

$arFilter = array("ID" => $data['CURRENT_USER_ID']);
$arParams["SELECT"] = array("ID", "UF_FRENDS");
$res = CUser::GetList($by ="timestamp_x", $order = "desc", $arFilter, $arParams);
if($obj=$res->GetNext()){
    if(!empty($obj['UF_FRENDS'])){
        $data['FRENDS'] = json_decode($obj['~UF_FRENDS']);
    }
    else{
        $data['FRENDS'] = [];
    }
}

if($data['FREND_USER_ID'] == $data['CURRENT_USER_ID']){
    echo json_encode([ 'VALUE'=>'Попытка добавления себя в друзья', 'TYPE'=> 'ERROR']);
    die();
}

if($data['action']=='add'){
    if(empty($data['FRENDS'])){
        $arFrends[] =$data['FREND_USER_ID'];
    }
    else{
        $arFrends = $data['FRENDS'];
        if(!in_array($data['FREND_USER_ID'], $data['FRENDS'])){
            $arFrends[] =$data['FREND_USER_ID'];
        }
    }
}
elseif($data['action']=='delete'){
    foreach ($data['FRENDS'] as $item){
        if($item!=$data['FREND_USER_ID']){
            $arFrends[] = $item;
        }
    }
}


$arFrends = json_encode($arFrends);

$user = new CUser;
$fields = Array(
    'UF_FRENDS'=>$arFrends
);
if($user->Update($data['CURRENT_USER_ID'], $fields)){
    echo json_encode([ 'VALUE'=>'добавлен в друзья', 'TYPE'=> 'SUCCESS']);
    die();
}
else{
    echo json_encode([ 'VALUE'=>$user->LAST_ERROR, 'TYPE'=> 'ERROR']);
    die();
}

?>