<?php


namespace App\Services\Bring\API\Client;

use App\Services\Bring\Contract\Tracking\TrackingRequest;
use GuzzleHttp\Exception\RequestException;
use Markantnorge\Bring\API\Client\TrackingClientException;


class TrackingClient extends Client
{
    const BRING_TRACKING  = 'https://tracking.bring.com/tracking.json';

    const MYBRING_TRACKING = 'https://www.mybring.com/tracking/api/tracking.json';

    protected $_apiTracking = self::BRING_TRACKING;
    protected $_apiMybringTracking  = self::MYBRING_TRACKING;

    public function getTracking(TrackingRequest $request){
        $query = $request->toArray();
        $url =  $this->getTrackingApiUrl();

        $options = [
          'query'=>$this->getQueryParams($query)
        ];

        try{
            $request = $this->request('get',$url,$options);
            $json  = json_decode($request->getBody(),true);
            return $json;
        }catch(RequestException $e){
            throw new TrackingClientException("Could not track package.",null,$e);
        }
    }

    protected  function getTrackingApiUrl(){
        return $this->_credentials->hasAuthorization() ? $this->_apiMybringTracking : $this->_apiTracking;
    }

    public function setApiTracking($url){
        $this->_apiTracking = $url;
        return $this;
    }

    public function setApiMybringTracking($url)
    {
        $this->_apiMybringTracking = $url;
        return $this;
    }

}
