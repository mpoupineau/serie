<?php

namespace Serie\DataBundle\Manager;

class CallRestManager
{
    const APIKEY = '837CC70044812B57';
    const POST_TOKEN_URL = 'https://api.thetvdb.com/login';
    const DEFAULT_LANGUAGE = 'FR';
    private $token = null;
    
    public function get($url, $language = 'FR', $data = null) {
        if(!$this->token) {
            $this->token = $this->getToken();
        }
        $completeUrl = $url;
        if($data) {
            $completeUrl .= '?'.http_build_query($data);
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $completeUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                'Accept-Language: '.$language,
                'Authorization: Bearer '.$this->token),
        ));

        $response = curl_exec($curl);
        
        if(curl_error($curl)) {
            curl_close($curl);
            return null;
        }
        
        curl_close($curl);
        
        // If there is no result in FR, we try in EN
        if(isset(json_decode($response)->Error) && $language == 'FR') {
            $response = $this->get($url, 'EN', $data);
        }
        //return $response;
        return json_decode($response);    
    }
    
    private function getToken() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_URL => $this::POST_TOKEN_URL,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
            CURLOPT_POSTFIELDS => json_encode(array(
                'apikey' => $this::APIKEY
            ))
        ));

        $response = curl_exec($curl);
        
        if(curl_error($curl)) {
            curl_close($curl);
            return null;
        }
        
        curl_close($curl);
        
        return json_decode($response)->token;
    }
}
