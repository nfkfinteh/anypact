<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск контрагентов");
if(!$_REQUEST['TYPE'] || $_REQUEST['TYPE']=='user'){
    $type = 'user';
}
else{
    $type = 'company';
}
?>
<!--Поиск людей-->
    <div class="tender" style="margin-bottom: 100px;">
        <?if($type=='user'):?>
            <h1 class="mb-4">Поиск физ. лиц</h1>
        <?elseif($type=='company'):?>
            <h1 class="mb-4">Поиск юр. лиц</h1>
        <?endif?>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="cotragent">
                    <?if($type=='user'):?>
                        <span class="cotragent__button_active">Физ. лица</span> /
                        <a class="cotragent__button" href="<?=$APPLICATION->GetCurPage().'?TYPE=company'?>">Юр. лица</a>
                    <?elseif($type=='company'):?>
                        <a class="cotragent__button" href="<?=$APPLICATION->GetCurPage().'?TYPE=user'?>">Физ. лица</a> /
                        <span class="cotragent__button_active">Юр. лица</span>
                    <?endif?>
                </div>
                <div class="filter-tender people-s-form">
                    <? $APPLICATION->IncludeComponent(
                        "nfksber:user.filter",
                        "",
                        Array(
                            "FILTER_NAME"=>"arrFilter",
                            'TYPE_FILTER'=>$type
                        )
                    ); ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <?if($type=='user'):?>
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
                <?elseif($type=='company'):?>
                    <? $APPLICATION->IncludeComponent(
                        "nfksber:company.list",
                        "",
                        Array(
                            "FILTER_NAME" => "arrFilter",
                            "NEWS_COUNT" => 12,
                            "IBLOCK_COMPANY"=>8,
                            "PAGER_TEMPLATE" => "anypact_pagination"
                        )
                    ); ?>
                <?endif?>
            </div>
        </div>
    </div>
</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>