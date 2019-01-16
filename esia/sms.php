<? session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';

EsiaLogger::DumpEnviroment( 'sms' );

require_once 'Core/modx.config.php';

$request=$modx->quote($_REQUEST['sms']);
$sql="SELECT code_in FROM konklude WHERE id=".intval($_SESSION['id_konklude'])." LIMIT 0,1";
#echo $sql;
$results = $modx->query($sql);
$r = $results->fetch(PDO::FETCH_ASSOC);


$sql="UPDATE konklude SET code_out=$request, date_out=now() WHERE id='".intval($_SESSION['id_konklude'])."'";
$modx->query($sql);
#echo var_dump($kod);
if ($request<>$r['code_in']){echo 'false';}
else{echo 'true'; 
$sql="UPDATE konklude SET correct=1 WHERE id='".intval($_SESSION['id_konklude'])."'";
$modx->query($sql);
$_SESSION['date_kod_out']=date('Y-m-d H:i:s');
$_SESSION['sms_kod_right_esia']=1;
$_SESSION['SMS_CODE_CONFIRM_1'] = $request;
}


?>