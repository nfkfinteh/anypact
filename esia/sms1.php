<? session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';

EsiaLogger::DumpEnviroment( 'sms1' );

require_once 'Core/modx.config.php';

$request=trim($_REQUEST['sms1']);
$sql="SELECT code_in FROM konklude WHERE id=".$_SESSION['id_konklude1']." LIMIT 0,1";
#echo $sql;
$results = $modx->query($sql);
$r = $results->fetch(PDO::FETCH_ASSOC);


$sql="UPDATE konklude SET code_out='$request', date_out=now() WHERE id='$_SESSION[id_konklude1]'";
$modx->query($sql);
#echo var_dump($kod);
if ($request<>$r['code_in']){echo 'false';}
else{echo 'true'; 
$sql="UPDATE konklude SET correct=1 WHERE id='$_SESSION[id_konklude1]'";
$modx->query($sql);
$_SESSION['date_kod_out1']=date('Y-m-d H:i:s');
$_SESSION['sms_kod_right_esia1']=1;
$_SESSION['SMS_CODE_CONFIRM_2'] = $request;
}


?>