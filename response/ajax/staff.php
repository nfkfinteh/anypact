<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
global $USER;

if(!$USER->IsAuthorized()){
    die(json_encode(['VALUE' => "Не авторизован", 'TYPE' => 'ERROR']));
}


function sendMessageAddStaff($idUser, $arCompany){

    $rsUser = CUser::GetByID($idUser);
    if($obj = $rsUser->GetNext()){
        $arUser = $obj;
    }
    $send_data = Array(
        'EMAIL' => $arUser['EMAIL'],
        'NAME_COMPANY'=> htmlspecialchars($arCompany['NAME']),
        'LINK_COMPANY'=>'https://'.SITE_SERVER_NAME.'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arCompany['IBLOCK_ID'].'&type='.$arCompany['TYPE'].'&ID='.$arCompany['ID']
    );

    CEvent::Send("ADD_STAFF", "s1", $send_data);

}

$idUser = $USER->GetID();
$el = new CIBlockElement;
$data = $_POST;

foreach ($data as $key=>&$value){
    $value = htmlspecialcharsEx($value);
}

$rsCompany = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID'=>8,
        'ID'=>$data['idCompany'],
        'ACTIVE'=>'Y'
    ],
    false,
    false,
    ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_STAFF', 'PROPERTY_STAFF_NO_ACTIVE']
);

if($obj = $rsCompany->GetNextElement()){
    $arCompany['FIELDS'] = $obj->GetFields();
    $arCompany['PROPERTY'] = $obj->GetProperties();
}
#массив ID сотрудников
$arProps['STAFF'] = $arCompany['PROPERTY']['STAFF']['VALUE'];
$arProps['STAFF_NO_ACTIVE'] = $arCompany['PROPERTY']['STAFF_NO_ACTIVE']['VALUE'];



switch ($data['action']) {
    case 'add':
        if(!empty($arProps['STAFF']) && in_array($data['idUser'], $arProps['STAFF'])){
            die(json_encode(['VALUE' => "Пользователь уже представитель компании", 'TYPE' => 'ERROR']));
        }

        if(!empty($arProps['STAFF_NO_ACTIVE'])){
            if(!in_array($data['idUser'], $arProps['STAFF_NO_ACTIVE'])){
                $arProps['STAFF_NO_ACTIVE'][] = $data['idUser'];
            }
        }
        else{
            $arProps['STAFF_NO_ACTIVE'][] = $data['idUser'];
        }

        CIBlockElement::SetPropertyValuesEx($data['idCompany'], 8, ['STAFF_NO_ACTIVE'=>$arProps['STAFF_NO_ACTIVE']]);
        $GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_8");

        //сообщения для модерации
        sendMessageAddStaff($data['idUser'], $arCompany);

        die(json_encode(['VALUE' => "Заявка на добавление представителя компании принята и будет рассмотрена в течении 2 дней", 'TYPE' => 'SUCCESS']));
        break;
    case 'delete':
        if(!empty($arProps['STAFF']) && !in_array($data['idUser'], $arProps['STAFF'])){
            die(json_encode(['VALUE' => "Пользователь не являеться представителем компании", 'TYPE' => 'ERROR']));
        }

        foreach($arProps['STAFF'] as $key => $staff){
            if ($staff == $data['idUser']){
                unset($arProps['STAFF'][$key]);
                break;
            }
        }

        if(empty($arProps['STAFF'])) $arProps['STAFF'] = false;

        CIBlockElement::SetPropertyValuesEx($data['idCompany'], 8, ['STAFF'=>$arProps['STAFF']]);
        $GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_8");
        die(json_encode(['VALUE' => "Преедставитель удален", 'TYPE' => 'SUCCESS']));
        break;
}
?>