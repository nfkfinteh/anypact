<?php
// авторизация
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;
if ($USER->IsAuthorized()) echo "Вы авторизованы!";

?>