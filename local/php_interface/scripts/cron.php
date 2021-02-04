<?
if ($_REQUEST['key'] === 'CGh23NSNkdc2nLiWdX2hMlhbVI-UmcEd5FlIoZeX7'){
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS",true);
	define("BX_CRONTAB", true);
	define('BX_NO_ACCELERATOR_RESET', true);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	@set_time_limit(0);
	@ignore_user_abort(true);
	CAgent::CheckAgents();
	define("BX_CRONTAB_SUPPORT", true);
	CEvent::CheckEvents();
	if(CModule::IncludeModule('sender'))
	{
		\Bitrix\Sender\MailingManager::checkPeriod(false);
		\Bitrix\Sender\MailingManager::checkSend();
	}
	if (CModule::IncludeModule("subscribe"))
	{
	      $cPosting = new CPosting;
	      $cPosting->AutoSend();
	}
}
