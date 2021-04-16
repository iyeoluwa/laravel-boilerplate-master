<?php


namespace App\Services\Bring\Contract\Tracking;


use App\Services\Bring\Contract\ApiEntity;
use Markantnorge\Bring\API\Contract\ContractValidationException;

class TrackingRequest extends ApiEntity
{
static  private $_LANGUAGES = ['no','en','sv','da'];

protected $_data = [
    'q' => null,
    'lang'=>null
];


/**
*Searches for given query
 * @param $query Reference, pacakage number, shipment number to search for
 * @return $this
 */
public function setQuery($query){
    return $this->setData('q',$query);
}

public function setLanguage($language){
    if(!in_array($language, self::$_LANGUAGES)){
        throw new \InvalidArgumentException("Invalid language '$language' . Valid languages are:".implode(',',self::$_LANGUAGES));

    }
    return $this->setData('lang',$language);
}

public function validate(){
    if (!$this->getData('q')){
        throw new ContractValidationException(('Tracking Request requires "q" attribute to be set '));
    }
}

}
