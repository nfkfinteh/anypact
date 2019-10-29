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
            $ipAddress = GeoIp\Manager::getRealIp();
            GeoIp\Manager::useCookieToStoreInfo(true);
            $this->arResult['GEO'] = (array) $this->getLocation($ipAddress);
        }
        $this->includeComponentTemplate();
        return $this->arResult['GEO'];
    }

    public function getLocation($ipAddress)
    {
        $obj = GeoIp\Manager::getDataResult($ipAddress, "ru", array('cityName', 'zipCode'));
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
        $res = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while($obj = $res->GetNext(true, false)){
            $result[$obj['ID']] = $obj;
        }

        return $result;
    }

}