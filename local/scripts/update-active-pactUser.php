<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit(0);

CModule::IncludeModule("iblock");
CModule::IncludeModule("main");

global $USER;
//Проверка на админа
if (!$USER->IsAdmin()) { CHTTP::SetStatus("404 Not Found"); @define("ERROR_404","Y"); die; }


$arSelect = Array("DATE_ACTIVE_TO", "ID", "IBLOCK_ID");
$arFilter = Array(
	"IBLOCK_ID" => 3,
	"ACTIVE" => "Y",
	array("><DATE_ACTIVE_TO" => array('01.09.2020', '25.01.2021'))
);
$dbEl = CIBlockElement::GetList(Array('DATE_ACTIVE_TO' => 'ASC'), $arFilter, false, false, $arSelect);
while ($arEl = $dbEl->fetch()) {
	cdump($arEl);
	$el = new CIBlockElement;
	$arElArray = Array(
		"DATE_ACTIVE_TO" => '15.01.2022',
	);
	$res = $el->Update($arEl['ID'], $arElArray);
}
echo ' Готово!';
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");