<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent("nfksber:dogovorview.detail",
"new_redaction",
    Array(
        "IBLOCK_ID"             => 3,
        "IBLOCK_ID_CONTRACT"    => 4,
        "SEF_MODE"              => "N",
        "SEF_FOLDER"            => "/pacts/view_pact/",
        "SECTION_ID"            => $_GET['SECTION_ID'],
        "ELEMENT_ID"            => $_POST['ELEMENT_ID'],
        "SEF_URL_TEMPLATES"     => array(
                "list"      => "",
                "detail"    => "#ID#"
            )
        )
);
