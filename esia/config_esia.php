<?
class ConfigESIA
{   
    public $config;

    function __construct() {
        $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';

        $config = array(
            "site" => "https://esia.gosuslugi.ru/", //esia portal
            "redirect_uri" => "https://anypact.ru/esia_test/esiadecode.php",  //callback url
            "pkey_path"  => $keys_dir."/secret.key",
            "cert_path"  => $keys_dir."/cert.crt",
            //"client_id" => "NFKS01211",
            "client_id" => "04VS01",    
            "scope" => "openid fullname id_doc"
            );
        
        $this->config = $config;
    }

}

?>