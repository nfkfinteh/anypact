<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 7:13
 */

/** @var \EsiaCore $this */

$this->Merge( $this->Detail, 'RESULT', $arResult );

$url_html_templates= $this->DIRRECTORY_ESIA_TPL . 'base.html' ;

$templates=file_get_contents($url_html_templates);

//echo '<h1>Current</h1>' . PHP_EOL;
//var_dump($this->Current);
//
//echo '<h1>arResult</h1>' . PHP_EOL;
//var_dump($arResult);

$search = array ("<p>content_esia</p>");
$replace = array ($out);
$text_page = str_replace ($search, $replace, $templates);

echo $text_page;