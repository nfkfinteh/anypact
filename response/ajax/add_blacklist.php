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
}else{
    echo json_encode([ 'VALUE'=>'Не передан логин', 'TYPE'=> 'ERROR']);
    die();
}

$data['CURRENT_USER_ID'] = $USER->GetID();

$arFilter = array("ID" => $data['CURRENT_USER_ID']);
$arParams["SELECT"] = array("ID", "UF_BLACKLIST");
$res = CUser::GetList($by ="timestamp_x", $order = "desc", $arFilter, $arParams);
if($obj=$res->GetNext()){
    if(!empty($obj['UF_BLACKLIST'])){
        $data['BLACKLIST'] = json_decode($obj['~UF_BLACKLIST']);
    }
    else{
        $data['BLACKLIST'] = [];
    }
}

if($data['FREND_USER_ID'] == $data['CURRENT_USER_ID']){
    echo json_encode([ 'VALUE'=>'Попытка добавления себя в черный список', 'TYPE'=> 'ERROR']);
    die();
}

if($data['action']=='add'){
    if(empty($data['BLACKLIST'])){
        $arBLACKLIST[] =$data['FREND_USER_ID'];
    }
    else{
        $arBLACKLIST = $data['BLACKLIST'];
        if(!in_array($data['FREND_USER_ID'], $data['BLACKLIST'])){
            $arBLACKLIST[] =$data['FREND_USER_ID'];
        }
    }
}
elseif($data['action']=='delete'){
    foreach ($data['BLACKLIST'] as $item){
        if($item!=$data['FREND_USER_ID']){
            $arBLACKLIST[] = $item;
        }
    }
}


$arBLACKLIST = json_encode($arBLACKLIST);

$user = new CUser;
$fields = Array(
    'UF_BLACKLIST'=>$arBLACKLIST
);
if($user->Update($data['CURRENT_USER_ID'], $fields)){
    echo json_encode([ 'VALUE'=>'добавлен в черный список', 'TYPE'=> 'SUCCESS']);
    die();
}
else{
    echo json_encode([ 'VALUE'=>$user->LAST_ERROR, 'TYPE'=> 'ERROR']);
    die();
}

?>