<div class="row">
    <?// выборка сделок
    foreach($arResult["INFOBLOCK_LIST"] as $pact){
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
            <div class="tender-post">
                <a href="<?=$pact['DETAIL_PAGE_URL']?>">
                    <div class="tender-img">
                        <?if (!isset($pact['URL_IMG_PREVIEW'])){ ?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg" alt="">
                        <?} else {?>
                            <img src="<?=$pact['URL_IMG_PREVIEW']?>" alt="">
                        <?}?>                        
                    </div>
                </a>
                <div class="tender-text">
                    <a href="<?=$pact['DETAIL_PAGE_URL']?>">
                        <h3 title="<?=$pact["NAME"]?>"><?=TruncateText($pact["NAME"], 30)?></h3>
                        <p><?=$pact["CREATED_DATE"]?></p>
                        <span class="tender-price">
                            <?if($pact['PROPERTIES']['PRICE_ON_REQUEST']['VALUE_ENUM'] == "Y"){?>
                                Цена по запросу
                            <?}else{?>
                                <?=$pact['PROPERTIES']['SUMM_PACT']['VALUE']?> руб.
                            <?}?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?}?>
</div>
<?=$arResult["NAV_STRING"]?>
<div class="container-img">
    <a href="https://pioneer-leasing.ru/" target="_blank"><img src="<?=SITE_TEMPLATE_PATH?>/img/pioneer_leasing_avto.png" alt="Пионер-лизинг"></a>
</div>