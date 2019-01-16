<?php
/**
 * Created by PhpStorm.
 * User: Яков
 * Date: 27.10.2016
 * Time: 13:07
 */

class PhoneHelper
{
    public static function FormatPhone($Phone, $Mask = '+7 ($1) $2-$3-$4')
    {
        $Result = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{2})[^\d]{0,7}(\d{2}).*~', $Mask, $Phone);

        return $Result;
    }
}