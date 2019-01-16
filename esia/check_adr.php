<? session_start();

if (isset($_REQUEST['oblast'])){
$request=trim($_REQUEST['oblast']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<5){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['raion'])){
$request=trim($_REQUEST['raion']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<5){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['street'])){
$request=trim($_REQUEST['street']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<2){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['korp'])){
$request=trim($_REQUEST['korp']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<1){echo "false";}else{echo 'true';}
}else{echo 'true';}
}

if (isset($_REQUEST['flat'])){
$request=trim($_REQUEST['flat']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<1){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['oblast_post'])){
$request=trim($_REQUEST['oblast_post']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<5){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['raion_post'])){
$request=trim($_REQUEST['raion_post']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<5){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['street_post'])){
$request=trim($_REQUEST['street_post']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<2){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['korp_post'])){
$request=trim($_REQUEST['korp_post']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<1){echo "false";}else{echo 'true';}
}else{echo 'true';}
}

if (isset($_REQUEST['flat_post'])){
$request=trim($_REQUEST['flat_post']);
//$_SESSION['adr_oblast']=$request;

if (strtolower($request)!="нет"){
	if (strlen($request)<1){echo "false";}else{echo 'true';}
}else{echo 'true';}
}


if (isset($_REQUEST['birth_day'])){

$request_date = $_REQUEST['birth_day'];
$q=explode(".", $request_date);
$q_date=$q[2]."-".$q[1]."-".$q[0];
$x=checkdate($q[1],$q[0],$q[2]);
#if (($q[2]<'1920') and ($q[2]>='2010'))
if ((!$x) or ($q[2]<'1920') or ($q[2]>='1998')) echo 'false';
else echo 'true';
}

?>