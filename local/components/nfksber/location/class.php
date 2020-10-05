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
        if($this->startResultCache()){
            $this->arResult['CITY'] = $this->getListCity();
            $this->EndResultCache();
        }
        if(!empty($_COOKIE['CITY_ANYPACT'])){
            $this->arResult['GEO']['cityName'] = $_COOKIE['CITY_ANYPACT'];
        }
        else{
            // Перед запросом можно включить сохранение геоинформации в cookies
            \Bitrix\Main\Service\GeoIp\Manager::useCookieToStoreInfo(true);
            // Для определения местоположения требуется IP пользователя
            $ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
            // Получение геоинформации по этому IP
            global $APPLICATION;
            $result = file_get_contents("http://ipgeobase.ru:7020/geo?ip=".$ipAddress."&json=1");
            $resultstr = $APPLICATION->ConvertCharset($result, "windows-1251", "UTF-8");
            $result_decode = json_decode($resultstr, true)[$ipAddress];
            $cityName = $result_decode['city'];
            if(empty($result_decode['city'])){
                $resultheader = \Bitrix\Main\Service\GeoIp\Manager::getDataResult($ipAddress, "ru", array('cityName'));
                $cityName = \Bitrix\Main\Service\GeoIp\Manager::getcityName($ipAddress, "ru");
            }
            if (empty($cityName)) $cityName = "Москва";
            $this->arResult['GEO']['cityName'] = $cityName;
        }
        $this->includeComponentTemplate();
        return $this->arResult['GEO'];
    }

    public function getLocation($ipAddress)
    {
        //$obj = GeoIp\Manager::getDataResult($ipAddress, "ru", array('cityName', 'zipCode'));
        $geoData = ''; //$obj->getGeoData();
        return $geoData;
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