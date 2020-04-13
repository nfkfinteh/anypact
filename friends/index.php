<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Мои друзья");
?>
<!--Мои друзья-->
<?
    global $USER;
    if (!$USER->IsAuthorized()){
        LocalRedirect("/");
    }?>
    <div class="tender" style="margin-bottom: 100px;">
        <h1 class="mb-4">Мои друзья</h1>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?
                    $GLOBALS['arrFilter']['UF_HIDE_PROFILE'] = 0;

                    $arFilter = array("ID" => $USER->GetID());
                    $arParams["SELECT"] = array("ID", "UF_FRENDS");
                    $res = CUser::GetList($by ="timestamp_x", $order = "desc", $arFilter, $arParams);
                    $no_friend = false;
                    if($obj=$res->GetNext()){
                        if(!empty($obj['UF_FRENDS'] && $obj['UF_FRENDS'] != 'null')){
                            $GLOBALS['arrFilter']['ID'] = implode('|', json_decode($obj['~UF_FRENDS']));
                        }else{
                            $no_friend = true;
                        }
                    }
                ?>
                <? if(!$no_friend){
                    $APPLICATION->IncludeComponent(
                        "nfksber:user.list",
                        "friends",
                        Array(
                            "FILTER_NAME" => "arrFilter",
                            "NEWS_COUNT" => 12,
                            "PAGER_TEMPLATE" => "anypact_pagination"
                        )
                    );
                }else{
                    echo '<p>Список друзей пуст.</p><p>Воспользуйтейтесь <a href="/search_people/">поиском</a>.</p>';
                } ?>
            </div>
        </div>
    </div>
</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>