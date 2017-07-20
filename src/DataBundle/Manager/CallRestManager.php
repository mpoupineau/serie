<?php

namespace DataBundle\Manager;

use Symfony\Component\Config\Definition\Exception\Exception;
use DataBundle\Service\CallApiLogService;
use DataBundle\Entity\callApiLog;

class CallRestManager
{
    const APIKEY = '837CC70044812B57';
    const POST_TOKEN_URL = 'https://api.thetvdb.com/login';
    const DEFAULT_LANGUAGE = 'FR';
    private $token = null;

    /** @var callApiLogService $callApiLogService **/
    private $callApiLogService;

    public function __construct(
            CallApiLogService $callApiLogService
            ) 
    {
        $this->callApiLogService = $callApiLogService;
    }
    
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
        
        $this->logCall($this->token, $completeUrl, $language, $response);
        
        // If there is no result in FR, we try in EN
        if((isset(json_decode($response)->Error) || (isset(json_decode($response)->data) && json_decode($response)->data == null)) && $language == 'FR') {
            $response = $this->get($url, 'EN', $data);
            if($response->Error) {
                throw new Exception();
            }
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
    
    private function logCall($token, $url, $language, $response) 
    {
        $callApiLog = new callApiLog();
        
        $callApiLog->setToken($token);
        $callApiLog->setLanguage($language);
        $callApiLog->setUrl($url);
        $callApiLog->setResponse($response);
        
        $this->callApiLogService->save($callApiLog);
    }
}
