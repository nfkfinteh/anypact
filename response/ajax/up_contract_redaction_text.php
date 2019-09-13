<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');

$el = new CIBlockElement;
$text = $_POST['contect'];
$idSdelka = $_POST['id'];
$redaction = $_POST['redaction'];
$idUser = $USER->GetID();

#получение данных по сделке
$res = CIBlockElement::GetByID($idSdelka);
if($obj = $res->GetNextElement()){
    $arSdelka = $obj->GetFields();
    $arSdelka['PROPERTY'] = $obj->GetProperties();
}

$idUserA = $arSdelka['PROPERTY']['PACT_USER']['VALUE'];
$idUserB = $idUser;

#проверка на существовоание редакции
if(!empty($redaction)){
    $arFilter = [
        'IBLOCK_ID'=>6,
        'ID'=>$redaction,
        'ACTIVE'=>'Y',
        'PROPERTY_ID_PACT'=>$arSdelka['ID'],
    ];
    $arSelect = [
        'IBLOCK_ID',
        'ID',
        'CODE',
        'DETAIL_TEXT_TYPE',
        'DETAIL_TEXT'
    ];
    $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
    if($obj = $res->GetNext(true, false)){
        $arNewRedaction = $obj;
    }
}


if(empty($arNewRedaction)){
    //если нету редакции
    #получение текста договора
    $res = CIBlockElement::GetByID($arSdelka['PROPERTY']['ID_DOGOVORA']['VALUE']);
    if($obj = $res->GetNext()) $oldDogovor = $obj['DETAIL_TEXT'];

    $arLoadProductArray = Array(
        "IBLOCK_ID"=> 6,
        "MODIFIED_BY"    => $idUser,
        "NAME"=>$arSdelka['NAME'],
        "ACTIVE" => "Y",
        "CODE" => $arSdelka['CODE'].'_'.$arSdelka['ID'].'_user_'.$idUser,
        "DETAIL_TEXT_TYPE"=>"html",
        "DETAIL_TEXT"=>html_entity_decode($text),
        "PREVIEW_TEXT_TYPE"=>"html",
        "PREVIEW_TEXT"=>$oldDogovor,
        "PROPERTY_VALUES" => [
            "ID_PACT"=>$arSdelka['ID'],
            "USER_A"=>$idUserA,
            "USER_B"=> $idUserB,
            "USER_ID_INITIATOR"=> $idUser
        ]
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        echo json_encode(['VALUE' => "Новая редакция договора: ".$PRODUCT_ID, 'ID'=>$arSdelka['ID'], 'TYPE' => 'SUCCESS']);
    }
    else {
        echo json_encode(['VALUE' => $el->LAST_ERROR, 'TYPE' => 'ERROR']);
        die();
    }
}
else{
    //редактирование существующей редакции
    $arLoadProductArray = Array(
        "DETAIL_TEXT_TYPE"=>"html",
        "DETAIL_TEXT"=>html_entity_decode($text),
        "PREVIEW_TEXT_TYPE"=>"html",
        "PREVIEW_TEXT"=>$arNewRedaction['DETAIL_TEXT'],
    );
    $PROP = [
        "USER_ID_INITIATOR"=> $idUser
    ];
    $el = new CIBlockElement;
    if($el->Update($arNewRedaction['ID'], $arLoadProductArray)){
        CIBlockElement::SetPropertyValuesEx($arNewRedaction['ID'], 6, $PROP);
        echo json_encode(['VALUE' => "Новая редакция договора обновлена", 'TYPE' => 'SUCCESS']);
    }
    else{
        echo json_encode(['VALUE' => $el->LAST_ERROR, 'TYPE' => 'ERROR']);
        die();
    }
}


?>