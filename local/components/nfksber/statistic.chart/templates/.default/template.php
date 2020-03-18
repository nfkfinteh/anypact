<table class="table">
  <thead>
    <tr>
      <th scope="col">Всего зарегистрированных</th>
      <th scope="col">Из них активированных</th>
      <th scope="col">Из них Верифицированных ЕСИА</th>      
      <th scope="col">Из них Верифицированных ЕСИА или с фото или с номером телефона</th>
      <th scope="col">Из них Выплачено</th>
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
            <?=$arResult["ALL_PAY_USERS"]["COUNT_ARR_ALL_USERS"]?>
        </td>
    </tr>
  </tbody>
</table>


<h2>Статистика по каналам привлечения</h2>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Канал</th>
      <th scope="col">Количество</th>
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
    </tr>
  </tbody>
</table>