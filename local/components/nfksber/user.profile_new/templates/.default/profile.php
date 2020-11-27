<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($_REQUEST['type'] != 'company'){?>
    </div>
    </div>
    <div class="container new-profile_container">
        <div class="row">
            <?
            $APPLICATION->IncludeComponent("nfksber:user.profile.info",
                "",
                Array(
                    "USER_ID" => $_REQUEST['ID'],
                    "ACTION_VARIABLE" => "action",
                )
            );
            ?>
            <?
            $APPLICATION->IncludeComponent("nfksber:user.profile.pacts",
                "",
                Array(
                    "USER_ID" => $_REQUEST['ID'],
                    "IBLOCK_ID" => 3,
                    "ITEM_COUNT" => 3,
                    "ACTION_VARIABLE" => "action",
                )
            );
            ?>
            <?
            $APPLICATION->IncludeComponent("nfksber:user.profile.post",
                "",
                Array(
                    "USER_ID" => $_REQUEST['ID'],
                    "ACTION_VARIABLE" => "action",
                )
            );
            ?>
        </div>
    </div>
<?}else{?>
    <?global $USER;?>
    <div id="ajax_profile">
        <?$APPLICATION->IncludeComponent("nfksber:user.profile",
            "",
            Array(
                "IBLOCK_ID" => 3,
                "IBLOCK_ID_COMPANY" => 8,
                "IBLOCK_ID_DEAL" => 3,
                "USER_ID" => $_REQUEST['ID'],
                'CURRENT_USER'=>$USER->GetID(),
                "CACHE_TIME"=>3600000,
                "ITEM_COUNT"=> 9,
                "PAGER_TEMPLATE"=>'anypact_pagination',
                "TYPE" => 'company'
            )
        );?>
    </div>
<?}?>