<?php


namespace App\Services\Bring\API\Contract\Booking\BookingRequest\Consignment;

use App\Services\Bring\Contract\ApiEntity;
use App\Services\Bring\Contract\ContractValidationException;
class Contact extends ApiEntity
{

    protected $_data = [
        'name' => null,
        'email' => null,
        'phoneNumber' => null
    ];

    public function setName ($name) {
        return $this->setData('name', $name);
    }

    public function setEmail ($email) {
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('$email must be a valid email.');
        }
        return $this->setData('email', $email);
    }


    public function setPhoneNumber ($phoneNumber) {
        return $this->setData('phoneNumber', $phoneNumber);
    }



    public function validate()
    {

        $required_fields = ['email', 'phoneNumber'];

        foreach ($required_fields as $f) {
            if (!$this->getData($f)) {
                throw new ContractValidationException('BookingRequest\Consignment\Contact requires "'.$f.'" to be set.');
            }
        }

    }
}
