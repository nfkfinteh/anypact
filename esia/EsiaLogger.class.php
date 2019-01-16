<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Logger.class.php';

class EsiaLogger extends Logger
{
	public static function DumpEnviroment( $Kind )
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		$Data = [
			'SERVER' => $_SERVER,
			'REQUEST' => $_REQUEST,
			'POST' => $_POST,
			'GET' => $_GET,
			'COOKIE' => $_COOKIE,
			'SESSION' => $_SESSION,
		];
		
		static::AddText( $Data, 'ESIA/Global' );
		static::AddText( $Data, 'ESIA/'.$Kind );
	}
}