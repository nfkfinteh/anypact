<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 10.10.2017
 * Time: 11:01
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/Logger.class.php'; 
 
class SMEVHelperRequest
{
    /** @var string $Type */
    public $Type;

    /** @var array $Request */
    public $Request = [];

    public function Update()
    {

    }
}

class SMEVHelperResponseFault
{
    /** @var string $Code */
    public $Code;

    /** @var string $Message */
    public $Message;
}

class SMEVHelperResponse
{
    /** @var bool $Success */
    public $Success;

    /** @var array $Response */
    public $Response = [];

    /** @var SMEVHelperResponseFault $Error */
    public $Error;
}

class SMEVHelper
{
    //public static $APIHost = 'http://uprid.d.rnds.pro/';
    public static $APIHost = 'https://nfks.p.rnds.pro/';

    public static $APIUser = 'demo';

    //public static $APIPass = '123456';
    public static $APIPass = 'OMHNLv4!52';

    public static $APIKey = 'a9316be2819731ef7f3a4831b361d1a54177dfef293b4c09ca23368077b1d0eb00e06df7f60c0f53c0f47cb23d4b1a127db163f306fd212aa8afa571e0e41a';
    //public static $APIKey = '123456';

    public static $CURL;

    public static $Last = [];

    public static $LastURL = null;
    public static $LastDATA = null;
    public static $LastRESPONSE = null;

    public static $Proxy = null;

    public static function Post( $URL, $Data )
    {
        static::$LastURL = $URL;
        static::$LastDATA = $Data;

        static::$CURL = curl_init($URL);

        curl_setopt(static::$CURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $AuthString = implode( ':', [ static::$APIUser, static::$APIPass ] );

        curl_setopt(static::$CURL, CURLOPT_USERPWD, $AuthString);

        $Headers = [
            'Content-Type: application/json; charset=utf-8',
        ];

        if ( !empty(static::$Proxy) )
        {
            curl_setopt(static::$CURL, CURLOPT_PROXY, static::$Proxy);
            curl_setopt(static::$CURL, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        }

//        $proxy = '127.0.0.1:8888';
//
//        curl_setopt(static::$CURL, CURLOPT_PROXY, $proxy);
//        curl_setopt(static::$CURL, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

        curl_setopt(static::$CURL, CURLOPT_HTTPHEADER, $Headers);

        curl_setopt(static::$CURL, CURLOPT_POST, true);
        curl_setopt(static::$CURL, CURLOPT_POSTFIELDS, $Data);
        curl_setopt(static::$CURL, CURLOPT_RETURNTRANSFER, true);

        static::$LastRESPONSE = curl_exec(static::$CURL);

        curl_close(static::$CURL);
		
		$RAW_LOG = [
			'URL' => $URL,
			'DATA' => $Data,
			'RESPONSE' => static::$LastRESPONSE,
		];
		
		Logger::AddText( $RAW_LOG, 'SMEV/Raw' );

        return static::$LastRESPONSE;
    }

    /**
     * @param SMEVHelperRequest $Request
     * @return SMEVHelperResponse
     */
    public static function MakeRequest( $Request )
    {
        $Request->Update();

        //

        $Result = new SMEVHelperResponse();

        $Result->Success = false;

        $DateTimeOne = new \DateTime();

        $DateTimeTwo = clone $DateTimeOne;
        $DateTimeTwo->setTimezone(new DateTimeZone('Europe/Moscow'));

        $TimeStamp = time();

        $Sign = hash('sha256', $TimeStamp . static::$APIKey );

        $Data = [
            'type' => $Request->Type,
            'request' => $Request->Request,
            'timestamp' => $TimeStamp,
            'sign' => $Sign,
        ];

        $JSON = json_encode( $Data );

        $URL = static::$APIHost . 'smev_requests.json';

        $Res = static::Post( $URL, $JSON );

        $arResult = json_decode( $Res, true );

        static::$Last = $arResult;

        if ( array_key_exists( 'success', $arResult ) )
        {
            $Result->Success = (bool)$arResult['success'];

            if ( $Result->Success === true )
            {
                $Result->Response = ArrayHelper::Value( $arResult, 'response' );
            }
            else
            {
                $Result->Error = new SMEVHelperResponseFault();

                $Result->Error->Code = ArrayHelper::GetValuePath( $arResult, 'fault/faultCode' );
                $Result->Error->Message = ArrayHelper::GetValuePath( $arResult, 'fault/faultString' );
            }
        }

        return $Result;
    }
}