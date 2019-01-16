<?php

/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 02.03.2017
 * Time: 16:06
 */

namespace Strelok\Classes\Helpers\Bitrix\Email;

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Logger.class.php';

//use PHPMailer\PHPMailer\PHPMailer;
use
    \Strelok\Classes\Debug\Logs\Logger,
    \Strelok\Classes\Helpers\ArrayHelper;

if ( !function_exists('IsTestEnvironment') )
{
    function IsTestEnvironment()
    {
        return false;
    }
}

class Email
{
    public static $Debug = 0;

    public static $LogData = null;

    public static $TotalDisable = false;

    public static function MailTemplate( $Email, $Subject, $Template, $Variables )
    {
        if ( static::$TotalDisable === true )
        {
            return true;
        }

        //

        \Logger::AddText( [ 'MailTemplate' => func_get_args() ], 'Email' );

        $MessageFileName = $_SERVER['DOCUMENT_ROOT'] . $Template;

        if ( !file_exists( $MessageFileName ) )
        {
            \Logger::AddText('MessageFileName not found', 'Email' );

            return false;
        }

        $Message = file_get_contents( $MessageFileName );

        foreach ( $Variables as $VarsN => $VarsV )
        {
            $Message = str_ireplace( '%' . $VarsN . '%', $VarsV, $Message );
        }

        $Result = static::Mail( $Email, $Subject, $Message );

        return $Result;
    }
	
	public static function Mail( $Email, $Subject, $Message, $Headers = null )
	{
        if ( static::$TotalDisable === true )
        {
            return true;
        }

        //

        \Logger::AddText( [ 'Mail' => func_get_args() ], 'Email' );

	    $LogData = [];

        $Result = false;
		
		require_once $_SERVER["DOCUMENT_ROOT"] . '/esia/PHPMailer-master/PHPMailerAutoload.php';

            $mail = new \PHPMailer( true );

            try
            {
                if ( IsTestEnvironment() )
                {
                    $mail->isSMTP();

                    $mail->SMTPSecure = 'tls';

                    $mail->Host       = 'post.nfksber.ru';
                    $mail->Port       = 587;
                    $mail->SMTPDebug  = static::$Debug;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'openaccount@nfksber.ru';
                    $mail->Password   = 'Ab6xuxfu27';
                }
                else
                {
                    $mail->isSMTP();

                    $mail->SMTPSecure = 'tls';

                    $mail->Host       = 'post.nfksber.ru';
                    $mail->Port       = 587;
                    $mail->SMTPDebug  = static::$Debug;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'openaccount@nfksber.ru';
                    $mail->Password   = 'Ab6xuxfu27';
                }

                $mail->FromName = 'АО «НФК-Сбережения»';
                $mail->From = 'openaccount@nfksber.ru';

                $mail->CharSet = 'utf-8';
                $mail->Encoding = 'base64';
				
				if ( is_array( $Email ) )
				{
					foreach ($Email as $item)
                    {
                        $mail->addAddress($item, '');
                    }
				}
				else
				{
					$mail->addAddress($Email, '');
				}               

                $mail->addBCC('mail@nfksber.ru', '');
                $mail->addBCC('sales@nfksber.ru', '');
                $mail->addBCC('strelok-2007@mail.ru', 'Яков Кравцов');

                $mail->Subject = $Subject;

                $mail->msgHTML( $Message );                

                $LogData['OBJECT'] = $mail;

                ob_start();

                $Send = $mail->send();

                $SendDebug = ob_get_contents();

                ob_end_clean();

                $LogData['SEND'] = $Send;

                $LogData['SEND_DEBUG'] = $SendDebug;

                if ($Send != false )
                {
                    $Result = true;
                }
            }
            catch (\phpmailerException $e)
            {
                $LogData['phpmailerException'] = $e->errorMessage(); //Pretty error messages from PHPMailer
            }
            catch (\Exception $e)
            {
                $LogData['Exception'] = $e->getMessage(); //Boring error messages from anything else!
            }

        \Logger::AddText( [ 'Mail.LogData' => $LogData ], 'Email' );

        return $Result;
	}

    public static function Send($ID, $arFields)
    {
        if ( static::$TotalDisable === true )
        {
            return true;
        }

        //

        $Result = false;

        $LogData = [];
        $LogData['ID'] = $ID;
        $LogData['FIELDS'] = $arFields;

        $rsEM = \CEventMessage::GetByID($ID);

        $arEM = $rsEM->Fetch();

        $LogData['arEM']['BEFORE'] = $arEM;

        if (!$arEM)
        {
            $LogData['ERROR'] = 'Message template not found';
        }
        else
        {
            foreach ($arFields as $k => $v)
            {
                $arEM["MESSAGE"] = str_replace('#' . $k . '#', $v, $arEM["MESSAGE"]);
            }

            if (isset($arFields['SUBJECT']))
            {
                $arEM["SUBJECT"] = $arFields['SUBJECT'];
            }

            foreach ($arFields as $k => $v)
            {
                $arEM["SUBJECT"] = str_replace('#' . $k . '#', $v, $arEM["SUBJECT"]);
            }

            /* УДАЛЕНИЕ ПАРАМЕТРОВ #PARAM# из текста, которых нет в данных*/
            preg_match('/#[a-zA-Z_]*?#/', $arEM["MESSAGE"], $matches);
            foreach ($matches as $match)
            {
                $arEM["MESSAGE"] = str_replace($match, '', $arEM["MESSAGE"]);
            }
            /*------------------------------------------------------------*/

            $LogData['arEM']['AFTER'] = $arEM;

            require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/Library/PHPMailer-master/PHPMailerAutoload.php';
            //require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/Library/PHPMailer-6.0/src/PHPMailer.php';
            //require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/Library/PHPMailer-6.0/src/Exception.php';

            $mail = new \PHPMailer( true );

            try
            {
                if ( IsTestEnvironment() )
                {
                    $mail->isSMTP();

                    $mail->Host       = 'mx25.valuehost.ru';
                    $mail->Port       = 2525;
                    $mail->SMTPDebug  = static::$Debug;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'lk@nfksber.ru';
                    $mail->Password   = 'Lfvr_3712';
                }
                else
                {
                    $mail->isSMTP();

                    $mail->Host       = 'mx25.valuehost.ru';
                    $mail->Port       = 2525;
                    $mail->SMTPDebug  = static::$Debug;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'lk@nfksber.ru';
                    $mail->Password   = 'Lfvr_3712';
                }

                $mail->FromName = 'ООО «НФК-Сбережения»';
                $mail->From = 'lk@nfksber.ru';

                $mail->CharSet = 'utf-8';
                $mail->Encoding = 'base64';
                $e = $arFields['EMAIL'];

                if(isset($arFields['SPEC_EMAIL']) && $arFields['SPEC_EMAIL'] == 'Y')
                {
                    foreach ($e as $item)
                    {
                        $mail->addAddress($item, '');
                    }
                }
                else
                {
                    $mail->addAddress($e, '');
                }

                $mail->addBCC('strelok-2007@mail.ru', 'Яков Кравцов');

                $mail->Subject = $arEM["SUBJECT"];

                $mail->msgHTML($arEM["MESSAGE"]);

                foreach ($arFields['FILES'] as $f)
                {
                    $FileLog = '';

                    if (file_exists($f))
                    {
                        if (array_key_exists($f, $arFields['FILES_NAMES']))
                        {
                            $mail->addAttachment($f, $arFields['FILES_NAMES'][$f]);

                            $FileLog = 'Added. With name: `' . $arFields['FILES_NAMES'][$f] . '`';
                        }
                        else
                        {
                            $mail->addAttachment($f);

                            $FileLog = 'Added. Without name.';
                        }
                    }
                    else
                    {
                        $FileLog = 'File not exists';
                    }

                    $LogData['FILES'][] = [
                        'PATH' => $f,
                        'LOG' => $FileLog,
                    ];
                }

                foreach ( $arFields['FILES_BASE64'] as $b64FileIndex => $b64File )
                {
                    $b64FileContent = ArrayHelper::Value($b64File, 'CONTENT');

                    $FileLog = 'File content null';

                    if ( !is_null($b64FileContent) )
                    {
                        $b64FileName = ArrayHelper::Value($b64File, 'NAME', '');
                        $b64FileName = trim($b64FileName);

                        $b64FileEncoding = 'base64';

                        $b64FileMimeType = ArrayHelper::Value($b64File, 'MIME', '');

                        if ( $b64FileName != '' )
                        {
                            $mail->addStringAttachment( $b64FileContent, $b64FileName, $b64FileEncoding, $b64FileMimeType );

                            $FileLog = 'Added. With name: `' . $b64FileName . '`';
                        }
                        else
                        {
                            $mail->addStringAttachment( $b64FileContent, 'Attachment', $b64FileEncoding, $b64FileMimeType );

                            $FileLog = 'Added. Without name.';
                        }
                    }

                    $LogData['FILES_BASE64'][] = [
                        'PATH' => $b64FileIndex,
                        'LOG' => $FileLog,
                    ];
                }

                $LogData['OBJECT'] = $mail;

                ob_start();

                $Send = $mail->send();

                $SendDebug = ob_get_contents();

                ob_end_clean();

                $LogData['SEND'] = $Send;

                $LogData['SEND_DEBUG'] = $SendDebug;

                if ($Send != false )
                {
                    $Result = true;
                }
            }
            catch (\phpmailerException $e)
            {
                $LogData['phpmailerException'] = $e->errorMessage(); //Pretty error messages from PHPMailer
            }
            catch (\Exception $e)
            {
                $LogData['Exception'] = $e->getMessage(); //Boring error messages from anything else!
            }
        }

        foreach ($arFields['FILES'] as $f)
        {
            $DeleteCurrentFile = true;

            if (in_array($f, $arFields['FILES_NOT_DELETE']))
            {
                $DeleteCurrentFile = false;
            }

            if ( $DeleteCurrentFile == true )
            {
                @unlink($f);
            }

            $LogData['FILES_DELETE'] = [
                'PATH' => $f,
                'STATUS' => ( $DeleteCurrentFile == true ) ? 'The file was deleted' : 'The file was left',
            ];
        }

        if ( $Result == false )
        {
            Logger::AddText('Ошибка отправки сообщения. Подробности в общем логе.', 'PHPMailer_IZHZ_Fail');
        }

        Logger::AddText($LogData, 'PHPMailer_IZHZ_Log');

        static::$LogData = $LogData;

        return $Result;
    }
}