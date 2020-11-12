<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Service\GeoIp;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

class Location extends \CBitrixComponent
{
    
    public function __construct($component = null)
    {
        parent::__construct($component);
        
        Loader::includeModule('iblock');
    }

    public function onPrepareComponentParams($arParams)
    {
        if($arParams['CACHE_TYPE'] == 'N') {
            $arParams['CACHE_TIME'] = 0;
        }
        else{
            $arParams['CACHE_TIME'] = 3600;
        }

        return $arParams;
    }
    
    public function executeComponent()
    {
        $this->checkSession = check_bitrix_sessid();
        $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
        if($this->checkSession && $this->isRequestViaAjax){
            $this -> getLocation();
            echo json_encode(array("STATUS" => "SUCCESS", "CITY_NAME" => $this->arResult['GEO']['cityName']));
        }else{
            if($this->startResultCache()){
                $this->arResult['CITY'] = $this->getListCity();
                $this->EndResultCache();
            }
            $this -> arResult['NEED_GEO'] = false;
            if(!empty($_COOKIE['CITY_ANYPACT']) || !empty($_SESSION['CITY_ANYPACT'])){
                if(!empty($_COOKIE['CITY_ANYPACT']))
                    $this->arResult['GEO']['cityName'] = $_COOKIE['CITY_ANYPACT'];
                else
                    $this->arResult['GEO']['cityName'] = $_SESSION['CITY_ANYPACT'];
            }
            else{
                $this -> arResult['NEED_GEO'] = true;
            }
            $this->includeComponentTemplate();
            return $this->arResult['GEO'];
        }
    }

    private function getLocationIpGeoBase($ipAddress){
        $httpClient = new Bitrix\Main\Web\HttpClient();
        $httpClient -> setTimeout(1);
        $httpClient -> setStreamTimeout(1);
        $response = $httpClient->get("http://ipgeobase.ru:7020/geo?ip=".$ipAddress."&json=1");
        global $APPLICATION;
        $resultstr = $APPLICATION->ConvertCharset($response, "windows-1251", "UTF-8");
        $result_decode = json_decode($resultstr, true)[$ipAddress];
        return (array)$result_decode;
    }

    function getLocation()
    {
        // Перед запросом можно включить сохранение геоинформации в cookies
        \Bitrix\Main\Service\GeoIp\Manager::useCookieToStoreInfo(true);

        // Для определения местоположения требуется IP пользователя
        $ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();

        // Получение геоинформации по этому IP
        global $APPLICATION;
        $cityName = $this->getLocationIpGeoBase($ipAddress)['city'];
        if(empty($cityName)){
            $resultheader = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ipAddress, "ru", array('cityName'));
            $cityName = \Bitrix\Main\Service\GeoIp\Manager::getcityName($ipAddress, "ru");
        }
        if (empty($cityName)) $cityName = "Москва";

        setcookie('CITY_ANYPACT', $cityName);
        $_COOKIE['CITY_ANYPACT'] = $cityName;
        $_SESSION['CITY_ANYPACT'] = $cityName;

        $this->arResult['GEO']['cityName'] = $cityName;
    }

    public function getListCity(){
        $arFilter = [
            'IBLOCK_ID'=>7,
            'ACTIVE'=>'Y',
            'PROPERTY_DISPLAY_LIST_VALUE'=>'Y'
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME',
            'PROPERTY_BOLD'
        ];
        $arOrder = [
            "NAME" => "ASC"
        ];
        $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while($obj = $res->GetNext(true, false)){
            $result[$obj['ID']] = $obj;
        }

        return $result;
    }

}