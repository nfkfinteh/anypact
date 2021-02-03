<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);

$siteId = isset($_REQUEST['SITE_ID']) && is_string($_REQUEST['SITE_ID']) ? $_REQUEST['SITE_ID'] : '';
$siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId))
{
	define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if(!empty($_REQUEST['data']) && is_array($_REQUEST['data']))
foreach($_REQUEST['data'] as $key => $value){
	if(strpos($key, "[")){
		list($key1, $key2) = explode("[", $key);
		$_REQUEST['data'][$key1][$key2] = $value;
		unset($_REQUEST['data'][$key]);
	}
}

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

Bitrix\Main\Localization\Loc::loadMessages(dirname(__FILE__).'/class.php');

$signer = new \Bitrix\Main\Security\Sign\Signer;
try
{
	$signedParamsString = $request->get('signedParamsString') ?: '';
	$params = $signer->unsign($signedParamsString, 'messenger_hl.dialog.list');
	$params = unserialize(base64_decode($params));
}
catch (\Bitrix\Main\Security\Sign\BadSignatureException $e)
{
	die();
}

$action = $request->get($params['ACTION_VARIABLE']);
if (empty($action))
	return;

global $APPLICATION;

$APPLICATION->IncludeComponent(
	'nfksber:messenger_hl.dialog.list',
	'.default',
	$params
);