<pre>
    <?
        print_r($arResult["USER_REGIST_ACTION"]);
    ?>
</pre>
<h1>Панель управления пользователями зарегистрированными по акции</h1>
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
            <td><?=$UserRegAction["LAST_NAME"]?></td>
            <td></td>
            <td><a class="btn btn-primary" href="#" role="button">Выплатить вознаграждение</a></td>
        </tr>
        <? $i++; ?>
    <?}?>    
  </tbody>
</table>
