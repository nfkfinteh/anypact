<? //print_r($arResult["PROPERTY"]) ;?>
 <div class="tender">
    <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-12">                
                <h3>Описание</h3>
                <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                <h3>Условия</h3>
                <?=$arResult["PROPERTY"][6]["VALUE"]["TEXT"]?>
            </div>
        <div class="col-lg-3 col-md-4 col-sm-12">
            800000 руб.
        </div>            
    </div>
</div>