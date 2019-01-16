<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 09.10.2017
 * Time: 22:21
 */

/** @var \EsiaCore $this */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

$arResult['IS_SMEV'] = 'Y';

$this->Merge( $this->Detail, 'RESULT', $arResult );

$this->Save();

$url = $this->URL('smev');
header("Location: $url");

