<?
// необходимые классы
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class sendsms extends CBitrixComponent {

    public function get_status_all_pact($id) {
        CModule::IncludeModule("highloadblock");
        $hlblock = HL\HighloadBlockTable::getById($id)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList();
         
        while($arData = $rsData->Fetch()){                
            $arStatus[$arData['ID']]  = $arData; 
        }
         
        return  $arStatus;
    }


    public function get_item_filter($id, $arFilter){

        // получить все подписанные сделки
        $ID_hl_send_contract = 3;
        $hlblock = HL\HighloadBlockTable::getById($ID_hl_send_contract)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $entity_data_class = $entity->getDataClass();
        
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order"  => array("ID" => "ASC"),
            "filter" => $arFilter
        ));            
                   
        while($arData = $rsData->Fetch()){                
            $arStatus = $arData; 
        }
         
        return  $arStatus;

    }
}


?>