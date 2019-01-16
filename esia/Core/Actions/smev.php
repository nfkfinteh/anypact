<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 09.10.2017
 * Time: 22:42
 */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

require_once $this->DIRRECTORY_ESIA_TPL . 'smev' . DIRECTORY_SEPARATOR . 'page.php';