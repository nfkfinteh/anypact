<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($_REQUEST['error']){
    if($_REQUEST['error'] == 'props_empty' || $_REQUEST['error'] == 'props_no_number'){
        $arProps = explode(', ', $_REQUEST['props']);
        $props = '';
        foreach($arProps as $key => $arProp){
            $props .= $arResult['PROPERTIES'][$arProp]['NAME'].', ';
        };
        $props = substr($props, 0, -2);
        $arResult['ERROR'] = GetMessage($_REQUEST['error']).$props;
    }else{
        $arResult['ERROR'] = $_REQUEST['error'];
    }
}