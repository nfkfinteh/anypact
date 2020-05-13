<?php
#set_include_path(realpath(dirname(__FILE__)) . '/phpseclib');
#require_once realpath(dirname(__FILE__)) . "/phpseclib" . '/Crypt/RSA.php';
#require_once realpath(dirname(__FILE__)) . "/phpseclib" . '/Crypt/AES.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/phpseclib/Crypt/RSA.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/phpseclib/Crypt/AES.php';
/**
 * Esia authentication
 * based on https://esia.gosuslugi.ru
 *
 * More information on Opauth: http://rnds.pro
 *
 * @copyright    Copyright © 2014 Alexander Dmitriev (http://rnds.pro)
 * @link         http://rnds.pro
 * @package      Esia
 * @license      MIT License
 */


class Esia {

  /*
   * save to log debug info
   */

  const DEBUG = true;


	/**
	 * Compulsory config keys, listed as unassociative arrays
   *
   *  assertion_consumer_service_url     => "https://esia.s.rnd-soft.ru/SOAP/ACS", //back url
   *  issuer                             => "http://esia.s.rnd-soft.ru",           //the unique identifier of the identity provider
   *  pkey_path                          => "${your_path_to_keys_dir}/rnds-key.key",
   *  idp_cert                           => "${your_path_to_keys_dir}/rnds-cert.pem",
   *  idp_sso_target_url                 => "https://esia.gosuslugi.ru/idp/profile/SAML2/Redirect/SSO" //request url
   *  idp_slo_target_url                 => "https://esia.gosuslugi.ru/idp/profile/SAML2/Redirect/SLO" //logout request url
	 */

	public $expects = array('assertion_consumer_service_url', 'issuer', 'pkey_path', 'idp_cert', 'idp_sso_target_url', 'idp_slo_target_url');

  /*
   * Query saml string
   */
  private $requestSaml;


  /*
   * Query saml string
   */
  public $resultXml;

  /*
   * UUID for request saml
   */

  public $uuid;

  /*
   * Config object
   */

  public $config;


  /*
   * SSL signature
   */
  private $signature;


	/**
	 * Constructor
	 * Loads user configuration and strategies.
	 *
	 * @param array $config User configuration
	 */

	public function __construct($config = array()) {
    /* compare arrays keys config or empty $config */
    if(sizeof($config) == 0 || sizeof(array_diff(array_keys($config), $this->expects)) > 0){
      $this->configError();
      exit();
    }
    /* set config */
    else{
      $this->config = $config;
    }
  }

  /************** Request Area *****************/

	/**
	 * Send auth request
	 */

	public function request(){

      /* Url redirect to esia */
      $url = $this->config['idp_sso_target_url'];

      /* params requests */
      $params = array();

      /* add SAMLRequest to params */
      $saml_request = $this->makeSamlRequest();
      $this->debugSave($saml_request, 'Saml Request');

      /* set params */
      $params['SAMLRequest'] = $saml_request;
      $params['Signature']  = '-';
      $params['SigAlg']     = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';


      $this->clientGet($url, $params);
  }

  /**
   * Make saml base64 request
   */

  public function makeSamlRequest(){
      /* generate uuid */
      $this->uuid = $this->generate_GUID();

      /* generate saml */
      $saml = $this->generateSaml();
      $this->debugSave($saml, 'Initial generation');

      /* get sign xml */
      $sign_xml = $this->xmlWithSign($saml);

      /* add to xml node with sign */
      $saml = str_replace('</saml2p:AuthnRequest>', $sign_xml . "</saml2p:AuthnRequest>", '<?xml version="1.0" encoding="UTF-8"?>' . $saml);
      $this->debugSave($saml, 'Finale xml');

      $gzip = gzdeflate($saml, 9);
      return base64_encode($gzip);
  }


	/**
	 * Send logoute request
	 */

	public function request_logout($user_id, $session_index){

      /* Url redirect to esia */
      $url = $this->config['idp_slo_target_url'];

      /* params requests */
      $params = array();

      /* add SAMLRequest to params */
      $saml_request = $this->makeSamlLogoutRequest($user_id, $session_index);
      $this->debugSave($saml_request, 'Saml Logout Request');

      /* set params */
      $params['SAMLRequest'] = $saml_request;
      $params['Signature']  = '-';
      $params['SigAlg']     = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';


      $this->clientGet($url, $params);
  }


  /**
   * Make saml base64 logout request
   */

  public function makeSamlLogoutRequest($user_id, $session_index){
      /* generate uuid */
      $this->uuid = $this->generate_GUID();

      /* generate saml */
      $saml = $this->generateLogoutSaml($user_id, $session_index);
      $this->debugSave($saml, 'Initial logout generation');

      /* get sign xml */
      $sign_xml = $this->xmlWithSign($saml);

      /* add to xml node with sign */
      $saml = str_replace('</saml2p:LogoutRequest>', $sign_xml . "</saml2p:LogoutRequest>", '<?xml version="1.0" encoding="UTF-8"?>' . $saml);
      $this->debugSave($saml, 'Finale logout xml');

      $gzip = gzdeflate($saml, 9);
      return base64_encode($gzip);
  }


  /**
    * returns Xml with sign
   */

  public function xmlWithSign($saml){

      /* decode to UTF-8 */
      $saml = utf8_decode($saml);
      $this->debugSave($saml, 'UTF-8 generation');

      return $this->generateXmlSign($saml);
  }


  /**
    * generate sign xml
   */
  public function generateXmlSign($xml){
      /* make ssl dagest */
      $dagest = $this->dagest($xml);

      /* insert dagest to sign template */
      $xml_for_sign = $this->signInfoForSign($dagest);
      $this->debugSave($xml_for_sign, 'Info xml');

      /*canonical xml */
      $xml= new DOMDocument();
      $xml->loadXML($xml_for_sign);
      $xml_for_sign=$xml->c14n();
      $this->debugSave($xml_for_sign, 'Info xml for sign');

      /* make sign */
      $sign_value = $this->sign($xml_for_sign);
      $this->debugSave($sign_value, 'Value');

      /* get cert */
      $cert = $this->getCert();
      $this->debugSave($cert, 'Cert');

      /* make sign xml */
      $sign_result_xml = $this->signatureTemplate($xml_for_sign, $sign_value, $cert);
      $this->debugSave($sign_result_xml, 'Result');

      return $sign_result_xml;
  }


  /**
   * Generate uuid
   * @param $prefix default '_'
   */

  static function generate_GUID($prefix='_') {
        $uuid = md5(uniqid(rand(), true));
        $guid =  $prefix.substr($uuid,0,8)."-".
                substr($uuid,8,4)."-".
                substr($uuid,12,4)."-".
                substr($uuid,16,4)."-".
                substr($uuid,20,12);
        return $guid;
   }


  /**
   * Make dagest openssl and returns base 64 string
   * @param $xml_str xml string for signing xml
   */

  final public function dagest($xml_str){
    $xml_str = $this->clean_node($xml_str);
    $this->debugSave($xml_str, 'Clean node');
    return base64_encode(openssl_digest($xml_str, 'sha1', true));
  }

  /**
   * Sign a xml node
   */

  final public function sign($xml_str){
    /* fetch private key from file and ready it */
    if($pkeyid = openssl_pkey_get_private('file://'.$this->config['pkey_path'])){
      /* compute signature */
      if(openssl_sign($xml_str, $signature, $pkeyid, OPENSSL_ALGO_SHA1)){

        /* free the key from memory */
        openssl_free_key($pkeyid);

        /* base 64 encode signature */
        $base64signature = base64_encode($signature);

        $this->debugSave($base64signature, 'Base64 Signature');
        return $base64signature;
      }

      else{

        /* free the key from memory */
        openssl_free_key($pkeyid);
        trigger_error('Error sign processing', E_USER_ERROR);
      }
    }
    else{
     trigger_error('Openssl private key is invalide. Check our pkey_path config file path', E_USER_ERROR);
    }
  }




  /************** Response Area *****************/

  /**
   * Get base 64 saml request and returns xml with user data
   * @param $saml_base64_response base 64 SAMLResponse
   * @param $options are array return get string, dom or array. When string returns xml as a string, when dom returns DOMDocument object. When array returns user data array Default 'string'.
   */

  public function response($saml_base64_response, $option='string'){
    $response = $this->decode($saml_base64_response);
    /* return formats */
    if($option == 'string'){
      return $response;
    }
    elseif($option == 'dom'){
      /* load dom */
      $finaldom = new DOMDocument();
      $finaldom->loadXML($response);
      return $finaldom;
    }
    elseif($option == 'array'){
      /* load dom */
      $dom = new DOMDocument();
      $dom->loadXML($response);

      /* convert to array */
      $final_array = array();
      foreach($dom->childNodes->item(0)->childNodes->item(5)->childNodes as $item){
        $final_array[$item->attributes->item(0)->value] = $item->textContent;
      }
      /* session index for logout request */
      $final_array['SessionIndex'] = $dom->childNodes->item(0)->childNodes->item(4)->getAttribute('SessionIndex');
      $final_array['NameID'] = $dom->childNodes->item(0)->childNodes->item(2)->childNodes->item(0)->textContent;

      return $final_array;

    }else{
      trigger_error('$options mast be "string", "dom" or "array" string', E_USER_ERROR);
    }
  }



  /**
   * Decode xml by openssl
   * @param $response base 64 SAMLResponse
   *
   *
   * Decod algoritm:
   * 1. Get and decode cipherkey;
   * 2. Get and decode xml by got cipherkey;
   *
   */

  public function decode($response){

    /* base 64 encode saml */
    $decoded = base64_decode($response);
    $this->debugSave($decoded, 'Response decoded');

    /* try to unzip for logout requests*/
    $inflated = @gzinflate($decoded);
    if ($inflated){
      $this->debugSave($inflated, 'Unziping decode');
      /* load dom */
      $dom = new DOMDocument();
      $dom->loadXML($inflated);

      /* logout request if we send request */
      if($dom->childNodes->item(0)->nodeName == 'saml2p:LogoutResponse'){
        return array('logout', $inflated);
      }

      /* logout request other send request */
      if($dom->childNodes->item(0)->nodeName == 'saml2p:LogoutRequest'){
        return array('provider_logout', $inflated);
      }
    }

    /* verify esia certificate LoginRequest */
    if($this->certificate_validation($decoded)){
      /* load dom */
      $dom = new DOMDocument();
      $dom->loadXML($decoded);

      /* get chiper values */
      $cipher_values = $dom->getElementsByTagNameNS('http://www.w3.org/2001/04/xmlenc#', 'CipherValue');


      /* get encrypted cipherkey */
      $encrypted_password = base64_decode($cipher_values->item(0)->textContent);

      /* decrypt cipherkey */
      $cipherkey = $this->decryptCipherkey($encrypted_password);


      /* get encrypted xml */
      $encrypted_xml = base64_decode($cipher_values->item(1)->textContent);

      /* decrypt xml */
      $xml = $this->decryptXml($encrypted_xml, $cipherkey);
      $this->debugSave($xml, 'Final xml');

      return $xml;
    }
  }


  /********** Protected area **********/

  /**
   * Verify esia sertificate
   * @param $decoded base 64 decoded SAMLResponse
   */
  protected function certificate_validation($decoded){
    $verify = new DOMDocument();
    $verify->loadXML($decoded);

    $esia_cert = $verify->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'X509Certificate');
    $signature = $verify->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'Signature')->item(0);
    $esia_dgst = $signature->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'DigestValue')->item(0)->textContent;
    $esia_sign = $signature->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'SignatureValue')->item(0)->textContent;
    $signed_info = $signature->getElementsByTagNameNS('http://www.w3.org/2000/09/xmldsig#', 'SignedInfo')->item(0);

    //remove signature
    $root   = $verify->documentElement;
    $response  = $root->firstChild;
    $root->removeChild($signature);

    $dgst = base64_encode(sha1($verify->C14N(true, false), true));

    //check digest
    if($dgst == $esia_dgst){
      $esia_pub_key = openssl_pkey_get_public('file://'.__DIR__.'/esia.pem');


      //php need to new DOMDocument for c14n, wtf?
      $sign_c14n = new DOMDocument();
      $cloned = $signed_info->cloneNode(TRUE);
      $sign_c14n->appendChild($sign_c14n->importNode($cloned,TRUE));
      $canonical_signed_info = $sign_c14n->C14N(true, false);

      //verify sertificate
      if(openssl_verify($canonical_signed_info, base64_decode($esia_sign), $esia_pub_key) === 1){
        return true;
      }else{
        trigger_error('Esia signature is not valid!', E_USER_ERROR);
      }
    }else{
      trigger_error('Esia digest is not valid!', E_USER_ERROR);
    }
  }

  /**
   * Saml esia
   */

  protected function generateSaml(){
    /* Don't change spaces and returns */
    return trim('
      <saml2p:AuthnRequest xmlns:saml2p="urn:oasis:names:tc:SAML:2.0:protocol" AssertionConsumerServiceURL="'. $this->config['assertion_consumer_service_url'].'" Destination="'. $url = $this->config['idp_sso_target_url'] .'" ForceAuthn="false" ID="'. $this->uuid .'" IsPassive="false" IssueInstant="'. $this->strftimeWithMilliseconds() .'" ProtocolBinding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Version="2.0">
<saml2:Issuer xmlns:saml2="urn:oasis:names:tc:SAML:2.0:assertion">'. $this->config['issuer']. '</saml2:Issuer>
</saml2p:AuthnRequest>');
  }


  /**
   * Saml logout esia
   */

  protected function generateLogoutSaml($user_id, $session_index){
    /* Don't change spaces and returns */

    return trim('<saml2p:LogoutRequest xmlns:saml2p="urn:oasis:names:tc:SAML:2.0:protocol" Destination="'. $this->config['idp_slo_target_url'] .'" ID="'. $this->uuid .'" IssueInstant="'. $this->strftimeWithMilliseconds() .'" Reason="urn:oasis:names:tc:SAML:2.0:logout:user" Version="2.0">
<saml2:Issuer xmlns:saml2="urn:oasis:names:tc:SAML:2.0:assertion">'. $this->config['issuer']. '</saml2:Issuer>
<saml2:NameID xmlns:saml2="urn:oasis:names:tc:SAML:2.0:assertion" Format="urn:oasis:names:tc:SAML:2.0:nameid-format:transient">' .$user_id. '</saml2:NameID>
<saml2p:SessionIndex>' .$session_index. '</saml2p:SessionIndex>
</saml2p:LogoutRequest>');
  }


  protected function strftimeWithMilliseconds(){
       $m = explode(' ',microtime());
       list($totalSeconds, $extraMilliseconds) = array($m[1], (int)round($m[0]*1000,3));
       return gmdate("Y-m-d\TG:i:s", $totalSeconds) . ".${extraMilliseconds}Z";
  }

  protected function configError(){
    trigger_error('You should set $config. Example: "new Esia(array(

     "assertion_consumer_service_url"     => "https://esia.s.rnd-soft.ru/SOAP/ACS", //back url
     "issuer"                             => "http://esia.s.rnd-soft.ru",           //the unique identifier of the identity provider
     "pkey_path"                          => "${your_path_to_keys_dir}/rnds-key.key",
     "idp_cert"                           => "${your_path_to_keys_dir}/rnds-cert.pem",
     "idp_sso_target_url"                 => "https://esia.gosuslugi.ru/idp/profile/SAML2/Redirect/SSO" //request url

    ));"', E_USER_ERROR);
  }

  /**
   * Clean saml node
   * @param $str xml string
   * !Simple xml adds <?xml version="1.0" encoding="UTF-8"?> after save to string if root node saving(ag-gg-rrrr....)
   */
  protected function clean_node($str){
     /*$str = str_replace('<?xml version="1.0" encoding="UTF-8"?>', "", $str);*/
     return trim($str);
  }

  /**
   * Make SignedInfo tag for sign
   * @param $digest digest from Esia::digest
   */
  protected function signInfoForSign($digest){
    /* Don't change spaces and returns */
    return trim('
<ds:SignedInfo xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
    <ds:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:CanonicalizationMethod>
    <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"></ds:SignatureMethod>
    <ds:Reference URI="#'. $this->uuid .'">
      <ds:Transforms>
        <ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"></ds:Transform>
        <ds:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"></ds:Transform>
      </ds:Transforms>
      <ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"></ds:DigestMethod>
      <ds:DigestValue>'. $digest .'</ds:DigestValue>
    </ds:Reference>
  </ds:SignedInfo>');
  }

  /**
   * Final signature
   * @param $sign_info signed xml string from Esia::signInfoForSign
   * @param $sign_value sign string from Esia::sign
   * @param $cert certificate string from Esia::getCert
   */
  protected function signatureTemplate($sign_info, $sign_value, $cert){
    /* Don't change spaces and returns */
    return trim('
<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
  '. $sign_info .'
  <ds:SignatureValue>'. $sign_value .'</ds:SignatureValue>
  <ds:KeyInfo>
    <ds:X509Data>
      <ds:X509Certificate>'. $cert .'</ds:X509Certificate>
    </ds:X509Data>
  </ds:KeyInfo>
</ds:Signature>');

  }




  /**
   * decrypting cipherkey from saml
   * @param $encrypt_pass encrypt password
   */
  protected function decryptCipherkey($encrypt_pass){
      $privatekey = file_get_contents($this->config['pkey_path']);
      $rsa = new Crypt_RSA();
      $rsa->loadKey($privatekey); // private key
      return($rsa->decrypt($encrypt_pass));
  }


  /**
   * decrypting xml from saml
   * @param $encrypt_xml encrypt xml
   * @param $cipherkey cipherkey
   */
  protected function decryptXml($encrypt_xml, $cipherkey){
    $aes = new Crypt_AES(CRYPT_AES_MODE_CBC); //mcrypt is used
    /*Decrypt request from application. [IV 32 CHARS IN HEX] */
    $aes->setKeyLength(128);
    $aes->setKey($cipherkey);
    //Iv
    $IV = substr($encrypt_xml,0,16 );
    $aes->setIV( $IV );
    /* Encrypted text in binary */
    $encryptedTextBin =  substr($encrypt_xml, 16) ;
    $decryptedRequest = $aes->decrypt(  $encryptedTextBin );
    return $decryptedRequest;
  }

  /********** Private area **********/

  /**
   * Debug
   */

  private static function debugSave($content, $notice = ''){
    if(self::DEBUG){
      $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
              $notice.PHP_EOL.
              $content.PHP_EOL.
              "-------------------------".PHP_EOL;
      //Save string to log, use FILE_APPEND to append.
      file_put_contents(self::debug_file_path() . 'class_log.log', $log, FILE_APPEND);
    }
  }

  private static function debug_file_path(){
    return realpath(dirname(__FILE__)) . '/log/';
  }


  /**
   * load own sertificate string from config
   */
  private function getCert(){
    return $this->loadCert($this->config['idp_cert']);
  }


  /**
   * clean sertificate from file
   */

  private function loadCert($file_name){
    $cert = file_get_contents($file_name);
    $cert = str_replace('-----BEGIN CERTIFICATE-----', '',  $cert);
    $cert = str_replace('-----END CERTIFICATE-----', '',  $cert);
    return trim($cert);
  }



	/**
	 * Redirect to $url with HTTP header (Location: )
	 *
	 * @param string $url URL to redirect user to
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public static function redirect($url, $exit = true) {
		header("Location: $url");
		if ($exit) {
			exit();
		}
	}

	/**
	 * Client-side GET: This function builds the full HTTP URL with parameters and redirects via Location header.
	 *
	 * @param string $url Destination URL
	 * @param array $data Data
	 * @param boolean $exit Whether to call exit() right after redirection
	 */
	public static function clientGet($url, $data = array(), $exit = true) {
		self::redirect($url.'?'.http_build_query($data, '', '&'), $exit);
	}
}//end Esia



