<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 7:07
 */

/** @var \EsiaCore $this */

session_start();

require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

require_once $this->DIRRECTORY_ESIA_TPL . 'agree' . DIRECTORY_SEPARATOR . 'page.php';