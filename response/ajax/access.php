<? /* АО "НФК-Сбережения" 11.06.20 */
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
global $USER;

if(!$USER->IsAuthorized()){
    die(json_encode(['VALUE' => "Не авторизован", 'TYPE' => 'ERROR']));
}

$idUser = $USER->GetID();
$el = new CIBlockElement;
$data = $_POST;

foreach ($data as $key=>&$value){
    $value = htmlspecialcharsEx($value);
}

$rsDeal = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID'=>3,
        'ID'=>$data['idDeal'],
        'ACTIVE'=>'Y',
        'PROPERTY_MODERATION' => 7,
        'PROPERTY_PRIVATE' => 10,
    ],
    false,
    false,
    ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_ACCESS_USER']
);

if($obj = $rsDeal->GetNextElement()){
    $arDeal['FIELDS'] = $obj->GetFields();
    $arDeal['PROPERTY'] = $obj->GetProperties();
}
#массив ID сотрудников
$arProps['ACCESS'] = $arDeal['PROPERTY']['ACCESS_USER']['VALUE'];



switch ($data['action']) {
    case 'add':
        if(!empty($arProps['ACCESS']) && in_array($data['idUser'], $arProps['ACCESS'])){
            die(json_encode(['VALUE' => "Пользователь уже представитель компании", 'TYPE' => 'ERROR']));
        }

        if(!empty($arProps['ACCESS'])){
            if(!in_array($data['idUser'], $arProps['ACCESS'])){
                $arProps['ACCESS'][] = $data['idUser'];
            }
        }
        else{
            $arProps['ACCESS'][] = $data['idUser'];
        }

        CIBlockElement::SetPropertyValuesEx($data['idDeal'], 3, ['ACCESS_USER'=>$arProps['ACCESS']]);
        $GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_3");

        die(json_encode(['VALUE' => "Пользователю был предоставлен доступ к сделке", 'TYPE' => 'SUCCESS']));
        break;
    case 'delete':
        if(!empty($arProps['ACCESS']) && !in_array($data['idUser'], $arProps['ACCESS'])){
            die(json_encode(['VALUE' => "Пользователю не был предоставлен доступ к сделке", 'TYPE' => 'ERROR']));
        }

        foreach($arProps['ACCESS'] as $key => $access){
            if ($access == $data['idUser']){
                unset($arProps['ACCESS'][$key]);
                break;
            }
        }

        if(empty($arProps['ACCESS'])) $arProps['ACCESS'] = false;

        CIBlockElement::SetPropertyValuesEx($data['idDeal'], 3, ['ACCESS_USER'=>$arProps['ACCESS']]);
        $GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_3");
        die(json_encode(['VALUE' => "Доступ к сделке для данного пользователя был закрыт", 'TYPE' => 'SUCCESS']));
        break;
}
?>