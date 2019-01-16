<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 10.10.2016
 * Time: 15:45
 */

class RandomFx
{
    public static function UnicalDATA($solt = '', $strong = false)
    {
		$data = '';
		if (isset($_SERVER) && (is_array($_SERVER)))
		{
			$data = implode(';', $_SERVER);
		}
        $data .= '@' . uniqid('pref', true);

        $data .= '%' . microtime(true);

        if ( function_exists('openssl_random_pseudo_bytes') && $strong == true )
        {
            $pseudo_bytes = openssl_random_pseudo_bytes( 32 );

            $data .= $pseudo_bytes;
        }

        $data .= $solt;

        return $data;
    }

    public static function UnicalMD5($solt = '')
    {
        $data = static::UnicalDATA($solt);

        return md5($data);
    }

    public static function UnicalMD5Strong($solt = '')
    {
        $data = static::UnicalDATA($solt, true);

        return md5($data);
    }

    public static function UnicalSHA1($solt = '')
    {
        $data = static::UnicalDATA($solt);

        return sha1($data);
    }

    public static function UnicalSHA1Strong($solt = '')
    {
        $data = static::UnicalDATA($solt, true);

        return sha1($data);
    }

    public static function UnicalSHA256($solt = '')
    {
        $data = static::UnicalDATA($solt);

        return hash('sha256', $data);
    }

    public static function UnicalSHA256Strong($solt = '')
    {
        $data = static::UnicalDATA($solt, true);

        return hash('sha256', $data);
    }

    /** Генерация строки
     *
     * @param int  $len      Длина строки
     * @param bool $isDigits Ипользовать только числа
     *
     * @return string Псевдослучайная строка
     */
    public static function RandString($len = 5, $isDigits = false, $IncludeSimilarSymbols = false)
    {
        if ($isDigits)
        {
            $charset = '0123456789';
        }
        else
        {
            $charset = '0123456789ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

            if ( $IncludeSimilarSymbols )
            {
                $charset .= 'lI';
            }
        }

        $base = strlen($charset);
        $result = '';

        do
        {
            $ind = rand(0, $base);
            $result .= $charset[$ind];
        }
        while (strlen($result) < $len);

        return $result;
    }

    /**
     * Генерирует случайное имя файла в определённой
     * директории, проверяя, не существует ли файл с таким же именем?
     *
     * @param    string $dir Папка в которой будет осуществлятся проверка на существование файла
     * @param    string $extension Необходимое расширение файла
     * @return    string                        Полное имя файла включая расширение
     */
    public static function RandomFileName($dir, $extension, $full = true)
    {
        while ( true )
        {
            $name = static::UnicalMD5($dir . $extension ) . '.' . $extension;

            $file_name = $dir . $name;

            if ( !file_exists( $file_name ) )
            {
                break;
            }
        }

        $Result = ( $full == true ) ? $file_name : $name;

        return $Result;
    }
}