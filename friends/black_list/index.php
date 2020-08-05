<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Черный список");
?>
<!--Черный список-->
<?
    global $USER;
    if (!$USER->IsAuthorized()){
        LocalRedirect("/");
    }?>
    <div class="tender" style="margin-bottom: 100px;">
        <h1 class="mb-4">Черный список</h1>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu", 
                    "vertical_friends", 
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_USE_GROUPS" => "N",
                        "ROOT_MENU_TYPE" => "left",
                        "USE_EXT" => "Y",
                        "COMPONENT_TEMPLATE" => "vertical_friends"
                    ),
                    false
                );?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <?
                $APPLICATION->IncludeComponent(
                    "nfksber:user.list",
                    "friends",
                    Array(
                        "FILTER_NAME" => "arrFilter",
                        "NEWS_COUNT" => 12,
                        "PAGER_TEMPLATE" => "anypact_pagination",
                        "FRIENDS_STATUS" => "B",
                        "CACHE_TIME" => 0,
                    )
                );
                ?>
            </div>
        </div>
    </div>
    </div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>