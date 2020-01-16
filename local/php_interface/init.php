<?php define("PREFIX_PATH_404", "/404.php");
include_once 'function.php';

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("main", "OnAfterEpilog", "Prefix_FunctionName");

//проверка являеться ли пользователь сотрудником компании и сброс по необходимости
$eventManager->addEventHandler("main", "OnAfterEpilog", "checkUserCompany");

//установка статуса завершенной сделки
$eventManager->addEventHandler('', 'ContractSendOnAfterUpdate', 'SetStatusDeal');
$eventManager->addEventHandler('', 'ContractSendOnAfterAdd', 'SetStatusDeal');

function Prefix_FunctionName() {
    global $APPLICATION;

    // Check if we need to show the content of the 404 page
    if (!defined('ERROR_404') || ERROR_404 != 'Y') {
        return;
    }

    // Display the 404 page unless it is already being displayed
    if ($APPLICATION->GetCurPage() != PREFIX_PATH_404) {
        header('X-Accel-Redirect: '.PREFIX_PATH_404);
        exit();
    }
}

function checkUserCompany(){
    global $USER;
    $resUser = CUser::GetByID($USER->GetID());
    if ($obj = $resUser->GetNext()){
        $arUser = $obj;
    }
    if(!empty($arUser['UF_CUR_COMPANY'])){
        if(CModule::IncludeModule('iblock')){
            $resCompany = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'=>8,
                    'ID'=>$arUser['UF_CUR_COMPANY'],
                    'ACTIVE'=>'Y',
                    [
                        "LOGIC" => "OR",
                        ["=PROPERTY_STAFF" => $arUser['ID']],
                        ["=PROPERTY_DIRECTOR_ID" => $arUser['ID']],
                    ]
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'PROPERTY_STAFF']
            );
            $cntCompany = $resCompany->SelectedRowsCount();

            #если не сотрудник и не директор сбрасываем поле UF_CUR_COMPANY
            if($cntCompany<=0){
                $user = new CUser;
                $user->Update(
                    $arUser['ID'],
                    ['UF_CUR_COMPANY'=>'']
                );
            }
        }
    }
}

function SetStatusDeal(\Bitrix\Main\Entity\Event $event)
{
    $ID = $event->getParameter("id");

    if(is_array($ID))
        $ID = $ID["ID"];

    if(!$ID)
        return;

    $arFields = $event->getParameter("fields");

    if($arFields['UF_STATUS'] == 2){
        if(CModule::IncludeModule('iblock')){
            $res = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'=>3,
                    'PROPERTY_ID_DOGOVORA'=>$arFields['UF_ID_CONTRACT']
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'PROPERTY_COMPLETED']
            );

            $cntSdelka = $res->SelectedRowsCount();
            if($cntSdelka==1){
                if($obj = $res->GetNext(true, false)){
                    $arSdelka = $obj;
                }
                if(empty($arSdelka['PROPERTY_COMPLETED_VALUE'])){
                    CIBlockElement::SetPropertyValuesEx($arSdelka['ID'], $arSdelka['IBLOCK_ID'], array('COMPLETED' => 6));
                }
            }
        }
    }
}