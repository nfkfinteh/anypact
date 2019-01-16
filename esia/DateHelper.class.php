<?php

/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 08.06.2016
 * Time: 17:28
 */

class DateHelper
{
	const  Y_m_d = 'Y-m-d';
	const  d_m_Y = 'd.m.Y';
	const  d_m_Y_H_i_s = 'd.m.Y H:i:s';
	const  Y_m_d_H_i_s_u = 'Y-m-d\TH:i:s.u';
	const  Y_m_d_H_i_s_uu = 'Y-m-d\TH:i:s.uu';
	const  Y_m_d_H_i_s = 'Y-m-d\TH:i:s';

	const d__m__Y = 'm/d/Y';

    const  d__m__Y__H__i__s = 'Y-m-d H:i:s';

	public static function GetDateFromString($strDate)
	{
		$arr = [
			self::Y_m_d,
			self::d_m_Y,
			self::d_m_Y_H_i_s,
			self::Y_m_d_H_i_s_u,
			self::Y_m_d_H_i_s_uu,
			self::Y_m_d_H_i_s,
            self::d__m__Y,
            self::d__m__Y__H__i__s,
		];

		foreach ($arr as $format)
		{
			$date = \DateTime::createFromFormat($format, $strDate);

            if ($date instanceof \DateTime)
            {
				return $date;
			}
		}

		return false;
	}

	public static function GetDateString($strDate, $format)
	{
		$date = static::GetDateFromString($strDate);

        if ($date)
        {
            return $date->format($format);
        }

		return false;
	}

    /**
     * @param \DateTime $DateFrom
     * @param \DateTime $DateTo
     * @param null $NameFormat
     * @return array
     */
	public static function GetDateListByRange( $DateFrom, $DateTo, $NameFormat = null )
    {
        if ( $NameFormat === null )
        {
            $NameFormat = static::d_m_Y;
        }

        $DateFromTimeStamp = $DateFrom->getTimestamp();
        $DateToTimeStamp = $DateTo->getTimestamp();

        if (  $DateFromTimeStamp > $DateToTimeStamp )
        {
            $DateToTimeStampTmp = $DateToTimeStamp;
            $DateToTmp = $DateTo;

            $DateTo = $DateFrom;
            $DateToTimeStamp = $DateFromTimeStamp;

            $DateFrom = $DateToTmp;
            $DateFromTimeStamp = $DateToTimeStampTmp;
        }

        $DateFromCloned = clone $DateFrom;

        $Result = [];

        while ( true )
        {
            $DateFromClonedStr = $DateFromCloned->format( $NameFormat );

            $Result[ $DateFromClonedStr ] = null;

            if ( $DateFromCloned->getTimestamp() >= $DateToTimeStamp )
            {
                break;
            }

            $DateFromCloned = $DateFromCloned->add( new \DateInterval('P1D') );
        }

        return $Result;
    }
}