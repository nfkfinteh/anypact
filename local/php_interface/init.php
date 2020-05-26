<?php define("PREFIX_PATH_404", "/404.php");
include_once 'function.php';

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler("main", "OnAfterEpilog", "Prefix_FunctionName");

//проверка являеться ли пользователь сотрудником компании и сброс по необходимости
$eventManager->addEventHandler("main", "OnAfterEpilog", "checkUserCompany");


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

AddEventHandler("main", "OnBuildGlobalMenu", "OnBuildGlobalMenu");
function OnBuildGlobalMenu(&$arGlobalMenu, &$arModuleMenu)
{
	global $USER;
	if(!$USER->IsAdmin())
    return;

    $arItems[] = array(
        'parent_menu' => 'global_menu_anypact',
        'section' => 'gosuslugi',
        'sort' => 1,
        'url' => 'anypact_gosuslugi.php?lang=' . LANGUAGE_ID,
        'text' => 'Госуслуги',
        'title' => 'Настройка госуслуг',
        'icon' => 'gosuslugi_menu_icon',
        'page_icon' => 'gosuslugi_page_icon',
        'items_id' => 'menu_gosuslugi',
        'items' => array()
    );

    $arGlobalMenu[] = array(
        'menu_id' => 'anypact',
        'text' => 'AnyPact',
        'title' => 'Настройки сайта AnyPact',
        'sort' => 550,
        'item_id' => 'global_menu_anypact',
        'help_section' => 'anypact',
        'items' => $arItems
    );
}