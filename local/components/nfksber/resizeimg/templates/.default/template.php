<?
if($_GET['action']=='company'){
    $action = 'company';
}
else{
    $action = '';
}
?>
<h3>Загрузите и отредактруйте фото</h3>
<div class="tender cardPact">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="cardPact-box">
                <div class="cardPact-box-edit">
                    <div id="cardPact-box-edit-add_img">
                        <span>+</span>
                    </div>
                </div>
            </div>
            <input id='filePicture' type="file" accept=".txt,image/*" style="display: none">
        </div>
    </div>
</div>
<div class="cart-tab" style="display:;">
    <button class="btn btn-nfk js-rem_img">Очистить</button>
    <button class="btn btn-nfk js-submit_selection" data-action="<?=$action?>" data-id="<?=$_GET['id']?>">Обрезать и сохранить</button>
</div>
