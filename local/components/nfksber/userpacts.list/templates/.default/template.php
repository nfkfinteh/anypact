<? print_r($arResult["INFOBLOCK_LIST"]["HL"]) ;?>
<h2>Опубликованные сделки</h2>
<?  
  $count_pacts = count($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"]);
  if($count_pacts > 0 ){
?>
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
        foreach($arResult["INFOBLOCK_LIST"]["ARR_SDELKI"] as $pact){
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
      <?}else {?>
      <h3>У вас нет сделок</h3>
<?}?>
<div style="width: 100%; height: 100px;">
</div>
<h2>Подписанные договора</h2>
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
        foreach($arResult["INFOBLOCK_LIST"]["HL"] as $pact){
            ?>
            <tr>
                <th scope="row"><?=$pact["UF_NAME"]?></th>
                <td><??></td>                
                <!--<td><a href="/upload/private/userfiles/<?=$pact["UF_ID_GROUP"]?>/<?=$pact["UF_ID_USER_GROUP"]?>/pact/<?=$pact["ID"]?>/pact/dog_21_01_2019.pdf?" target="_blank">Посмотреть</a></td>-->
                <td><a href="/my_pacts/view_my_pact/?id=<?=$pact["ID"]?>" target="_blank">Посмотреть</a></td>
            </tr>   
            <?         
        }
    ?>    
  </tbody>
</table>