<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 3:57
 */

/** @var \EsiaCore $this */

include $this->DIRRECTORY_ESIA . "Esia.php";
include $this->DIRRECTORY_ESIA . "EsiaOmniAuth_t.php";

$config = [
    'site' => 'https://esia.gosuslugi.ru/',
    'redirect_uri' => HTTPHelper::GenerateUniversalBaseURL() . $this->URL('esia'),
    'pkey_path' => $this->DIRRECTORY_ESIA_SERT . 'secret_NFKS01211.key',
    'cert_path' => $this->DIRRECTORY_ESIA_SERT . 'cert_NFKS01211.crt',
    'client_id' => 'NFKS01211',
    'scope' => 'openid fullname id_doc',
];

$esia = new EsiaOmniAuth($config);