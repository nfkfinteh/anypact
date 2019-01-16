<? //print_r($arResult["PROPERTY"]) ;?>
<? print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender">
    <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-8">                
                <h3>Описание</h3>
                <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
                <h3>Условия</h3>
                <?=$arResult["PROPERTY"][6]["VALUE"]["TEXT"]?>
            </div>
        <div class="col-lg-3 col-md-4 col-sm-4">
            <h1 style="color:#ff6416;"><?=$arResult["PROPERTY"][5]["VALUE"]?> руб.</h1>
        </div>            
    </div>
</div>