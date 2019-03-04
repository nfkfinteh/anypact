<?  //print_r($arResult["TEXT_CONTRACT"]) ;?>
<? //print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
        <h4>Редактировать договор</h4>
            <div class="cardDogovor-boxTool">
                <button class="btn btn-nfk">Сохранить изменения</button>                
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12"> 
            <div class="cardDogovor-boxViewText">
                <?=$arResult["TEXT_CONTRACT"]?>
            </div>
        </div>            
    </div>
</div>