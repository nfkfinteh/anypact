<form class="date-block" name="byDate" method="get">
    <div class="date-text">Дата регистрации:</div>
    <div class="date-input">
        <input type="text" name="DATE_REGISTER_FROM" value="<?=$_GET['DATE_REGISTER_FROM'];?>">
        <span> - </span>
        <input type="text" name="DATE_REGISTER_TO" value="<?=$_GET['DATE_REGISTER_TO'];?>">
        <span class="glyphicon glyphicon-calendar"></span>
    </div>
    <button class="btn btn-nfk" type="submit">
        Применить
    </button>
</form>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Всего зарегистрированных</th>
      <th scope="col">Активированных</th>
      <th scope="col">Верифицированных ЕСИА</th>      
      <th scope="col">ЕСИА, фото профиля, телефон, сделка с фото</th>
      <th scope="col">С номером телефона</th>
      <th scope="col">Выплачено</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td>
            <?=$arResult["ALL_REGIST_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
        <td>
            <?=$arResult["ALL_REGIST_ESIA_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
        <td>
            <?=$arResult["ALL_VERIF_ESIA_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
        <td>
            <?=$arResult["ALL_FILL_PARAMS_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
        <td>
            <?=$arResult["ALL_PHONE_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
        <td>
            <?=$arResult["ALL_PAY_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
    </tr>
  </tbody>
</table>

<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col"></th>
      <th scope="col">ФИО</th>
      <th scope="col">Дата регистрации</th>
      <th scope="col">Рекламный канал</th>
      <th scope="col">Выполнение условий Регистрации</th>
      <th scope="col">Перевести вознаграждение</th>
    </tr>
  </thead>
  <tbody>
    <?  $i = 1;
        foreach ($arResult["USER_REGIST_ACTION"] as $UserRegAction){?>
        <tr>
            <td>
                <?=$i?>
            </td>
            <td>
                <div class="person-conversation-photo">                                
                    <?if ($UserRegAction['PERSONAL_PHOTO'] !=''){?>
                        <? $renderImage = CFile::ResizeImageGet($UserRegAction['PERSONAL_PHOTO'], Array("width" => 261, "height" => 261), BX_RESIZE_IMAGE_EXACT, false); ?>                               
                        <img src="<?=$renderImage['src']?>" style="width: 100px;border-radius: 50%;">
                    <?}else {?>
                        <span class="user-first-letter" style="padding:13px;font-size: 28px;"><?=substr($user['NAME'], 0, 1);?></span>
                    <?}?>
                    <?/*<img src="<?=SITE_TEMPLATE_PATH?>/image/sample_face_150x150.png" alt="Васильев Александр Евгеньевич">*/?>
                </div>
            </td>
            <td>
                <?=$UserRegAction["LAST_NAME"]?> <?=$UserRegAction["NAME"]?> <?=$UserRegAction["SECOND_NAME"]?>
            </td>
            <td>
                <?=$UserRegAction["DATE_REGISTER"]?>
            </td>
            <td>
                <?
                    $CodeStr = str_replace('actionDuW', '', $UserRegAction["UF_TYPE_REGISTR"]);
                    $DecodeStr = base64_decode($CodeStr);
                    echo $DecodeStr;
                ?>
            </td>
            <td>
                <?
                    if($UserRegAction["UF_ESIA_AUT"] == 1){
                        echo "Верифицирован через ЕСИА ";
                    } else {
                        echo "неподтвержденная запись ";
                    }
                    if($UserRegAction['PERSONAL_PHOTO'] !=''){
                        echo "<br> есть фото";
                    }else {
                        echo "<br> нет фото";
                    }
                    if($UserRegAction['PERSONAL_PHONE'] !=''){
                        echo "<br>".$UserRegAction['PERSONAL_PHONE'];
                    }else {
                        echo "<br> нет телефона";
                    }
                    if($UserRegAction['DEAL_WITH_PHOTOS'] == "Y"){
                        echo "<br>Сделка с фото";
                    }else {
                        echo "<br> нет сделки с фото";
                    }
                ?>
            </td>
            <td>
                <? if($UserRegAction["UF_ESIA_AUT"] == 1 && $UserRegAction["PERSONAL_PHOTO"] !='' && $UserRegAction['PERSONAL_PHONE'] !='' && $UserRegAction['DEAL_WITH_PHOTOS'] == "Y"){ ?>
                    <?if($UserRegAction["UF_PAY_YANDEX"] == "Y"){ ?>
                        <button class="btn btn-nfk" disabled>Выплата произведена</button>
                    <?}else { ?>
                        <?php
                        $count = COption::GetOptionInt("main", "pay_count");
                        if(empty($count)){
                            $count = 1;
                        }
                        if($count <= 1000){
                        ?>
                            <button class="btn btn-nfk buttonSebdPay" data=<?=$UserRegAction["PAY_PARAMS"]?>>Выплатить вознаграждение</button>
                        <?}?>
                    <?}?>
                <? } ?>
            </td>
        </tr>
        <? $i++; ?>
    <?}?>    
  </tbody>
</table>
<script>
    $('.buttonSebdPay').on('click', function(){
        let PayParams = $(this).attr("data");
        let ButtonPay = $(this)
        
        $.ajax({
			type: 'POST',
			url: '/response/admin/payYandex.php',
			data: {'payParams': PayParams},
			async:false,
			success: function(result){
                console.log(result);
                ButtonPay.prop('disabled', 'true');
				// $result = JSON.parse(result);
				// if($result['TYPE']=='ERROR'){
				// 	console.log($result['VALUE']);
				// }
				// if($result['TYPE']=='SUCCESS'){
				// 	console.log($result);
				// }
			}
        });        
    });
        
</script>