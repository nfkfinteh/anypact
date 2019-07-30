<?php
/* 
    Запросы в СМЭВ
    первичный и повторный
*/
class requestSMEV {
    
    private $APIHost = 'https://nfks.p.rnds.pro/smev3_requests.json';
    private $APIUser = 'demo';        
    private $APIPass = 'OMHNLv4!52';
    private $APIKey = 'a9316be2819731ef7f3a4831b361d1a54177dfef293b4c09ca23368077b1d0eb00e06df7f60c0f53c0f47cb23d4b1a127db163f306fd212aa8afa571e0e41a';

    // подготовка данных к запросу
    private function getJsonParams($arrParams){
        $arrParamsJSON  = array(
            'type'      => 'VerifyRequest',
            'request'   => $arrParams,
            'timestamp' => '',
            'sign'      => '',
        );
        
        $Timestamp                  = time();
        $sing                       = hash('sha256', $Timestamp . $this->APIKey);        
        $arrParamsJSON['timestamp'] = $Timestamp;
        $arrParamsJSON['sign']      = $sing;       
        $arrParamsJSON = json_encode($arrParamsJSON);
        
        return $arrParamsJSON;
    }
   
    // запрос
    private function getRequestSMEV($arrParam){
        
        $arrParamsJSON  = $this->getJsonParams($arrParam);
        $headers        = array(
            'Content-Type: application/json; charset=utf-8'
        );
                
        $CurlRequest    = curl_init($this->APIHost);        
        curl_setopt_array($CurlRequest, array(    
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,    
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_USERPWD => $this->APIUser.":".$this->APIPass,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $arrParamsJSON
        ));
        $response       = curl_exec($CurlRequest);
        curl_close($CurlRequest);
        
        return $response;
    }

    // точка входа первичный, вторичный запрос
    public function getRequest($arrParam){
        
        $Response = $this->getRequestSMEV($arrParam);
        return $Response;

    }
    
}
