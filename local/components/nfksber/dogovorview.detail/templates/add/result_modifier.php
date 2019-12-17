<?
if(!empty($arResult['COMPANY_PROP'])){
    $arResult['JS_DATA']['USER'] = [
        'NAME' => $arResult['COMPANY_PROP']["NAME"],
        'SURNAME' => $arResult['COMPANY_PROP']["NAME"],
        'MIDLENAME' => $arResult['COMPANY_PROP']["NAME"],
        'PHONE' => '',
        'PASSPORT' => ''
    ];
}
else{
    $arResult['JS_DATA']['USER'] = [
        'NAME' => $arResult["USER_PROP"]["NAME"],
        'SURNAME' => $arResult["USER_PROP"]["LAST_NAME"],
        'MIDLENAME' => $arResult["USER_PROP"]["SECOND_NAME"],
        'PHONE' => $arResult["USER_PROP"]["PERSONAL_PHONE"],
        'PASSPORT' => $arResult["USER_PROP"]["UF_PASSPORT"].' '.$arResult["USER_PROP"]["UF_KEM_VPASSPORT"]
    ];
}
?>