<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 18.10.2017
 * Time: 12:39
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Email.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

class SMEVCheckStatusMail
{
    const STATUS_VERIFIED = 'V';
    const STATUS_UNVERIFIED = 'U';
    const STATUS_TIMEOUT = 'T';

    public static function Send( $UserData, $Status )
    {
        $UD = '';

        foreach ( $UserData as $UserDataK => $UserDataV )
        {
            $UD = $UD . $UserDataK . ': ' . $UserDataV . '<br>' . PHP_EOL;
        }

        switch ( $Status )
        {
            case static::STATUS_VERIFIED: $S = 'Данные корректны'; break;
            case static::STATUS_UNVERIFIED: $S = 'Данные не корректны'; break;
            case static::STATUS_TIMEOUT: $S = 'Таймаут проверки'; break;
            default: $S = 'UNKNOWN';
        }

        $Vars = [
            'USER_DATA' => $UD,
            'CHECK_STATUS' => $S,
        ];

        \Strelok\Classes\Helpers\Bitrix\Email\Email::MailTemplate( 'test@nfksber.ru', 'Результат проверки smev', '/esia/mail/smevcheckstatus.html', $Vars );
    }
}