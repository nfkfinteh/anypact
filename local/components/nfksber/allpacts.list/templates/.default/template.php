<div class="row">
<?// выборка договоров
foreach($arResult["INFOBLOCK_LIST"] as $pact){
    ?>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="tender-post">
            <a href="/pacts/view_pact/?ELEMENT_ID=<?=$pact['ID']?>">
                <div class="tender-img">
                  <?if (!isset($pact['URL_IMG_PREVIEW'])){ ?>
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/no_img_pacts.jpg" alt="">
                  <?} else {?>
                    <img src="<?=$pact['URL_IMG_PREVIEW']?>" alt="">
                  <?}?>
                    <span><?=$pact["CREATED_DATE"]?></span>
                </div>
            </a>
            <div class="tender-text">
                <a href="/pacts/view_pact/?ELEMENT_ID=<?=$pact['ID']?>">
                    <h3><?=$pact["NAME"]?></h3>
                    <p><?=$pact['PREVIEW_TEXT']?></p>
                    <span class="tender-price">до <?=$pact['PROPERTIES']['SUMM_PACT']['VALUE']?> руб.</span>
                </a>
            </div>
        </div>
    </div>
    <?
        }
    ?>
</div>
<?=$arResult["NAV_STRING"]?>
