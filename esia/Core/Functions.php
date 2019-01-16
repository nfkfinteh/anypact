<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 4:08
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/EsiaLogger.class.php';

function xDump($X)
{
    $XX = PHP_EOL . json_encode( $X, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) . PHP_EOL;

    echo "<!-- $XX -->" . PHP_EOL;
}

function Bool2YN( $Value )
{
    return ( $Value ) ? 'Y' : 'N';
}

function xShutDown()
{
    $Error = error_get_last();

    \Logger::AddText( $Error, 'ESIA/Work1ShutDown' );
}

register_shutdown_function('xShutDown');

function xDate2MySQLDate( $Value )
{
    \Logger::AddText( $Value, 'ESIA/Debug' );

    $Result_Obj = \DateHelper::GetDateFromString( $Value );

    \Logger::AddText( $Result_Obj, 'ESIA/Debug' );

    $Result = ( is_object($Result_Obj) && ( $Result_Obj instanceof \DateTime) )
        ? $Result_Obj->format( \DateHelper::Y_m_d )
        : '';

    \Logger::AddText( $Result, 'ESIA/Debug' );

    return $Result;
}

/////
///
///
///
function c_date($date)
{
    $month=array(
        '01' =>'января',
        '02' =>'февраля',
        '03' =>'марта',
        '04' =>'апреля',
        '05' =>'мая',
        '06' =>'июня',
        '07' =>'июля',
        '08' =>'августа',
        '09' =>'сентября',
        '10' =>'октября',
        '11' =>'ноября',
        '12' =>'декабря');

    $da= explode("-", $date);
    $date_out = $da[2].' '.$month[$da[1]].' '.$da[0].' г.';
    return $date_out;
}

function ret_date($date)
{
    $da= explode(".", $date);
    $date_out = $da[2].'-'.$da[1].'-'.$da[0];
    return $date_out;
}

function ret_date1($date)
{
    $da= explode("-", $date);
    $date_out = $da[2].'/'.$da[1].'/'.$da[0];
    return $date_out;
}

function td_date($date)
{

    $date_out = date("Y-m-d H:i:s", $date);
    return $date_out;
}

function get_data($url,$id_cl){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'id_client='.$id_cl);

    $content = curl_exec($ch);
    return $content;
}

/**
 * @param \EsiaCore $Core
 * @param $Param
 * @param $Value
 */
function XChecked( $Core, $Param, $Value, $Default = null )
{
    $CurrentValue = ArrayHelper::GetValuePath( $Core->Detail, 'RESULT/' . $Param );

    if ( $Default === null )
    {
        $Result = ( $CurrentValue === $Value ) ? 'checked="checked"' : '';
    }
    else
    {
        $CurrentValue2 = ( $CurrentValue === $Value ) ? true : $Default;

        $Result = ( $CurrentValue2 ) ? 'checked="checked"' : '';
    }

    return $Result;
}

/**
 * @param \EsiaCore $Core
 * @param $Param
 * @param $Value
 */
function XCheckedCurrent( $Core, $Param, $Value, $Default = null )
{
    $CurrentValue = ArrayHelper::GetValuePath( $Core->Current, $Param );

    if ( $Default === null )
    {
        $Result = ( $CurrentValue === $Value ) ? 'checked="checked"' : '';
    }
    else
    {
        if ( is_null( $CurrentValue ) )
        {
            $CurrentValue2 = $Default;
        }
        else
        {
            $CurrentValue2 = ( $CurrentValue === $Value );
        }

        $Result = ( $CurrentValue2 ) ? 'checked="checked"' : '';
    }

    return $Result;
}

function XModelTrim( $Value )
{
    $Result = trim( $Value );

    return $Result;
}

function xExtractDigits2Array( $Text, $Count )
{
    $Digits = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
    ];

    $Result = [];

    for ($i = 0, $j = strlen($Text); $i < $j; $i++)
    {
        if ( count($Result) > $Count )
        {
            break;
        }

        $Char = $Text[$i];

        if ( in_array( $Char, $Digits ) )
        {
            $Result[] = $Char;
        }
    }

    return $Result;
}

function xExtractDigits2String( $Text, $Count )
{
    $Res = xExtractDigits2Array( $Text, $Count );

    $Result = implode( '', $Res );

    return $Result;
}

function XModelExtractDigits( $Value, $Digits )
{
    $Result = xExtractDigits2String( $Value, $Digits );

    return $Result;
}

function XModelPregReplace( $Value, $Pattern, $Replace )
{
    $Result = preg_replace( $Pattern, $Replace, $Value );

    return $Result;
}

function XModel( $Source, $Model )
{
    $Result = [];

    foreach ( $Model as $To => $Config )
    {
        $From = ArrayHelper::Value( $Config, 'SOURCE' );
        $Calls = ArrayHelper::Value( $Config, 'CALLS' );

        if ( empty( $From ) || empty( $Calls ) )
        {
            continue;
        }

        $Value = ArrayHelper::Value( $Source, $From );

        foreach ( $Calls as $CallableName => $CallableParams )
        {
            if ( !is_callable( $CallableName ) )
            {
                continue;
            }

            $Params = [
                $Value,
            ];

            if ( !empty( $CallableParams ) && is_array( $CallableParams ) )
            {
                $Params = array_merge( $Params, $CallableParams );
            }

            $Value = call_user_func_array( $CallableName, $Params );
        }

        if ( !empty( $Value ) )
        {
            $Result[ $To ] = $Value;
        }
    }

    return $Result;
}

/**
 * @param ModX $modx
 * @param $Table
 * @param $Source
 * @return string
 */
function XMySQLUpdateSimple( $modx, $Table, $Source, $WhereID )
{
    $Result = '';

    $Updates = [];

    foreach ( $Source as $SourceKey => $SourceValue )
    {
        if ( !empty( $SourceValue ) )
        {
            $Updates[] = $SourceKey . '=' . $modx->quote( $SourceValue );
        }
    }

    if ( !empty( $Updates ) )
    {
        $Result = 'UPDATE '.$Table.' SET ' . implode( ', ', $Updates ) . ' WHERE id=\'' . $WhereID . '\';';
    }

    return $Result;
}