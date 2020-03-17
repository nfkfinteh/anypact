<div class="row">
    <?// выборка сделок
    foreach($arResult["INFOBLOCK_LIST"] as $pact){
        ?>
        <div class="col-lg-4 col-md-6 col-sm-12 tender-block">
            <div class="tender-post">
                <a href="/pacts/view_pact/?ELEMENT_ID=<?=$pact['ID']?>">
                    <div class="tender-img">
                        <?if (!isset($pact['URL_IMG_PREVIEW'])){ ?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg" alt="">
                        <?} else {?>
                            <img src="<?=$pact['URL_IMG_PREVIEW']?>" alt="">
                        <?}?>                        
                    </div>
                </a>
                <div class="tender-text">
                    <a href="/pacts/view_pact/?ELEMENT_ID=<?=$pact['ID']?>">
                        <h3><?=substr($pact["NAME"], 0, 30)?></h3>
                        <p><?=$pact["CREATED_DATE"]?></p>
                        <span class="tender-price">до <?=$pact['PROPERTIES']['SUMM_PACT']['VALUE']?> руб.</span>
                    </a>
                </div>
            </div>
        </div>
    <?}?>
</div>
<?=$arResult["NAV_STRING"]?>
