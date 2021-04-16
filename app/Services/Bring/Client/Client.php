<?php


namespace App\Services\Bring\Client;

use GuzzleHttp\ClientInterface;


abstract class Client
{
    /**
     * @var Credentials
     *
     **/
    protected $_credentials;

    /**
     * @var ClientInterface
     */
    protected $_client;

    public function __construct(Credentials $credentials,ClientInterface $client = null)
    {
//        this will .....
        $this->_credentials = $credentials;

        if($client === null){
            $client = new \GuzzleHttp\Client();
        }
        $this->_client = $client;
    }

    public function setClient(ClientInterface $client){
//        this is what is responsible for setting the client
        $this->_client = $client;

    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $options
     * @return mixed
     */

    protected  function request($method, $endpoint, array $options =[]){
        $options = array_merge([
            'headers'=>[
                'Accept'=>'application/json'
            ]
        ], $options);

        if($credentials  = $this->_credentials){
            $options['headers']['X-Bring-Client-URL'] = $credentials->getClientUrl();

            if($credentials->getClientId() !== null){
                $options['headers']['X-MyBring-API-Uid'] = $credentials->getClientId();
            }
            if($credentials->getApiKey() !== null){
                $options['headers']['X-MyBring-API-Key'] = $credentials->getApiKey();
            }
        }

        $req = $this->_client->request($method,$endpoint,$options);

        return $req;
    }

    protected function xmlRequest($method, $endpoint, array $options = []){
        $options = array_merge([
            'headers'=>[
                'Accept' => 'text/xml',
                'Content-type' => 'text/xml'
            ]
        ],$options);
        return $this->request($method,$endpoint,$options);
    }


    /**
     *https://github.com/guzzle/guzzle/issues/1196 (although i have not checked if it has been corrected ..but just  in case)
     * @param array $data
     * @return string
     */
    protected function getQueryParams(array $data){
        $add = '';
        foreach($data as $k => $arr){
            if(is_array($arr)){
                foreach($arr as $value){
                    $add .= "&$k=" . urlencode($value);
                }
                unset($data[$k]);
            }else if($arr === null){
                unset($data[$k]);
            }else if(is_bool($arr)){
                $data[$k] = $arr ? 'true': 'false';
            }
        }
        $params = http_build_query($data) . $add;

        return $params;
    }

}
