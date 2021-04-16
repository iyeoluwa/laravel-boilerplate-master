<?php


namespace App\Services\Bring\API\Client;
use App\Services\Bring\Client\Client;
use GuzzleHttp\Exception\RequestException;
use App\Services\Bring\Contract\ShippingGuide\PriceRequest;
use App\Services\Bring\API\Client\ShippingGuideClientException;



class ShippingGuideClient extends Client
{
    const   BRING_PRICES_API = 'https://api.bring.com/shippingguide/v2/products';

    protected  $_apiBringPrices  = self::BRING_PRICES_API;


    public function getprices(PriceRequest $request){
        $query = $request->toArray();

        $url = $this->_apiBringPrices;

        $options = [
            'query'=>$this->getQueryParams($query)
        ];

        try{
            $request = $this->request('get',$url,$options);
            $json = json_decode($request->getBody(),true);
            return $json;
        }catch(RequestException $e){
            throw new ShippingGuideClientException("Could not retrieve prices.",null,$e);
        }
    }

    public function setBringPricesApi($api){
        $this->_apiBringPrices = $api;
        return $this;
    }
}
