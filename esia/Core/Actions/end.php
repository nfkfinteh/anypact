<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:40
 */

/** @var \EsiaCore $this */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

$arResult['end'] = 'happy';

$this->Save();

require_once $this->DIRRECTORY_ESIA_TPL . 'end' . DIRECTORY_SEPARATOR . 'page.php';

$this->Merge( $this->Detail, 'RESULT', $arResult );