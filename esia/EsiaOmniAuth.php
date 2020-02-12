<?php
/**
 * Esia authentication through omniauth protocol
 * based on https://esia.gosuslugi.ru
 *
 * More information on Opauth: http://rnds.pro
 *
 * @copyright    Copyright © 2014 Alexander Dmitriev (http://rnds.pro)
 * @link         http://rnds.pro
 * @package      Esia
 * @license      MIT License
 */


class EsiaOmniAuth {

  /*
   * save to log debug info
   */

  const DEBUG = false;


  /*
   * UUID for request
   */

  public $uuid;

  /*
   * Config object
   */

  public $config;

  /*
	  * Compulsory config keys, listed as unassociative arrays
    * site: "https://esia-portal1.test.gosuslugi.ru/",
    * redirect_uri: 'http://localhost:3000/auth/callback',
    * client_id: 'GRST01611',
    * scope: 'openid http://esia.gosuslugi.ru/usr_brf http://esia.gosuslugi.ru/usr_inf',
    * cert_path: "${keys_path}/cert.crt",
    * pkey_path: "${keys_path}/secret.key"
   */

	public $expects = array('site', 'redirect_uri', 'client_id', 'scope', 'cert_path', 'pkey_path');

  /*
   * Signed file path
   */

  public $temp_dir;

	public function __construct($config = array()) {
    /* compare arrays keys config or empty $config */
    if(sizeof($config) == 0 || sizeof(array_diff(array_keys($config), $this->expects)) > 0){
      $this->configError();
      exit();
    }
    /* set config */
    else{
      $this->config = $config;
      $this->temp_dir = realpath(dirname(__FILE__)) . "/temp";
    }
  }

	/**
	 * Create request and redirect to esia site
	 */

  public function create(){
    $this->uuid = $this->generate_GUID();
    $time = $this->strftimeWithTimeZone();
    $params = array();

    //query format
    $params["client_id"]= $this->config["client_id"];
    $params["redirect_uri"]  = $this->config["redirect_uri"];
    $params["client_secret"] = "";
    $params["scope"] = $this->config["scope"];
    $params["response_type"] = 'code';
    $params["state"] = $this->uuid;
    $params["timestamp"] = $time;
    $params["access_type"] = 'online';
    $params["display"] = 'popup';


    //signing params
    $client_secret = $this->sign( $params );

    if($client_secret){
      $params["client_secret"] = $client_secret;

      //get code
      $_SESSION['esia_oauth_state'] = $this->uuid;
      $url = $this->config['site'] . "/aas/oauth2/ac" . '?' . http_build_query($params);

      self::redirect($url);
    }else{
      if(!$client_secret)
        $this->debugSave($client_secret, "Can't sign!!!");
      return false;
    }
  }


	/**
	 * Create request and redirect to esia site
   * @param code is code has gotten from create function
   * @return access_token
	 */

  public function get_token($code){
    $this->uuid = $this->generate_GUID();
    $time = $this->strftimeWithTimeZone();
    $params = array();

    //query format
    $params["client_id"]= $this->config["client_id"];
    $params["redirect_uri"]  = $this->config["redirect_uri"];
    $params["client_secret"] = "";
    $params["scope"] = $this->config["scope"];
    $params["state"] = $this->uuid;
    $params["timestamp"] = $time;

    //differents
    $params["code"]= $code;
    $params["grant_type"]= 'authorization_code';
    $params["token_type"] = 'Bearer';

    //signing params
    $client_secret = $this->sign( $params );

    if($client_secret){
      $params["client_secret"] = $client_secret;

      //get token
      $url = $this->config['site'] . "/aas/oauth2/te";
      $response = self::post($url, http_build_query($params));

      $json = json_decode($response);

      return $json->access_token;
    }else{
      if(!$client_secret)
        $this->debugSave($client_secret, "Can't sign!!!");
      return false;
    }
  }

  /**
    * Get info obout user
    * @param access_token has gotten by  get_token function
    * @return an array with two elements user_contacts and user_info
   */

  public function get_info($access_token){
    $header = "Authorization:  Bearer ${access_token}";

    //get user_id from token
    $splited = explode(".", $access_token);
    $decoded = base64_decode($this->url_data_decode($splited[1]));
    $json = json_decode($decoded);

    $user_id = $json->{'urn:esia:sbj_id'};

    // get contacts
    $contact_url = $this->config["site"] . "/rs/prns/${user_id}/ctts?embed=(elements)";
    $user_contacts = json_decode($this->get($contact_url, $header), true);

    //get user info
    $info_url = $this->config["site"] . "/rs/prns/${user_id}";
    $user_info = json_decode($this->get($info_url, $header), true);
	
	//get user addess
    $info_url = $this->config["site"] . "/rs/prns/${user_id}/docs?embed=(elements)";
    $user_docs = json_decode($this->get($info_url, $header), true);
	
	$info_url = $this->config["site"] . "/rs/prns/${user_id}/addrs?embed=(elements)";
    $user_addr = json_decode($this->get($info_url, $header), true);
    return array(
	              'user_id' => $user_id,
	              'user_info' => $user_info,
                  'user_contacts' => $user_contacts,
				  'user_docs' => $user_docs, 
				  'user_addr' => $user_addr
                );
  }

  
  


  /********** Private area **********/

  /**
   * Does sign PKSC7
   * @param $params containts config for sign
   */

  private function sign($params){
    $secret = $params["scope"] . $params["timestamp"] . $params["client_id"] . $params["state"];
    $this->save_for_sign($secret);

    $pkey = $this->config['pkey_path'];
    $cert = $this->config['cert_path'];

    $outfile = $this->signed_file();
    $infile = $this->for_sign_file();
    $command = "openssl smime -sign -in ${infile} -signer ${cert} -inkey ${pkey} -outform DER -out ${outfile} > /dev/null 2>&1";
    exec($command);

    if(file_exists($outfile)){
      $encoded = base64_encode(file_get_contents($this->signed_file()));
      return urlencode($this->url_data_encode($encoded));
    }else{
      return false;
    }
  }

  private function verify_signature($params) {
    $secret = $params["scope"] . $params["timestamp"] . $params["client_id"] . $params["state"];
    return openssl_pkcs7_verify($this->signed_file(), PKCS7_NOVERIFY);
  }

  /**
    * Change data accorded with esia standart
   */

  private function url_data_encode($data){
    $data = str_replace(array('+', '/'), array('-', '_'), $data);
    $data = preg_replace('/[=]+$/', '', $data);
    return $data;
  }

  private function url_data_decode($data){
    return str_replace(array('-', '_'), array('+', '/'), $data);
  }

  /**
   * Generate uuid
   * @param $prefix default '_'
   */

  private static function generate_GUID($prefix='') {
    $uuid = md5(uniqid(rand(), true));
    $guid =  $prefix.substr($uuid,0,8)."-".
            substr($uuid,8,4)."-".
            substr($uuid,12,4)."-".
            substr($uuid,16,4)."-".
            substr($uuid,20,12);
    return $guid;
   }


  protected function strftimeWithTimeZone(){
     return gmdate("Y.m.d G:i:s O");
  }

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

  protected function configError(){
    trigger_error('You should set $config. Example: "new Esia(array(
     site: "https://esia-portal1.test.gosuslugi.ru/",
     redirect_uri: "http://yoursite.ru/auth/callback",
     client_id: "YOURID123",
     scope: "openid http://esia.gosuslugi.ru/usr_brf http://esia.gosuslugi.ru/usr_inf",
     cert_path: "${your_keys_dir}/config/esia/cert.crt",
     pkey_path: "${your_keys_dir}/config/esia/secret.key"

    ));"', E_USER_ERROR);
  }

  protected function signed_file(){
    return $this->temp_dir . '/'. 'signed-' . $this->uuid;
  }


  protected function for_sign_file(){
    return $this->temp_dir . '/'. 'for-sign-' . $this->uuid;
  }

  /**
   * Save data in sing file
   * @param $data string for record
   */
  protected function save_for_sign($data) {
    $fp = fopen($this->for_sign_file(), "w");
    fwrite($fp, $data);
    fclose($fp);
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
   * POST request
   */
  private static function post($url, $body) {
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
            'content' => $body,
        ),
    ));

    return file_get_contents($file = $url, $use_include_path = false, $context);
  }

  /**
    * GET request
   */
  private function get($url, $header){
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'GET',
            'header' => $header . PHP_EOL
        ),
    ));

    return file_get_contents($file = $url, $use_include_path = false, $context);
  }


}//end EsiaOmniAuth
