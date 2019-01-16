<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:38
 */

/** @var \EsiaCore $this */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

$request=intval($_REQUEST['sms']);
$sql="SELECT code_in FROM konklude WHERE id=".intval($arResult['id_konklude1'])." LIMIT 0,1";
#echo $sql;
$results = $modx->query($sql);
$r = $results->fetch(PDO::FETCH_ASSOC);

$sql="UPDATE konklude SET code_out=$request, date_out=now() WHERE id='".intval($arResult['id_konklude1'])."'";
$modx->query($sql);

#echo var_dump($kod);
if ($request != intval($r['code_in']))
{
    echo 'false';

    $arResult['date_kod_out1']=date('Y-m-d H:i:s');
}
else
{
    echo 'true';
    $sql="UPDATE konklude SET correct=1 WHERE id='".intval($arResult['id_konklude1'])."'";
    $modx->query($sql);
    $arResult['date_kod_out1']=date('Y-m-d H:i:s');
    $arResult['sms_kod_right_esia1']=1;
    $arResult['SMS_CODE_CONFIRM_2'] = $request;
}

$this->Merge( $this->Detail, 'RESULT', $arResult );

$this->Save();