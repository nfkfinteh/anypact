<?
class ConfigESIA
{   
    public $config;

    function __construct() {
        $keys_dir = $_SERVER['DOCUMENT_ROOT'] . '/esia/sert';

        $config = array(
            "site" => "https://esia.gosuslugi.ru/", //esia portal
            "redirect_uri" => "http://anypact.nfksber.ru/profile/",  //callback url
            "pkey_path"  => $keys_dir."/secret_NFKS01211.key",
            "cert_path"  => $keys_dir."/cert_NFKS01211.crt",
            "client_id" => "NFKS01211",
            "scope" => "openid fullname id_doc"
            );
        
        $this->config = $config;
    }

}

?>