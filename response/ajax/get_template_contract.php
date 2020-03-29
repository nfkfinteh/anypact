<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

$arPact = array();
$SECTION_ID = 0;
$SECTION_ID = $_POST["idcontract"];
$idSdelka   = $_POST["id_element"];
$returnURL  = $_POST["return_url"];
if(CModule::IncludeModule("iblock"))
{
    $arSelect = Array();
    $arFilter = Array("IBLOCK_ID"=>5, "SECTION_ID" => $SECTION_ID,);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);

    while($ob = $res->GetNext())
    {
        $arr[] = $ob;

    }
}
if(!empty($arr)){
    foreach ($arr as $item) {
        ?>        
        <a class="navbar-brand" href="<?=$returnURL?>" data-id="0" style="width: 100%;">← Назад</a>
        <?if($_POST["type"]=='new_dogovor'):?>
            <a href="/my_pacts/add_new_dogovor/?ADD=ADD&ID_TEMPLATE=<?=$item["ID"]?>" style="width:100%; display: inline-block;">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/document_contract.png" /><?=$item["NAME"]?>
            </a>
        <?else:?>
            <a href="/my_pacts/add_my_dogovor/?EDIT=ADD&ID_TEMPLATE=<?=$item["ID"]?>&ELEMENT_ID=<?=$idSdelka?>" style="width:100%; display: inline-block;">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/document_contract.png" /><?=$item["NAME"]?>
            </a>
        <?endif?>
        <?
    }
}else { ?>
    <a class="navbar-brand" href="<?=$returnURL?>" data-id="0">← Назад</a>
    <p>Извините, шаблон договора появится в ближайшее время.</p>
    <p>Можете воспользоваться шаблоном "Иной договор".</p>
    <p>Если Вам нужно составить договор, Вы можете обратиться  к нашим специалистам info@anypact.ru</p>
<? }
?>