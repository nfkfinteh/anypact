<? //print_r($arResult["LIST_CATEGORY"]) ;?>
<?// print_r($arResult) ;?>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="cardDogovor-boxTool">
            <div class="select-dogovotTemplate">
                <label for="select-dogovotTemplate" class="col-form-label">Выбор шаблона договора</label>                
                <select class="form-control" id="select-dogovotTemplate">
                    <? foreach($arResult["LIST_CATEGORY"] as $items_category) { ?>
                    <option value="<?=$items_category["ID"]?>"><?=$items_category["NAME"]?></option>
                    <? } ?>
                </select>
            </div>
            <div class="select-dogovotTemplate">
                <label for="select-dogovotTemplate" class="col-form-label">Выбор подкатегории договора</label>
                <select class="form-control" id="select-dogovotTemplate">                    
                    <option value="">Договор Купли-Продажи Автомобиля</option>
                    <option value="">Договор Купли-Продажи Нежилого помещения</option>
                    <option value="">Договор Купли-Продажи Земельного участка</option>
                    <option value="">Договор Купли-Продажи Гаража</option>
                </select>
            </div>
                <button class="btn btn-nfk">Подписать договор</button>
                <button class="btn btn-nfk">Предложить свою редакцию</button>
            </div>
        </div>               
        <div class="col-lg-8 col-md-8 col-sm-8"> 
            <div class="cardDogovor-boxViewText">
                <div class="dogovor-title">
                    <h4>Договор</h4>
                <div>
            </div>
        </div>            
    </div>
</div>