<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// $id_contragent пользователь подписывающий контракт
// $id_owner_contract пользователь владеющий контрактом
/*if(empty($_POST['iblock'])){
    $iblockId = 4;
}
else{
    $iblockId = $_POST['iblock'];
}*/

if(CModule::IncludeModule('iblock') && !empty($_POST['contract'])){
    $iblockId = CIBlockElement::GetIBlockByID($_POST['contract']);

    if($_POST['action']=='set'){
        CIBlockElement::SetPropertyValuesEx($_POST['contract'], $iblockId, ['BLOCK'=>$_POST['status']]);
        echo json_encode(['STATUS'=>'SUCCESS', 'VALUE'=>$_POST['status']]);
    }
    elseif($_POST['action']=='get'){
        $res = CIBlockElement::GetList([], ['ID'=>$_POST['contract'], 'IBLOCK_ID'=>$iblockId, 'ACTION'=>'Y'], false, false, ['ID', 'IBLOCK_ID', 'PROPERTY_BLOCK']);
        if($obj = $res->GetNext(true, false)) $staus = $obj['PROPERTY_BLOCK_VALUE'];
        echo json_encode(['STATUS'=>'SUCCESS', 'VALUE'=>$staus]);
    }
    else{
        echo json_encode(['STATUS'=>'ERROR', 'VALUE'=>'Не передан action']);
    }
}
else{
    echo json_encode(['STATUS'=>'ERROR', 'VALUE'=>'Договор не найден']);
}


?>