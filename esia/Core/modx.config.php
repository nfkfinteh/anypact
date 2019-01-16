<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 6:57
 */

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'dynamic_config.php';
XDynamicConfig::Fetch();

require_once XDynamicConfig::$CorePath . 'config/config.inc.php';
require_once XDynamicConfig::$CorePath . 'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error', 'error.modError');