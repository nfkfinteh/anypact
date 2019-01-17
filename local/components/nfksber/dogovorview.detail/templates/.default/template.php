<? // print_r($arResult["PROPERTY"]) ;?>
<?// print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="cardDogovor-boxTool">
                <button class="btn btn-nfk">Подписать договор</button>
                <button class="btn btn-nfk">Предложить свою редакцию</button>
            </div>
        </div>               
        <div class="col-lg-8 col-md-8 col-sm-8"> 
            <div class="cardDogovor-boxViewText">
                <?=$arResult["ELEMENT"]["DETAIL_TEXT"]?>
            </div>
        </div>            
    </div>
</div>