<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class GetPdf{
    public function getSendContractItem($IDSendItem, $big_text = false){
        CModule::IncludeModule("highloadblock");
        CModule::IncludeModule("iblock");
        // получить все подписанны сделки
        $ID_hl_send_contract_text = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract_text)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $IDSendItem)
        ));

        while($arData = $rsData->Fetch()){
            $arSigned  = $arData;
        }

        // получить данные пользователей по id
        // пользователь А владелец контракта
        if(!empty($arSigned['UF_ID_COMPANY_A'])){
            //компания
            $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>COMPANY_IB_ID, 'ID'=>$arSigned['UF_ID_COMPANY_A'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
            if($obj=$res->GetNext(true, false)){
                $arCompany_A = $obj;
            }
        }
        //пользователь
        $rsUser = CUser::GetByID($arSigned['UF_ID_USER_A']);
        $arUser_A = $rsUser->Fetch();
        
        //статус подписи
        if(!empty($arSigned['UF_VER_CODE_USER_A'])){
            $hash_A = md5($arSigned['UF_VER_CODE_USER_A']);
        }

        // пользователь В подписывающий
        if(!empty($arSigned['UF_ID_COMPANY_B'])){
            //компания
            $res = CIBlockElement::GetList([], ['IBLOCK_ID'=>COMPANY_IB_ID, 'ID'=>$arSigned['UF_ID_COMPANY_B'], 'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_INN']);
            if($obj=$res->GetNext(true, false)){
                $arCompany_B = $obj;
            }
        }
        //пользователь
        $rsUser = CUser::GetByID($arSigned['UF_ID_USER_B']);
        $arUser_B = $rsUser->Fetch();
        
        //статус подписи
        if(!empty($arSigned['UF_VER_CODE_USER_B'])){
            $hash_B = md5($arSigned['UF_VER_CODE_USER_B']);
        }
        if(!empty($hash_A) && !empty($hash_B)){
            if ($big_text) {
                $signed_text = '<table style="width:100%; margin 50px 0;">';
            } else {
                $signed_text = '<table style="width:100%; border-top: 1px solid #9E9E9E; margin: 50px 0; font-size:8px;">';
            }
            $signed_text .= '<tr>';
            $signed_text .= '<td style="width:44%">';
            $signed_text .= '<b>Подписано простой электронной подписью:</b>';
            if(!empty($arCompany_A)){
                //компания
                $signed_text .= '<br>'.$arCompany_A['NAME'];
            }

            $signed_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'].' '.$arUser_A['SECOND_NAME'];

            if (!empty($arCompany_A)) {
                $signed_text .= '<br>'.$arCompany_A['PROPERTY_INN_VALUE'];
            } else{
                //пользователь
                $signed_text .= '<br>#'.$arUser_A['UF_PASSPORT'];
            }
            $DateTime = new DateTime($arSigned["UF_TIME_SEND_USER_A"]);
            $signed_text .= '<br>'.$DateTime->format("Y-m-d H:i:s");
            $signed_text .= '<br>'.$hash_A;
            $signed_text .= '</td>';
            $signed_text .= '<td style="width:2%"></td>';
            $signed_text .= '<td style="width:44%">';
            $signed_text .= '<b>Подписано простой электронной подписью:</b>';
            if(!empty($arCompany_B)) {
                //компания
                $signed_text .= '<br>'.$arCompany_B['NAME'];
            }

            $signed_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];

            if(!empty($arCompany_B)) {
                $signed_text .= '<br>'.$arCompany_B['PROPERTY_INN_VALUE'];
            } else {
                //пользователь
                $signed_text .= '<br>#'.$arUser_B['UF_PASSPORT'];
            }

            $DateTime = new DateTime($arSigned["UF_TIME_SEND_USER_B"]);
            $signed_text .= '<br>'.$DateTime->format("Y-m-d H:i:s");
            $signed_text .= '<br>'.$hash_B;
            $signed_text .= '</td>';
            $signed_text .= '</tr>';
            $signed_text .= '</table>';
        }

        // // получить данные пользователей по id
        // // пользователь А владелец контракта
        // $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_A']);
        // $arUser_A = $rsUser->Fetch();
        // //статус подписи
        // $hash_A ='--';
        // if(!empty($arSendItem['UF_VER_CODE_USER_A'])){
        //     $status_send_a = '';
        //     $hash_A =md5($arSendItem['UF_VER_CODE_USER_A']);
        // }else{
        //     $status_send_a = '<button class="btn btn-nfk" id="send_contract_owner" data-id='.$arSendItem['ID'].'" data-user="'.$arSendItem['UF_ID_USER_A'].'" >Подписать</button>';
        // }


        // // пользователь В подписывающий
        // $rsUser = CUser::GetByID($arSendItem['UF_ID_USER_B']);
        // $arUser_B = $rsUser->Fetch();
        // $hash_B = md5($arSendItem['UF_VER_CODE_USER_B']);

        // $Send_text = '<table style="width:100%; border-top: 1px solid #9E9E9E; margin: 50px 0; font-size:8px;">';
        // $Send_text .= '<tr>';
        // $Send_text .= '<td style="width:44%">';
        // $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        // $Send_text .= '<br>'.$arUser_A['LAST_NAME'].' '.$arUser_A['NAME'].' '.$arUser_A['SECOND_NAME'];
        // $Send_text .= '<br>#'.$arUser_A['UF_PASSPORT'];
        // $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_A"]->format("Y-m-d H:i:s");
        // $Send_text .= '<br>'.$hash_A;
        // $Send_text .= '</td>';
        // $Send_text .= '<td style="width:2%"></td>';
        // $Send_text .= '<td style="width:44%">';
        // $Send_text .= '<b>Подписано простой электронной подписью:</b>';
        // $Send_text .= '<br>'.$arUser_B['LAST_NAME'].' '.$arUser_B['NAME'].' '.$arUser_B['SECOND_NAME'];
        // $Send_text .= '<br>#'.$arUser_B['UF_PASSPORT'];
        // $Send_text .= '<br>'.$arSendItem["UF_TIME_SEND_USER_B"]->format("Y-m-d H:i:s");
        // $Send_text .= '<br>'.$arSendItem['UF_VER_CODE_USER_B'];
        // $Send_text .= '</td>';
        // $Send_text .= '</tr>';
        // $Send_text .= '</table>';

        $arSend['TEXT'] = $signed_text;

        return $arSend;
    }

    public function getSendContractText($IDSendItem){
        Loader::includeModule("highloadblock");

        $id = intval($_GET['ID']);
        $hlbl = 7;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_ID_SEND_ITEM"=>$IDSendItem)
        ));

        if($obj = $rsData->Fetch()) $arContract = $obj;

        return $arContract;
    }
}
