<?php
class payYandex {

    /* 
        Отправка запроса на коннектор с платежной системой
    */
    public function postParamsUserPay($url, $RestParams=''){
        if( $curl = curl_init() ) {    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $RestParams);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // если есть проблеммы с сертификатом
            curl_setopt($curl, CURLOPT_VERBOSE, 1);    
            $out = curl_exec($curl);
        
            if($errno = curl_errno($curl)) {
                $out = curl_strerror($errno);                
            }
            curl_close($curl);            

            return $out;
        
        }else {
            return 'error_connect';
        }
    }

}

?>