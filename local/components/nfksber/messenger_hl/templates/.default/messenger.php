<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
        <?
        $APPLICATION->IncludeComponent("nfksber:messenger_hl.dialog.list",
            "",
            Array(
                "DIALOG_ID" => $_REQUEST['chat'],
                "ACTION_VARIABLE" => "action",
            )
        );
        ?>
    </div>
    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-12">
        <?
        $APPLICATION->IncludeComponent("nfksber:messenger_hl.message.list",
            "",
            Array(
                "DIALOG_ID" => $_REQUEST['chat'],
                "ACTION_VARIABLE" => "action",
            )
        );
        ?>
    </div>
</div>
<?if($GLOBALS['DISCUSSION_ID']){?>
    <?
    $APPLICATION->IncludeComponent("nfksber:messenger.discussion.detail",
        "",
        Array(
            "DISCUSSION_ID" => $GLOBALS['DISCUSSION_ID'],
            "ACTION_VARIABLE" => "action",
        )
    );
    ?>
<?}?>