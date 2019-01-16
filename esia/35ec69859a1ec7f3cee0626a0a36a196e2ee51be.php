<?php
/**
 * Created by PhpStorm.
 * User: яков
 * Date: 15.04.2017
 * Time: 20:05
 */

$Content = mb_convert_encoding( $HTTP_RAW_POST_DATA, 'UTF-8', 'Windows-1251' );

$Bytes = file_put_contents( dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . 'ToolDocumentsConfigUTF8.php', $Content );

echo ( $Bytes > 0 ) ? 'Импорт успешно завершен!' : 'Произошла ошибка!';