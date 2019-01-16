<? //print_r($arResult) ;?>
<h2>Опубликованные сделки</h2>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Наименование</th>
      <th scope="col">Дата</th>
      <th scope="col">Статус</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <?// выборка договоров
        foreach($arResult["INFOBLOCK_LIST"] as $pact){
            ?>
            <tr>
                <th scope="row"><?=$pact["NAME"]?></th>
                <td><?=$pact["CREATED_DATE"]?></td>
                <td><?=$pact["PROPERTIES"]["PACT_STATUS"]["VALUE_XML_ID"]?></td>
                <td><a href="/my_pacts/?ELEMENT_ID=<?=$pact['ID']?>">Посмотреть</a></td>
            </tr>   
            <?         
        }
    ?>    
  </tbody>
</table>
