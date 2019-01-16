<? //print_r($arResult['INFOBLOCK_LIST'][0]['PROPERTIES']["STATUS_TRADE"]) ;?>
<?//print_r($arResult["INFOBLOCK_ID"])?>
<? //print_r($arParams) ;?>
<?
  $arr_status = array(
    0 => 'Намерение',
    2 => 'Заключена',
    3 => 'Исполнена',
  );
?>
<h2>Заключенные договора</h2>
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
                <td><?=$pact["PROPERTIES"]["STATUS_TRADE"]["status_name"]?></td>
                <td><a href="#">Посмотреть</a></td>
            </tr>   
            <?         
        }
    ?>    
  </tbody>
</table>
