<?php


namespace App\Services\Bring\API\Client;


use App\Services\Bring\Client\BookingClientException;
use App\Services\Bring\Client\API\Client;
use GuzzleHttp\Exception\RequestException;

class BookingClient extends Client
{
    const BRING_CUSTOMERS_API = 'https://api.bring.com/booking/api/customers.json';

    const BRING_BOOKING_API = 'https://api.bring.com/booking/api/booking';

    protected $_apiBringCustomers = self::BRING_CUSTOMERS_API;

    protected $_apiBringBooking = self::BRING_BOOKING_API;


    private $_customersCache = array();

    public function getCustomers($getFromCache  = true ){
        if($this->_customersCache && $getFromCache) return $this->_customersCache;

        try{
            $request = $this->request('get',$this->_apiBringCustomers);
            $json = json_decode($request->getBody(),true);
            $this->_customersCache = $json['customers'];
            return $this->_customersCache;

        }catch(RequestException $e){
            throw new BookingClientException("Could not fetch customers from Bring API. Are Api key details correct.",null,$e);
        }
    }
    // TODO  bookshipment

    public function bookShipment (BookingRequest $req) {
        $data = $req->toArray();

        $options = [
            'json' => $data
        ];

        try {
            $request = $this->request('post', $this->_apiBringBooking, $options);
            $json = json_decode($request->getBody(), true);
            return $json;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $resp = $e->getResponse();
            $json = json_decode($resp->getBody(), true);
            $errors = [];

            if (isset($json['consignments'])) {
                foreach($json['consignments'] as $consignment) {
                    foreach ($consignment['errors'] as $error) {
                        $errors[] = $error;
                    }
                }
            }
            $ex = new BookingClientException("Got error from Bring API.", null, $e);
            $ex->setErrors($errors);
            throw $ex;
        }
    }


    public function setApiBringCustomers($apiBringCustomers)
    {
        $this->_apiBringCustomers = $apiBringCustomers;
        return $this;
    }

    public function setApiBringBooking($apiBringBooking)
    {
        $this->_apiBringBooking = $apiBringBooking;
        return $this;
    }

}
