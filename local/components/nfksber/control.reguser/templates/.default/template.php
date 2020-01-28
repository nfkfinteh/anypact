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
            <td>
                <?=$i?>
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
                        echo "Верифицирован через ЕСИА";
                    } else {
                        echo "нет";
                    }
                ?>
            </td>
            <td><button class="btn btn-nfk buttonSebdPay">Выплатить вознаграждение</button></td>
        </tr>
        <? $i++; ?>
    <?}?>    
  </tbody>
</table>
