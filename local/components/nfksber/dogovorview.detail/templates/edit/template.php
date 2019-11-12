<? // print_r($arResult["TEMPLATE_CONTENT_PROPERTY"]) ;?>
<? //print_r($arResult) ;
   $Name = $arResult["USER_PROP"]["NAME"];
   $Surname = $arResult["USER_PROP"]["LAST_NAME"];
   $Midlenmae = $arResult["USER_PROP"]["SECOND_NAME"];
   $Phone = $arResult["USER_PROP"]["PERSONAL_PHONE"];
   $Passport = $arResult["USER_PROP"]["UF_PASSPORT"].' '.$arResult["USER_PROP"]["UF_KEM_VPASSPORT"];

?>
<style>
/*----------Стили кнопок-------------*/
#canvas .edit-buttons-container{
	display: none;
}
#canvas[contenteditable="true"] .edit-buttons-container{
	display: block;
}
.add-row, .delete-row{
	color: #ff6416 !important;
	background-color: #ffffff;
	border-color: #ff6416 !important;
	border-radius: .25rem;
	cursor: pointer;
	width: 20px;
	height: 20px;
	margin-top: 3px;
	position: relative;
	opacity: .3;
	transition: opacity .3s;
}
.add-row:hover, .delete-row:hover{
	opacity: 1;
}
.add-row::before, .delete-row::before{
	content: "";
	width: 10px;
	height: 2px;
	background-color: #ff6416;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
.add-row::after{
	content: "";
	width: 2px;
	height: 10px;
	background-color: #ff6416;
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
</style>
/*----------Стили кнопок-------------*/
<script type="text/javascript">
	function addRow(thisBtn, n){
		let tbody = thisBtn.parentElement.previousElementSibling.tBodies[0];
		let tr = document.createElement('tr');
		const num = tbody.rows.length + 1;
		const numTextNode = document.createTextNode(num);
		const td = document.createElement('td');
		td.append(numTextNode);
		tr.append(td);
		for (var i = 1; i < n; i++) {
			const td = document.createElement('td');
			tr.append(td);
		}
		tbody.append(tr);
	}
	function deleteRow(thisBtn){
		let collection = thisBtn.parentElement.previousElementSibling.tBodies[0].rows;
		console.log(collection);
		collection[collection.length-1].remove();
	}
</script>
<script>
var full_name = {
    name: '<?=$Name?>',
    surname: '<?=$Surname?>',
    midlname:'<?=$Midlenmae?>',
    phone: '<?=$Phone?>',
    passport: '<?=$Passport?>'
}
</script>
<h1><?=$arResult["ELEMENT"]["NAME"]?></h1>
 <div class="tender cardDogovor" style="margin-bottom: 100px;">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="cardDogovor-boxTool cardPact">
                <h5>Вставить в договор:</h5>
            </div>
        </div>               
        <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="tools_redactor">
        <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить" id="save_btn" data-id="<?=$arResult['ELEMENT_ID']?>">
            <span class="glyphicon glyphicon-floppy-disk"></span>
        </button>
                <!--<button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Сохранить"><span class="glyphicon glyphicon-print"></span></button>-->
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-edit" data-toggle="tooltip" data-placement="left" title="Включить редактирование текста" contenteditable="false"><span class="glyphicon glyphicon-pencil"></span></button>                
                <button type="button" class="btn btn-nfk btn-default" data-toggle="tooltip" data-placement="left" title="Вставить изображение"><span class="glyphicon glyphicon-picture"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text"  id="btn-noedit" data-toggle="tooltip" data-placement="left" title="Запретить редактирование выделенного текста"><span class="glyphicon glyphicon-ban-circle"></span></button>
                <button type="button" class="btn btn-nfk btn-default space_right" id="btn-data" data-toggle="tooltip" data-placement="left" title="Вставить подстановку текущей даты"><span class="glyphicon glyphicon-calendar"></span></button>                
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-weight" data-toggle="tooltip" data-placement="left" title="Жирный текст" contenteditable="false"><span class="glyphicon glyphicon-bold"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text space_right" id="btn-italic" data-toggle="tooltip" data-placement="left" title="Курсив" contenteditable="false"><span class="glyphicon glyphicon-italic"></span></button>
                <button type="button" class="btn btn-nfk btn-default form_text" id="btn-question" data-toggle="tooltip" data-placement="left" title="Информация по инструментам" contenteditable="false"><span class="glyphicon glyphicon-question-sign"></span></button>
        </div>
            <?if(!empty($arResult["DOGOVOR_IMG"][0]['URL'])):?>
                <div class="cardDogovor-boxViewText">
                    <?foreach ($arResult["DOGOVOR_IMG"] as $item):?>
                        <div class="document-img" style="text-align: center">
                            <img src="<?=$item['URL']?>">
                        </div>
                        <br>
                    <?endforeach?>
                </div>
            <?else:?>
                <div class="cardDogovor-boxViewText" id="canvas" contenteditable="false">
                    <? echo $arResult["TEMPLATE_CONTENT"]["DETAIL_TEXT"] ;?>
                </div>
            <?endif?>
        </div>            
    </div>
</div>