<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent("nfksber:redactionview.detail",
    "ajax_prev",
    Array(
        "IBLOCK_ID"             => 6,
        "IBLOCK_ID_CONTRACT"    => 6,
        "SEF_MODE"              => "N",
        "SEF_FOLDER"            => "/my_pacts/send_redaction/",
        "SECTION_ID"            => $_GET['SECTION_ID'],
        "ELEMENT_ID"            => $_REQUEST['ELEMENT_ID'],
        "SEF_URL_TEMPLATES"     => array(
            "list"      => "",
            "detail"    => "#ID#"
        )
    )
);
