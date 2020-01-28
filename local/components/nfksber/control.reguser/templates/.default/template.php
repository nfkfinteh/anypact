<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
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
            <td scope="row"><?=$i?></td>
            <td><?=$UserRegAction["NAME"]?> <?=$UserRegAction["LAST_NAME"]?> <?=$UserRegAction["SECOND_NAME"]?></td>
            <td><?=$UserRegAction["DATE_REGISTER"]?></td>
            <td><?=$UserRegAction["UF_TYPE_REGISTR"]?></td>
            <td>
                <?
                    if($UserRegAction["UF_ESIA_ID"] == 1){
                        echo "Верифицирован через ЕСИА";
                    } 
                ?>
            </td>
            <td><button class="btn btn-nfk" id="button_user_pay">Выплатить вознаграждение</button></td>
        </tr>
        <? $i++; ?>
    <?}?>    
  </tbody>
</table>
