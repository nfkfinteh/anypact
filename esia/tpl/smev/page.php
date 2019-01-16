<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 09.10.2017
 * Time: 22:23
 */

$out = '';

ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'smev' . DIRECTORY_SEPARATOR . 'form.php'; $out .= ob_get_contents(); ob_end_clean();

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';
