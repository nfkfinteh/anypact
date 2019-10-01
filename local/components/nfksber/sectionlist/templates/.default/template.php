<? //print_r($arResult);
?>
<div class="category">
        <span class="category-name">Категории:</span>
        <div class="row">
            <div class="col-lg-7 col-md-9 col-sm-12">
                <div class="row">
                  <? if($arResult['INFOBLOCK_SECTION_LIST']['PROP_ONE_ITEM'] == 'N') { 
                    foreach($arResult['INFOBLOCK_SECTION_LIST']['SECTION_LIST'] as $item_section) {?>
                        <div class="col-sm-4">
                            <a href="/pacts/?SECTION_ID=<?=$item_section['ID']?>">
                                <?=$item_section['NAME']?>
                                <span>(<?=$item_section['COUNT_IN_ITEM']?>)</span>
                            </a>                            
                        </div>  
                  <?    } 
                    }else {?>                        
                        <div class="col-sm-4">                            
                            <a href="/pacts/?SECTION_ID=<?=$arResult['INFOBLOCK_SECTION_LIST']['ARR_ONE_ITEM']['ID']?>">
                                <?=$arResult['INFOBLOCK_SECTION_LIST']['ARR_ONE_ITEM']['NAME']?>
                                <span><?=$item_section['COUNT_IN_ITEM']?></span>
                            </a>
                        </div>
                    <?}?>
                </div>
            </div>
        </div>
    </div>