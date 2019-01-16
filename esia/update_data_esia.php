<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';

EsiaLogger::DumpEnviroment( 'update_data_esia' );

require_once 'Core/modx.config.php';
$last_y = date('Y-m-d', mktime(0, 0, 0, date("m")  , date("d"), date("Y")-1));

$sql="SELECT * FROM persons_esia, persons WHERE persons_esia.add_date>='$last_y'";
echo $sql;
$results = $modx->query($sql);
$r = $results->fetch(PDO::FETCH_ASSOC);

?>