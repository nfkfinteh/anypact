<?
if(!empty($arResult['COMPANY_PROP'])){
    foreach ($arResult['COMPANY_PROP'] as $code=>$prop){
        if(!is_array($prop)){
            $arResult['JS_DATA']['USER'][$code] = $prop;
        }
        else{
            $arResult['JS_DATA']['USER'][$code] = $prop['VALUE'];
        }
    }
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