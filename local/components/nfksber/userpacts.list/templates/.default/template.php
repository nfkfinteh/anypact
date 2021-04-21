<?
// статусы объявлений
$PactStatus = array(
    'Y' => 'Active.png',
    'N' => 'DontActive.png'
);
// статус договоров
$arrStatus = array(
    'Отменен', 'Подписан контрагентом', 'Изменен конторагентом', 'Изменен и подписан контрагентом', 'Изменен Вами'
);
// статусы договоров ожидающих подписи контрагентов
$arrStatusAwait = array(
    '', 'Ожидает подписи контрагентом', '', '', 'Изменен Вами и ожидает подписи контрагентом'
);

function bildTable($arData, $type = "OTHER"){
    foreach($arData as $key => $data){?>
        <!--Запись в таблице--->
        <div class="d-flex d-md-none justify-content-between collapse-header">
            <div><?if(!empty($data['CONTRACT'])) echo '#'.$data['CONTRACT']['ID'].' '.$data['CONTRACT']['NAME']; ?></div>
            <div class="collapse-arrow position-relative"></div>
        </div>
        <div class="d-md-table-row collapse-body" <?if(array_key_first($arData) !== $key){?>style="display: none;"<?}?>>

            <div class="d-md-none text-gray"><?=$data['PARTNER']['FIRST_LETTER']?></div>
            <div class="first-face d-md-table-cell">
                <a class="d-flex align-items-center" href="/profile_user/?ID=<?=$data['PARTNER']['ID']; if($data['PARTNER']['TYPE'] == "C") echo "&type=company";?>" style="text-decoration: none;">
                    <?if(!empty($data['PARTNER']['PICTURE'])){?>
                        <img src="<?=$data['PARTNER']['PICTURE']?>" alt="">
                    <?}else {?>
                        <h3><?=$data['PARTNER']['FIRST_LETTER']?></h3>
                    <?}?>
                    <span style="margin-left: 10px;"><?=$data['PARTNER']['NAME']?></span>
                </a>
            </div>
            <div class="d-md-table-cell d-none"><?if(!empty($data['CONTRACT'])) echo $data['CONTRACT']['NAME']; ?></div>
            <div class="d-md-none text-gray">Дата и время подписания</div>
            <div class="d-md-table-cell"><?=$data['DATA']?></div>
            <?if($type == "REDACTION"){?>
                <div class="d-md-table-cell"><?=$data['STATUS']?></div>
            <?}?>
            <div class="d-md-table-cell"><a class="button-link" href="/contract/?ID=<? echo $data["ID"]; if($type == "SIGNED") echo "&COMPLETE=Y"; else{ if($data['PARTNER']['TYPE'] == "C") echo "&COMPANY_ID="; else echo "&USER_ID="; echo $data['PARTNER']['ID'];}?>">Посмотреть</a></div>
        </div>
        <!--//Запись в таблице--->
    <?}
}
?>
<div class="d-flex flex-wrap align-items-center">
    <h5>Мои предложения <?if(!empty($arResult["DEALS"])){?>(<?=count($arResult["DEALS"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Ваши предложения о заключении сделок.</div>
    <div class="position-relative not_auth-error-block new-pact-block">
        <a href="/my_pacts/edit_my_pact/?ACTION=ADD" class="btn btn-nfk btn-add-contract ml-auto <?if($arResult['CURRENT_USER']['ESIA'] != 1):?>disabled<?endif?>">
            + создать новое предложение
        </a>
        <?if($arResult['CURRENT_USER']['ESIA'] != 1):?>
            <div class="not_auth-error">
                <span class="triangle" style="display: block; z-index: 1;">▲</span>
                <div>Для размещения предложения необходимо <a target="__blank" href="/profile/#aut_esia">подтвердить свой аккаунт с помощью учетной записи портала Госуслуг</a></div>
            </div>
        <?endif;?>
    </div>
</div>
    <?
    if (!empty($arResult["DEALS"])):?>
        <!--Адаптивная табличка объявления--->
        <div class="d-md-table">
            <div class="d-none d-md-table-row t-head">
                <div class="d-md-table-cell">Наименование</div>
                <div class="d-md-table-cell">Активно до</div>
                <div class="d-md-table-cell">Активность</div>
                <div class="d-md-table-cell"></div>
                <!-- кнопки близко, удаление только из карточки <div class="d-md-table-cell"></div>  -->              
            </div>
            <? foreach ($arResult["DEALS"] as $key => $pact) { // выборка объявлений ?>
            <!--Запись в таблице--->
                <div class="d-flex d-md-none justify-content-between collapse-header">
                    <div><?= $pact["NAME"] ?></div>
                    <div class="collapse-arrow position-relative"></div>
                </div>
                <div class="d-md-table-row collapse-body" <?if(array_key_first($arResult["DEALS"]) !== $key){?>style="display: none;"<?}?>>
                    <div class="d-md-none text-gray"></div>
                    <div class="first-face d-md-table-cell">
                        <span class="d-flex align-items-center">
                            <? if(!empty($pact['URL_IMG_PREVIEW'])) {?>
                                <img src="<?=$pact['URL_IMG_PREVIEW']?>" height="45" alt ="<?=$pact['NAME']?>" />
                            <?}?>
                            <span style="margin-left: 10px;"><?=$pact["NAME"]?> <?if($pact['PROPERTIES']['MODERATION']['VALUE'] !='Y'):?>(на модерации)<?endif?></span>
                            </span>
                    </div>
                    <div class="d-md-none text-gray">Активно до:</div>
                    <div class="d-md-table-cell"><?=$pact["ACTIVE_TO"]?></div>
                    
                        <div class="d-md-none text-gray">Активность</div>
                        <div class="d-md-table-cell">
                            <?if($pact['PROPERTIES']['MODERATION']['VALUE'] =='Y'):?>
                                <button iditem="<?= $pact["ID"]?>" active="<?= $pact["ACTIVE"]?>" class="onActive">
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus[$pact["ACTIVE"]]?>" />
                                </button>
                            <?else:?>
                                <button class="onActive" disabled>
                                    <img src="<?=SITE_TEMPLATE_PATH?>/image/<?=$PactStatus['N']?>" />
                                </button>
                            <?endif?>
                        </div>
                        <div class="d-md-table-cell">
                            <a class="button-link" href="/my_pacts/edit_my_pact/?ELEMENT_ID=<?= $pact['ID'] ?>&ACTION=EDIT">Посмотреть</a>
                        </div>
                    
                    <?/*кнопки близко, удаление только из карточки
                    <div class="d-md-table-cell">
                        <a href="#" class="button-link" data-id="<?=$pact['ID']?>" data-toggle="modal" data-target=".bd-message-modal-sm" class="modal_deleteItem">Удалить</a>
                    </div>
                    */?>
                </div>
            <!--//Запись в таблице--->
            <? } ?>
        </div>
        <?if(count($arResult["DEALS"]) > 1){?>
            <div class="expand-list">
                <div class="hide-show-scroll">Показать все</div>
            </div>
        <?}?>
        <!------------------------>
    <?else:?>
        <h3>У Вас нет предложений</h3>
        <!-- <button class="info-btn">?</button> -->
        <div class="info-content">В данном разделе содержатся Ваши предложения о заключении сделок.</div>
    <?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Заключенные договоры -->
<div class="d-flex flex-wrap align-items-center">
    <h5>Заключенные договоры <?if(!empty($arResult["SIGNED_CONTRACTS"])){?>(<?=count($arResult["SIGNED_CONTRACTS"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с двух сторон.</div>
</div>
<?if(!empty($arResult["SIGNED_CONTRACTS"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата и время подписания</div>
            <!-- <div class="d-md-table-cell">Статус</div>             -->
            <div class="d-md-table-cell"></div>
        </div>
        <? echo bildTable($arResult["SIGNED_CONTRACTS"], "SIGNED");?>
    </div>
    <?if(count($arResult["SIGNED_CONTRACTS"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Договора которые подписал контрагент -->
<div class="d-flex flex-wrap align-items-center">
    <h5>Договоры, ожидающие подписания с моей стороны <?if(!empty($arResult["SIGNED_PARTNER"])){?>(<?=count($arResult["SIGNED_PARTNER"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые уже подписаны контрагентом и ожидают подписания с Вашей стороны.</div>
</div>
<?if(!empty($arResult["SIGNED_PARTNER"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table tablet_adaptive">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата подписания</div>
            <div class="d-md-table-cell"></div>
        </div>
        <? echo bildTable($arResult["SIGNED_PARTNER"]);?>
    </div>
    <?if(count($arResult["SIGNED_PARTNER"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У вас нет договоров, ожидающих подписания с Вашей стороны</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Подписанные договоры -->
<div class="d-flex flex-wrap align-items-center">
<h5>Договоры, подписанные с моей стороны и ожидающие подписания контрагентом <?if(!empty($arResult["SIGNED_USER"])){?>(<?=count($arResult["SIGNED_USER"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые подписаны с Вашей стороны и ожидающие подписания со стороны контрагента.</div>
</div>
<?if(!empty($arResult["SIGNED_USER"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table tablet_adaptive">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата и время подписания</div>
            <div class="d-md-table-cell"></div>
        </div>
        <? echo bildTable($arResult["SIGNED_USER"]);?>
    </div>
    <?if(count($arResult["SIGNED_USER"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>
<div style="width: 100%; height: 100px;"></div>

<!-- Измененные договора -->
<div class="d-flex flex-wrap align-items-center">
<h5>Договоры, измененые вами или же ваши договора измененные конторагентом <?if(!empty($arResult["CONTRACT_REDACTIONS"])){?>(<?=count($arResult["CONTRACT_REDACTIONS"])?>)<?}?></h5>
    <button class="info-btn">?</button>
    <div class="info-content">В данном разделе содержатся Договоры, которые были изменены Вами или Конторагентом.</div>
</div>
<?if(!empty($arResult["CONTRACT_REDACTIONS"])):?>
    <!--Адаптивная табличка--->
    <div class="d-md-table tablet_adaptive">
        <div class="d-none d-md-table-row t-head">
            <div class="d-md-table-cell">Контрагент</div>
            <div class="d-md-table-cell">Наименование</div>
            <div class="d-md-table-cell">Дата и время изменения</div>
            <div class="d-md-table-cell">Статус</div>
            <div class="d-md-table-cell"></div>
        </div>
        <? echo bildTable($arResult["CONTRACT_REDACTIONS"], "REDACTION");?>
    </div>
    <?if(count($arResult["CONTRACT_REDACTIONS"]) > 1){?>
        <div class="expand-list">
            <div class="hide-show-scroll">Показать все</div>
        </div>
    <?}?>
    <!------------------------>
<?else:?>
    <div style="clear: both"></div>
    <h3>У Вас нет подписанных договоров</h3>
<?endif?>

<script>
    $(".collapse-header").click(function () {
        $(this).toggleClass("open");
        $(this).next().toggleClass("open");
    });
    if (window.innerWidth <= 767) {
        $(".info-btn").click(function (e) {
            $(this).next().slideToggle();
            var X = e.pageX;
            var Y = e.pageY;
            var top = Y  + 10 + 'px';
            var left = X  + 10 + 'px';
            
            var width = $('body').width() - 100;

            var id = $(this).next();
            if(X  + 10 + width >= $('body').width()){
                left = X - ((X  + 10 + width) - $('body').width()) - 40;
                left = left + 'px';
            }
            id.css({
                top: top,
                left: left,
                width: width
            });
        });
        $(document).click( function(event){
            if( $(event.target).closest(".info-content").length ) return;
            if( $(event.target).closest(".info-btn").length ) return;
            $(".info-content").slideUp();
            event.stopPropagation();
        });
    }
    // $(".info-btn").hover(function () {
    //     if (window.innerWidth > 767)
    //         $(this).next().fadeToggle(50);
    // });
    if (window.innerWidth > 767){
        $('.info-btn').mousemove(function(e){
            var X = e.pageX;
            var Y = e.pageY;
            var top = Y  + 10 + 'px';
            var left = X  + 10 + 'px';
        
            var id = $(this).next();
            if(X  + 10 + id.width() >= $('body').width()){
                left = X - ((X  + 10 + id.width()) - $('body').width()) - 40;
                left = left + 'px';
            }
            id.css({
                display:"block",
                top: top,
                left: left
            });
        });
        $('.info-btn').mouseout (function(){
            var id = $(this).next();
            id.css({
                display:"none"
            });
        });
    }
</script>