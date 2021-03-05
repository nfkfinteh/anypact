<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT']."/local/class/CMoneta.php");

use Bitrix\Main\Loader,
    Bitrix\Iblock,
    Bitrix\Highloadblock as HL;

class CMonetaWalletPayments extends CBitrixComponent
{    
    public function onPrepareComponentParams($arParams)
    {
        $result = array(
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"]
        );
        return $result;
    }

    private function getPagination($total_pages, $current_page = 1){
        if($total_pages > 1){
            ob_start();
            ?>
            <ul class="pagination justify-content-center">
                <li class="page-item <?if($current_page == 1){?>disabled<?}?>"><a class="page-link" href="#" data-page="<?=$current_page-1?>">←</a></li>
                <li class="page-item <?if($current_page == 1){?>disabled<?}?>"><a class="page-link" href="#" data-page="<?=$current_page-1?>">Назад</a></li>
                <li class="page-item <?if($current_page == 1){?>active disabled<?}?>"><a class="page-link" href="#" data-page="1">1</a></li>
                <?if($current_page < 4){?>
                    <?if($total_pages > 2 || $current_page == 2){?>
                        <li class="page-item <?if($current_page == 2){?>active disabled<?}?>"><a class="page-link" href="#" data-page="2">2</a></li>
                    <?}?>
                    <?if($total_pages > 3 || $current_page == 3){?>
                        <li class="page-item <?if($current_page == 3){?>active disabled<?}?>"><a class="page-link" href="#" data-page="3">3</a></li>
                    <?}?>
                <?}else{?>
                    <li class="page-item"><a class="page-link" href="#" data-page="<?=round($current_page/2)?>">...</a></li>
                    <?if($current_page == $total_pages){?>
                        <li class="page-item"><a class="page-link" href="#" data-page="<?=$current_page-2?>"><?=$current_page-2?></a></li>
                    <?}?>
                    <li class="page-item"><a class="page-link" href="#" data-page="<?=$current_page-1?>"><?=$current_page-1?></a></li>
                    <li class="page-item"><a class="page-link active disabled" href="#" data-page="<?=$current_page?>"><?=$current_page?></a></li>
                <?}?>
                <?if($current_page+1 < $total_pages && $total_pages >= 4){?>
                    <li class="page-item"><a class="page-link" href="#" data-page="<?=$current_page+1?>"><?=$current_page+1?></a></li>
                <?}?>
                <?if($current_page+2 < $total_pages){?>
                    <li class="page-item"><a class="page-link" href="#" data-page="<?=(round($current_page/2)+round($total_pages/2))?>">...</a></li>
                <?}?>
                <?if($current_page != $total_pages){?>
                    <li class="page-item"><a class="page-link " href="#" data-page="<?=$total_pages?>"><?=$total_pages?></a></li>
                <?}?>
                <li class="page-item <?if($current_page == $total_pages){?>disabled<?}?>"><a class="page-link" href="#" data-page="<?=$current_page+1?>">Вперед</a></li>
                <li class="page-item <?if($current_page == $total_pages){?>disabled<?}?>"><a class="page-link" href="#" data-page="<?=$current_page+1?>">→</a></li>
            </ul>
            <?
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        return false;
    }

    private function getHistory($user_id, $data, $page = 1){
        if(empty($data['DATE_FROM'])){
            $date = new DateTime();
            $date->modify('+ 1 month');
        }else
            $date = new DateTime($data['DATE_FROM']);
        
        $data['DATE_FROM'] = $date->format("Y-m-d")."T00:00:00.000+03:00";

        if(empty($data['DATE_TO']))
            $date = new DateTime();
        else
            $date = new DateTime($data['DATE_TO']);
        
        $data['DATE_TO'] = $date->format("Y-m-d")."T23:59:59.999+03:00";

        $res = CUser::GetList(($by="personal_country"), ($order="desc"), array("ID" => $user_id), array('FIELDS' => array("ID"), 'SELECT' => array("UF_MONETA_UNIT_ID", "UF_MONETA_ACCOUNT_ID")));
        if($arUser = $res->Fetch()){
            return CMoneta::getHistory($arUser['UF_MONETA_UNIT_ID'], $arUser['UF_MONETA_ACCOUNT_ID'], $data['DATE_FROM'], $data['DATE_TO'], $page);
        }
        return false;
    }

    public function executeComponent()
    {
        global $USER;
        if($USER -> IsAuthorized()){
            $this->checkSession = check_bitrix_sessid();
            $this->isRequestViaAjax = $this->request->isPost() && $this->request->get('via_ajax') == 'Y';
            if($this->checkSession && $this->isRequestViaAjax && $this->request->get('action') == 'getHistory'){
                $arRes = self::getHistory($USER -> GetID(), $_REQUEST['data'], $_REQUEST['page']);
                if($arRes){
                    if($arRes['STATUS'] != "SUCCESS")
                        die(json_encode($arRes));
                    $this -> arResult['IS_AJAX'] = "Y";
                    $this -> arResult['page'] = $_REQUEST['page'];
                    $this -> arResult['ITEMS'] = $arRes['DATA']['ITEMS'];
                    $this -> arResult['pagination'] = self::getPagination($arRes['DATA']['pagesCount'], $_REQUEST['page']);
                    ob_start();
                    $this->includeComponentTemplate();
                    $html = ob_get_contents();
                    ob_end_clean();
                    echo json_encode(array("STATUS" => "SUCCESS", "HTML" => $html));
                }else{
                    echo json_encode(array("STATUS" => "ERROR", "ERROR_DESCRIPTION" => "Неизвестная ошибка"));
                }
            }else{
                $this->includeComponentTemplate();
            }
            return $this->arResult;
        }
    }
};

?>