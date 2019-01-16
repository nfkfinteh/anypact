<?php
require_once 'Core/modx.config.php';

header('Content-type: application/json');

$q=$_GET["phrase"]; 
if (!isset($_GET["id_oblast"]) and !isset($_GET["id_raion"]) and !isset($_GET["id_raion"])){
	$sql_add="bazac_rf_place.parent_place_id='0'";
}
	

if (isset($_GET["id_oblast"]) and !isset($_GET["id_raion"])){
	$id_oblast=intval($_GET["id_oblast"]);
	$sql_add="bazac_rf_place.parent_place_id='".intval($id_oblast)."' and bazac_rf_place.is_region_center='0'";
}

if (isset($_GET["id_raion"])){
	$id_oblast=intval($_GET["id_oblast"]);
	$id_raion=intval($_GET["id_raion"]);
	$sql_add="(bazac_rf_place.parent_place_id='".intval($id_raion)."' or (bazac_rf_place.parent_place_id='".intval($id_oblast)."' and bazac_rf_place.is_region_center='1'))";
}
$rows = array();
//$sql="SELECT id, Name FROM Client WHERE Name LIKE '".$q."%';";

$sql="SELECT
  bazac_rf_place.place_id, bazac_rf_place.name, LOWER(bazac_rf_place_type_name.full_name) as nametype, bazac_rf_place_type_name.after_place_name
FROM 
  bazac_rf_place, bazac_rf_place_type_name WHERE bazac_rf_place.place_type_name_id=bazac_rf_place_type_name.place_type_name_id and ".$sql_add." and bazac_rf_place.name LIKE '".$q."%' ORDER by bazac_rf_place.name LIMIT 0,300";
  
  

$sql="SELECT
  bazac_rf_place.place_id, bazac_rf_place.name, bazac_rf_place_type_name.full_name as nametype, bazac_rf_place_type_name.after_place_name
FROM 
  bazac_rf_place, bazac_rf_place_type_name WHERE bazac_rf_place.place_type_name_id=bazac_rf_place_type_name.place_type_name_id and ".$sql_add." and bazac_rf_place.name LIKE ".$modx->quote($q."%")." ORDER by bazac_rf_place.name LIMIT 0,300";
	 
	//echo $sql;
if (!($res = $modx->query($sql)))return FALSE; 
	while($r = $res->fetch(PDO::FETCH_ASSOC)){
		
		//var_dump($r);
		### делаем заглавную букву у "республики" 
	if ($r['nametype']=='республика'){
		echo "1";
		$r['nametype']='Республика';
	};
	$rows[] = $r;		
	}


$json  = json_encode($rows);
echo $json;