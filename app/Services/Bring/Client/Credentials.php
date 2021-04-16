<?php


namespace App\Services\Bring\Client;


class Credentials
{
    private $clientId;

    private $apiKey;

    private $clientUrl;

    /**
     *
     * This will create the Bring credentials Object.
     *
     * @param string $clientUrl ...(this is your domian)
     * @param string $clientId (this is the client id )
     * @param string $apiKey   (this will be the api key )
     *
     */

    public function __construct($clientUrl,$clientId = null,$apiKey = null){
        if(!$clientUrl){
            throw new \InvalidArgumentException('$clientUrl must not be empty');
        }
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->clientUrl = $clientUrl;

    }

    /**
     * @return string
     */
    public function getClientId(){
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getApiKey(){
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getClientUrl(){
        return $this->clientUrl;
    }

    public function hasAuthorizationData(){
        return $this->clientId !== null && $this->apiKey !== null;
    }





}
