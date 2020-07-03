<?  /* АО "НФК-Сбережения" 03.07.20 */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(check_bitrix_sessid()){
    $postData = $_POST;
    foreach ($postData as $key => $value){
        $data[$key] = htmlspecialcharsEx($value);
    }
    
    #проверка полей
    if(empty($data['complaints_type'])){
        echo json_encode([ 'VALUE'=>'Не выбран тип жалобы', 'TYPE'=> 'ERROR']);
        die();
    }
    if(empty($data['id'])){
        echo json_encode([ 'VALUE'=>'Предложение не найдено', 'TYPE'=> 'ERROR']);
        die();
    }
    GLOBAL $USER;
    $curentUser = $USER->GetID();
    if(empty($curentUser)){
        echo json_encode([ 'VALUE'=>'Не найден пользователь', 'TYPE'=> 'ERROR']);
        die();
    }
    
    if (!\Bitrix\Main\Loader::includeModule('iblock')) {
        echo json_encode([ 'VALUE'=>'Не подключен модуль iblock', 'TYPE'=> 'ERROR']);
        die();
    }

    $el = new CIBlockElement;
    $PROP['TYPE'] = $data['complaints_type'];
    $PROP['DEAL'] = $data['id'];
    $arLoadProductArray = Array(
        "IBLOCK_ID"      => 9,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => "Жалоба на предложение",
        "ACTIVE"         => "Y",
        "PREVIEW_TEXT"   => $data['message-text']
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray))
        echo json_encode([ 'VALUE'=>'Жалоба принята на рассмотрение', 'TYPE'=> 'SUCCESS']);
    else
        echo json_encode([ 'VALUE'=>'Не удалось создать жалобу', 'TYPE'=> 'ERROR']);

}else{
    echo json_encode([ 'VALUE'=>'Вы не авторизваны', 'TYPE'=> 'ERROR']);
    die;
}