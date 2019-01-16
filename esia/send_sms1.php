<? session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';

EsiaLogger::DumpEnviroment( 'send_sms1' );

if (!isset($_SESSION['sms_kod_right_esia1'])){
	$perm=rand(1000, 9999);
	if (substr($_SESSION['mobile'], 0, 1)=='7'){$pref='7'; $mobile_send="+".$_SESSION['mobile']; }else{$mobile_send = substr($_SESSION['mobile'], 1); $mobile="+7".$mobile_send;}
   
    $_SESSION['sms_kod1']=$perm;
    $sms_text="Kod dlya podtverzhdenia ".$perm.". Konfidencialno.";
	#echo $mobile;
	if (isset($_SESSION['id_person'])){
		echo send("gate.prostor-sms.ru", 80, "t89278485872", "897054", $mobile_send, $sms_text, "nfksber", "nfksber.ru");
		//$url="http://api.prostor-sms.ru/messages/v2/send/?login=t89278485872&password=897054&sender=nfksber&phone=".$mobile_send."&text=".$sms_text;
		//get_sms($url);
		$_SESSION['date_kod_in1']=date('Y-m-d H:i:s');
		$sql="INSERT INTO konklude SET id_client='$_SESSION[id_person]', phone='$mobile_send', date_in='$_SESSION[date_kod_in1]', code_in='$_SESSION[sms_kod1]', comment='Подписание'";

        require_once 'Core/modx.config.php';

		$modx->query($sql);
	    $_SESSION['id_konklude1']=$modx->lastInsertId();

	}
}


function send($host, $port, $login, $password, $phone, $text, $sender = false, $wapurl = false ){
$fp = fsockopen($host, $port, $errno, $errstr);
if (!$fp) {
return "errno: $errno \nerrstr: $errstr\n";
}
fwrite($fp, "GET /send/" .
"?phone=" . rawurlencode($phone) .
"&text=" . rawurlencode($text) .
($sender ? "&sender=" . rawurlencode($sender) : "") .
($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") .
" HTTP/1.0\n");
fwrite($fp, "Host: " . $host . "\r\n");
if ($login != "") {
fwrite($fp, "Authorization: Basic " .
base64_encode($login. ":" . $password) . "\n");
}
fwrite($fp, "\n");
$response = "";
while(!feof($fp)) {
$response .= fread($fp, 1);
}
fclose($fp);
list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
return $responseBody;
}

?>