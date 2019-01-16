<?php
/**
 * Created by PhpStorm.
 * User: Яков
 * Date: 16.10.2016
 * Time: 1:18
 */

class ArrayHelper
{
    const MODE_READ = 'r';
    const MODE_WRITE = 'w';

    const PARAM_AVAILLABLE_CREATE = 'AvaillableCreate';
    const PARAM_AVAILLABLE_CREATE_STRING = 'AvaillableCreateString';

    public static function Value(&$Source, $Key, $Default = null)
    {
        $Result = $Default;

        if ( is_array($Source) )
        {
            if ( array_key_exists($Key, $Source) )
            {
                $Result = $Source[$Key];
            }
        }
        elseif ( is_object($Source) )
        {
            if ( property_exists($Source, $Key) )
            {
                $Result = $Source->$Key;
            }
        }

        return $Result;
    }

    protected static function ParseKey($Key)
    {
        $Delimetr = PHP_EOL;

        $Key = str_replace([']['],$Delimetr, $Key);
        $Key = str_replace(['[', ']'],$Delimetr, $Key);
        $Key = str_replace(['/', '\\'],$Delimetr, $Key);

        $Key = trim($Key);

        $Result = explode($Delimetr, $Key);

        return $Result;
    }

    protected static function ValuePath(&$Source, $SourceKey, $Mode, $Value = null, $Params = [])
    {
        $KeyParsed = static::ParseKey($SourceKey);

        $Result = &$Source;

        while ( count( $KeyParsed ) !== 0 )
        {
            $Key = array_shift($KeyParsed);

            $isLastKey = ( count( $KeyParsed ) === 0 ) ? true : false;

            if ( ( is_object( $Result )  && property_exists($Result, $Key) ) || ( is_object( $Result )  && $Mode === static::MODE_WRITE ) )
            {
                if ( !$isLastKey )
                {
                    $Result = &$Result->$Key;
                }
                else
                {
                    if ( $Mode === static::MODE_READ )
                    {
                        $ResultTmp = &$Result;
                        unset($Result);

                        $Result = $ResultTmp->$Key;
                    }
                    else
                    {
                        $Result->$Key = $Value;

                        unset($Result);
                        $Result = true;
                    }
                }

                continue;
            }

            if ( ( is_array( $Result )  && array_key_exists($Key, $Result) ) || ( is_array( $Result )  && $Mode === static::MODE_WRITE ) )
            {
                if ( !$isLastKey )
                {
                    $Result = &$Result[$Key];
                }
                else
                {
                    if ( $Mode === static::MODE_READ )
                    {
                        $ResultTmp = &$Result;
                        unset($Result);

                        $Result = &$ResultTmp[$Key];
                    }
                    else
                    {
                        $Result[$Key] = $Value;

                        unset($Result);
                        $Result = true;
                    }
                }

                continue;
            }

            if ( $Mode === static::MODE_READ )
            {
                unset($Result);
                $Result = $Value;
            }
            else
            {
                $AvaillableCreate = ( array_key_exists(static::PARAM_AVAILLABLE_CREATE, $Params) && $Params[static::PARAM_AVAILLABLE_CREATE] === true );
                $AvaillableCreateString = ( array_key_exists(static::PARAM_AVAILLABLE_CREATE_STRING, $Params) && $Params[static::PARAM_AVAILLABLE_CREATE_STRING] === true );

                if ( $AvaillableCreate !== true )
                {
                    unset($Result);
                    $Result = false;
                }
                else
                {
                    if ( is_array($Result) )
                    {
                        $Result[$Key] = $Value;
                    }
                    elseif ( is_object($Result) )
                    {
                        $Result->{$Key} = $Value;
                    }
                    else
                    {
                        if ( !$AvaillableCreateString )
                        {
                            unset($Result);
                            $Result = false;
                        }
                        else
                        {
                            $Array1 = [$Result];

                            $Array2 = [ $Key => $Value ];

                            $Result = array_merge($Array1, $Array2);
                        }
                    }

                    unset($Result);
                    $Result = true;
                }
            }

            break;
        }

        return $Result;
    }

    public static function GetValuePath(&$Source, $Key, $Default = null)
    {
        return static::ValuePath($Source, $Key, static::MODE_READ, $Default);
    }

    public static function SetValuePath(&$Source, $Key, $Value)
    {
        return static::ValuePath($Source, $Key, static::MODE_WRITE, $Value);
    }

    public static function SetValuePathEx(&$Source, $Key, $Value, $Params = [])
    {
        return static::ValuePath($Source, $Key, static::MODE_WRITE, $Value, $Params);
    }

    public static function ImplodeEx($glue = "", array $pieces, $ImplodeNull = false)
    {
        $Array = [];

        foreach ( $pieces as $piece )
        {
            if ( is_null( $piece ) )
            {
                continue;
            }

            $Array[] = $piece;
        }

        $Result = implode( $glue, $Array );

        return $Result;
    }
}