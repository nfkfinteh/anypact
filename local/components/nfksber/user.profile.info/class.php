<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CUserProfileInfo extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "USER_ID" => intval($arParams["USER_ID"]),
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        );
        return $result;
    }

    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    function getUserInfo($user_id){
        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('SELECT' => array("UF_ABOUT", "UF_DISPLAY_PHONE", "UF_DISPLAY_DATE", "UF_ESIA_AUT", "UF_WORK", "UF_EDUCATION", "UF_DISPLAY_ADDRESS", "UF_N_HOUSE", "UF_N_HOUSING", "UF_N_APARTMENT", "UF_REGION", "UF_STREET"), 'FIELDS' => array("ID", "NAME", "LAST_NAME", "SECOND_NAME", "PERSONAL_BIRTHDAY", "PERSONAL_PHONE", "PERSONAL_PHOTO", "PERSONAL_GENDER", "PERSONAL_CITY", "PERSONAL_COUNTRY", "PERSONAL_STATE", "PERSONAL_ZIP")));
        if($arUser = $res->Fetch()) {
            $arResult['USER'] = $arUser;
        }
        $res = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'],
                'ACTIVE'=>'Y',
                [
                    'LOGIC'=> 'OR',
                    ['PROPERTY_DIRECTOR_ID'=>$this->arParams['USER_ID']],
                    ['PROPERTY_STAFF'=>$this->arParams['USER_ID']]
                ]
            ],
            false,
            false,
            ['IBLOCK_ID', 'ID', 'NAME']
        );
        while($obj = $res->GetNext()){
            $arCompany[] = $obj;
        }
        $arResult['COMPANY'] = $arCompany;
        unset($arCompany);

        //добавление в представители компании
        //только пользователей подтвердивших на гос услуга
        if($arResult['USER']['UF_ESIA_AUT']==1){
            $res = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'=>$this->arParams['IBLOCK_ID_COMPANY'],
                    'ACTIVE'=>'Y',
                    'PROPERTY_DIRECTOR_ID'=>$this->arResult['CURRENT_USER'],
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'NAME']
            );
            while($obj = $res->GetNextElement()){
                $arr = $obj->GetFields();
                $arr['PROPERTY_STAFF'] = $obj->GetProperty('STAFF')['VALUE'];
                $arr['PROPERTY_STAFF_NO_ACTIVE'] = $obj->GetProperty('STAFF_NO_ACTIVE')['VALUE'];

                if(!empty($arr['PROPERTY_STAFF']) && in_array($this->arParams['USER_ID'], $arr['PROPERTY_STAFF'])){
                    $arr['STAFF'] = true;
                }

                if(!empty($arr['PROPERTY_STAFF_NO_ACTIVE']) && in_array($this->arParams['USER_ID'], $arr['PROPERTY_STAFF_NO_ACTIVE'])){
                    $arr['STAFF_NO_ACTIVE'] = true;
                }

                $arResult['COMPANY_CURRENT_USER'][] = $arr;
            }
        }
        return $arResult;
    }

    public function getFrends($current_user){
        $entity_data_class = self::GetEntityDataClass(14);
        $rsData = $entity_data_class::getList(array(
            "select" => array("UF_USER_A", "UF_USER_B", "UF_ACCEPT"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $current_user),
                array("UF_USER_B" => $current_user, "UF_ACCEPT" => HLB_USER_FRIENDS_ACCEPT_Y),
            ))
        ));
        while($arData = $rsData->Fetch()){
            if($arData["UF_USER_A"] == $current_user && $arData['UF_ACCEPT'] == HLB_USER_FRIENDS_ACCEPT_A){
                $result['FRIENDS_REQUEST'][] = $arData["UF_USER_B"];
            }else{
                $result['FRENDS'][] = $arData["UF_USER_A"];
                $result['FRENDS'][] = $arData["UF_USER_B"];
            }
        }
        if(empty($result['FRENDS'])){
            $result['FRENDS'] = [];
        }
        if(empty($result['FRIENDS_REQUEST'])){
            $result['FRIENDS_REQUEST'] = [];
        }

        $result['FRENDS'] = array_unique($result['FRENDS']);

        if(isset($result['FRENDS'][array_search($current_user, $result['FRENDS'])]))
            unset($result['FRENDS'][array_search($current_user, $result['FRENDS'])]);

        return $result;
    }

    public function getBlackList(){
        $entity_data_class = self::GetEntityDataClass(15);
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array(array(
                "LOGIC" => "OR",
                array("UF_USER_A" => $this->arResult["CURRENT_USER"], "UF_USER_B" => $this->arResult["USER_ID"]),
                array("UF_USER_A" => $this->arResult["USER_ID"], "UF_USER_B" => $this->arResult["CURRENT_USER"]),
            ))
        ));
        while($arData = $rsData->Fetch()){
            if($arData['UF_USER_A'] == $this->arResult["CURRENT_USER"]){
                $result['CLOSE'] = true;
            }elseif($arData['UF_USER_B'] == $this->arResult["CURRENT_USER"]){
                $result['CLOSED'] = true;
            }
        }

        if(empty($result)){
            $result = [];
        }

        return $result;
    }

    public function executeComponent()
    {
        if(CModule::IncludeModule("highloadblock")){
            global $USER;
            $this->arResult["CURRENT_USER"] = $USER -> GetID();
            $this->arResult["USER_ID"] = $this -> arParams['USER_ID'];
            $this->arResult = array_merge($this->arResult, $this -> getUserInfo($this->arResult["USER_ID"]));
            $arFrends = $this->getFrends($this->arResult["CURRENT_USER"]);
            $this->arResult = array_merge($this->arResult, $arFrends);
            $this->arResult["BLACKLIST"] = $this->getBlackList();
            $this->includeComponentTemplate();
        }
        return $this->arResult;
    }
};

?>