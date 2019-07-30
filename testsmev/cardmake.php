<?php

 define("URLFILE", "");
 
	function sendmakeDeposition($agentId, $certFile, $keyFile, $pwdCert, $requestDT, $clientOrderId, $dstAccount, $amount, $currency)
{
	$data = '<?xml version="1.0" encoding="UTF-8"?>
		<makeDepositionRequest 
			agentId="'.$agentId.'"
			clientOrderId="272517"
			requestDT="'.$requestDT.'"
			dstAccount="79373977483"
			amount="100.00"
			currency="10643"
			contract="">
		<paymentParams>
			<pof_offerAccepted>1</pof_offerAccepted>
		</paymentParams>
	</makeDepositionRequest>';
	echo "<br>".$certFile;
	if(isset($data)){
	echo "\nmakeDeposition data:\n";
	print_r($data);
	echo "\n\n";
	};
	
	if (($data = encrypt($data, $certFile, $keyFile)) === false){
	return false;
	
	};
	echo 'ENCDATA: '.$data;
	$ch = curl_init();
	// https://bo-demo02.yamoney.ru:9094/
	//curl_setopt($ch, CURLOPT_URL, 'https://bo-demo02.yamoney.ru:9094/');
	curl_setopt($ch, CURLOPT_URL, 'https://bo-demo02.yamoney.ru:9094/webservice/deposition/api/makeDeposition');
	
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pkcs7-mime'));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Ymoney CollectMoney');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
	curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
	curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $pwdCert);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
	$response = curl_exec($ch);
	
	if(!$response)
    {
        $error = curl_error($ch).'('.curl_errno($ch).')';
        echo $error;
    }
    //если не ошибка, то выводим результат
    else
    {
//       return $response;
	echo $response;
    }
	
	curl_close($ch);
	return $response;
	die($response);
}

	function encrypt($data, $certFile, $keyFile)
{
	try {
		$pipes = array();
		$process = proc_open(
		'openssl smime -sign -signer ' . $certFile . ' -inkey ' . $keyFile . ' -nochain -nocerts -outform PEM -nodetach',
		array(array("pipe", "r"), array("pipe", "w"), array("pipe", "w")),
		$pipes
		);
		
		if (is_resource($process)) {
			fwrite($pipes[0], $data);
			fclose($pipes[0]);
			$pkcs7 = stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			proc_close($process);
			return $pkcs7;
		}
		return false;
        } catch (Exception $e) {
		return false;
	}
}

	function verify($encResult){
	$descriptorspec = array(0 => array("pipe", "r"),1 => array("pipe", "w"),2 => array("pipe", "w"));
	$certificate = URLFILE.'deposit.cer';
	$process = proc_open('openssl smime -verify -inform PEM -nointern -certfile ' . $certificate . ' -CAfile ' . $certificate, $descriptorspec, $pipes);	
	//$process = proc_open('openssl smime -verify -in PEM -noverify -signer '.$certificat.' -out textdata '.$descriptorspec, $pipes);
	if (is_resource($process)) {
	$data = $encResult; //Используется альтернатива массиву $_POST, поскольку XML-документ не получится получить с помощью данного массива
	fwrite($pipes[0], $data);
	fclose($pipes[0]);
	$content = stream_get_contents($pipes[1]);
	fclose($pipes[1]);
	$resCode = proc_close($process);
	if ($resCode != 0)
	return false;
	else
	return $content;
}
} 
 
 //
 //////////////////////////////////////////////////////////////////////////////////////////////
 // Задаем данные
 $agentId						= 202556;  //$configs['agentId'];
 $certFile 						= URLFILE.'certnew.cer'; //присланный сертификат
 $keyFile 						= URLFILE.'private.key'; //сгенерированный ключ при оформлении заявки
 $pwdCert						= URLFILE.'deposit.cer'; //присланный сертификат
 $requestDT						= date('Y-m-d\TH:i:sP');
 $clientOrderId					= 'nfk2345';
 $dstAccount					= '25700130535186';
 $amount						= '10';
 $currency						= '10643';

$post=sendmakeDeposition($agentId, $certFile, $keyFile, $pwdCert, $requestDT, $clientOrderId, $dstAccount, $amount, $currency);

echo '<br>DATA FROM DECRYPT: ' . $post;
if (!empty($post)){
$xml=verify($post);
};
echo 'RESPONSE: '.$xml;
//if (!empty($xml)){
//$sm=simplexml_load_string($xml);
//echo $sm;
//};
 
?>
