<?php
/**
 * Created by PhpStorm.
 * User: Яков
 * Date: 03.02.2017
 * Time: 1:57
 */

class HTTPHelper
{
    public static function GenerateUniversalBaseURL()
    {
        $https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? TRUE : FALSE;

        $default_port = ($https) ? 443 : 80;
        $protocol = ($https) ? 'https' : 'http';

        $port = ($_SERVER['SERVER_PORT'] == $default_port) ? '' : ':' . $_SERVER['SERVER_PORT'];

        $Result =  $protocol . '://' . $_SERVER['SERVER_NAME'] . $port;

        return $Result;
    }

    public static function Post( $URL, $Data )
    {
        $curl = curl_init($URL);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($Data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public static function BuildParsedURL(array $parts)
    {
        $Result = (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');

        return $Result;
    }

    public static function PushParsedURLVariable(array $Parts, $Name, $Value)
    {
        $LocationParsed = $Parts;

        $LocationQuery = explode( '&', ArrayHelper::Value( $LocationParsed, 'query', '' ) );

        $LocationQueryProcessed = [];

        foreach ( $LocationQuery as $LocationQueryString )
        {
            $LocationQueryStringArray = explode( '=', $LocationQueryString );

            switch ( count($LocationQueryStringArray) )
            {
                case 1:
                    if ( !empty($LocationQueryStringArray[0]) )
                    {
                        $LocationQueryProcessed[] = $LocationQueryStringArray[0];
                    }
                    break;

                case 2:
                    $LocationQueryProcessed[ $LocationQueryStringArray[0] ] = $LocationQueryStringArray[1];
                    break;
            }
        }

        //

        $LocationQueryProcessed[ $Name ] = $Value;

        $LocationParsed['query'] = http_build_query( $LocationQueryProcessed );

        //

        return $LocationParsed;
    }
}