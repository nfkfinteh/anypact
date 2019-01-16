<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:20
 */

/** @var \EsiaCore $this */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

require_once $this->DIRRECTORY_ESIA_TPL . 'docsagree' . DIRECTORY_SEPARATOR . 'page.php';

$this->Merge( $this->Detail, 'RESULT', $arResult );