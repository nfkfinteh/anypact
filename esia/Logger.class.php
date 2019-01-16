<?
class Logger
{
	public static function AddText($str, $filename_suffx = '_')
    {
        $data = [];
        $data[] = date('d.m.Y H:i:s') . '-------------------------------------------------' . PHP_EOL;

        if ( is_string($str) )
        {
            array_push($data, $str . PHP_EOL);
        }
        else
        {
            array_push($data, print_r($str, true));
        }

        $new_file_path = $_SERVER["DOCUMENT_ROOT"] . '/logs/' . date('d.m.Y') . '/';
        $fileName = $new_file_path . $filename_suffx . '.txt';
        $dir = dirname($fileName);

        if ( !file_exists($dir) )
        {
            mkdir($dir, 0775, true);
        }

        if ( !file_exists($fileName) )
        {
            $isCreate = true;
        }
        else
        {
            $isCreate = false;
        }

        $res = file_put_contents($fileName, $data, FILE_APPEND);

        if ($isCreate)
        {
            chmod($fileName, 0644);
        }
    }
}