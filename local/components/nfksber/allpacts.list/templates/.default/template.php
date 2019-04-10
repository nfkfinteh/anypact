<? //print_r($arResult) ;?>
 <div class="tender">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="filter-tender">
                    <span>Ключевое слово</span>
                    <!--<input class="filter-key" type="text" placeholder="Например, продать автомобиль">-->
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:search.form",
                            "",
                        Array()
                    );?>
                    <span>Дата</span>
                    <input class="filter-date" type="text" name="min-date" placeholder="--/--/---"> -
                    <input class="filter-date" type="text" name="max-date" placeholder="--/--/---">
                    <span>Цена, руб.</span>
                    <input class="filter-price" type="text" id="minCost2" value="100000"> -
                    <input class="filter-price" type="text" id="maxCost2" value="700000">
                    <div id="slider"></div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
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
            </div>
        </div>
    </div>
    <ul class="pagination justify-content-center">
        <li class="page-item disabled"><a class="page-link" href="#">&larr;</a></li>
        <li class="page-item disabled"><a class="page-link" href="#">Назад</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item disabled"><a class="page-link" href="#">.....</a></li>
        <li class="page-item"><a class="page-link" href="#">5</a></li>
        <li class="page-item"><a class="page-link" href="#">Вперед</a></li>
        <li class="page-item"><a class="page-link" href="#">&rarr;</a></li>
    </ul>

</div>