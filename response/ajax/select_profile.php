<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
global $USER;

$idUser = $USER->GetID();
$idCompany = intval($_POST['id_element']);
$action = htmlspecialcharsEx($_POST['action']);


switch ($action){
    case 'company':
        if(!empty($idUser) && !empty($idCompany)){
            //данные компаннии
            $arFilter = [
                'IBLOCK_ID'=>8,
                'ID'=>$idCompany,
                'ACTIVE'=>'Y',
                [
                    "LOGIC" => "OR",
                    ["PROPERTY_DIRECTOR_ID" => $idUser],
                    ["PROPERTY_STAFF" => $idUser],
                ]
            ];
            $res = \CIBlockElement::GetList([], $arFilter, false, false, ['IBLOCK_ID', 'ID', 'PROPERTY_DIRECTOR_ID', 'PROPERTY_STAFF']);
            if ($res->SelectedRowsCount() > 0){
                $USER->Update($idUser, ['UF_CUR_COMPANY'=>$idCompany]);
                echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);
            }
            else{
                $_SESSION['COMPANY_ID'] = '';
                echo json_encode([ 'VALUE'=>'Вы не зарегистрированы в компании', 'TYPE'=> 'ERROR']);
            }
        }
        else{
            echo json_encode([ 'VALUE'=>'Вы не авторизованы', 'TYPE'=> 'ERROR']);
        }
        break;
    case 'user':
        if(!empty($idUser)){
            $result = $USER->Update($idUser, ['UF_CUR_COMPANY'=>'']);
            if($result){
                echo json_encode([ 'VALUE'=>'', 'TYPE'=> 'SUCCESS']);
            }
            else{
                echo json_encode([ 'VALUE'=>'Ошибка сохранения', 'TYPE'=> 'ERROR']);
            }
        }
        break;

}
?>