<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');

$arPact = array();
$SECTION_ID = 0;
$SECTION_ID = $_POST["idcontract"];
$idSdelka = $_POST["id_element"];
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
foreach ($arr as $item) {
    ?>
    <a class="navbar-brand" href="/my_pacts/add_my_dogovor/?ELEMENT_ID=<?=$idSdelka?>&EDIT=EDIT" data-id="0">← Назад</a>
    <a href="/my_pacts/add_my_dogovor/?EDIT=ADD&ID_TEMPLATE=<?=$item["ID"]?>&ELEMENT_ID=<?=$idSdelka?>" style="width:100%; display: inline-block;">
        <img src="<?=SITE_TEMPLATE_PATH?>/img/document_contract.png" /><?=$item["NAME"]?></a>
    <?
}
?>