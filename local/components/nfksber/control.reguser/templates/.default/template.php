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
                <?=$UserRegAction["NAME"]?> <?=$UserRegAction["LAST_NAME"]?> <?=$UserRegAction["SECOND_NAME"]?>
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
                ?>
            </td>
            <td>
                <? if($UserRegAction["UF_ESIA_AUT"] == 1 && $UserRegAction['PERSONAL_PHOTO'] !=''){ ?>
                    <button class="btn btn-nfk buttonSebdPay">Выплатить вознаграждение</button>
                <? } ?>
            </td>
        </tr>
        <? $i++; ?>
    <?}?>    
  </tbody>
</table>
<script>
    console.log('Страница регистрации пользователей');    
    
    $('.buttonSebdPay').on('click', function(){
        $.ajax({
			type: 'POST',
			url: '/response/admin/payYandex.php',
			data: data,
			async:false,
			success: function(result){
                console.log(result);
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
