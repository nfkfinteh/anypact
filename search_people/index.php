<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск людей");
?>
<!--Поиск людей-->
    <div class="tender" style="margin-bottom: 100px;">
        <h1 class="mb-4">Поиск людей</h1>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="filter-tender people-s-form">
                    <? $APPLICATION->IncludeComponent(
                        "nfksber:user.filter",
                        "",
                        Array(
                            "FILTER_NAME"=>"arrFilter"
                        )
                    ); ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <?
                    $GLOBALS['arrFilter']['!ID'] = $USER->GetID();
                    $GLOBALS['arrFilter']['UF_HIDE_PROFILE'] = 0;
                ?>
                <? $APPLICATION->IncludeComponent(
                    "nfksber:user.list",
                    "",
                    Array(
                        "FILTER_NAME" => "arrFilter",
                        "NEWS_COUNT" => 12,
                        "PAGER_TEMPLATE" => "anypact_pagination"
                    )
                ); ?>
            </div>
        </div>
    </div>
</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>