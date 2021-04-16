<?php


namespace App\Services\Bring\Contract\Booking\BookingRequest;


use App\Services\Bring\Contract\ApiEntity;
use App\Services\Bring\Contract\ContractValidationException;

class Consignment extends ApiEntity
{


    protected $_data = [
        'shippingDateTime' => null,
        'product' => null,
        'purchaseOrder' => null,
        'correlationId' => null,
        'parties' => [
            'sender' => null,
            'recipient' => null,
            'pickupPoint' => null
        ],
        'packages' => []
    ];


    public function setShippingDateTime (\DateTime $dateTime) {
        return $this->setData('shippingDateTime', $dateTime->format('Y-m-d\TH:i:s'));
    }

    public function setProduct(Product $product) {
        return $this->setData('product', $product);
    }

    public function setPurchaseOrder ($purchaseOrder) {
        return $this->setData('purchaseOrder', $purchaseOrder);
    }

    public function setCorrelationId ($correlationId) {
        return $this->setData('correlationId', $correlationId);
    }

    public function setSender (Address $sender) {
        return $this->setPartiesData('sender', $sender);
    }

    public function setRecipient (Address $recipient) {
        return $this->setPartiesData('recipient', $recipient);
    }
    public function setPickupPoint ($pickupPoint) {
        return $this->setPartiesData('pickupPoint', $pickupPoint);
    }

    public function addPackage(Package $consignment) {
        return $this->addData('packages', $consignment);
    }

    public function validate()
    {
        if (!$this->getData('product')) {
            throw new ContractValidationException('BookingRequest\Consignment requires "product" to be set.');
        }

        if (!$this->getData('shippingDateTime')) {
            throw new ContractValidationException('BookingRequest\Consignment requires "shippingDateTime" to be set.');
        }

        if (!$this->getPartiesData('recipient')) {
            throw new ContractValidationException('BookingRequest\Consignment requires "recipient" to be set.');
        }

        if (!$this->getPartiesData('sender')) {
            throw new ContractValidationException('BookingRequest\Consignment requires "sender" to be set.');
        }

        if (!$this->getData('packages')) {
            throw new ContractValidationException('BookingRequest\Consignment requires "packages" to be set.');
        }
    }
    private function setPartiesData($key, $value) {
        if (!isset($this->_data['parties'])) $this->_data['parties'] = [];
        $this->_data['parties'][$key] = $value;
        return $this;
    }

    private function getPartiesData($key) {
        return $this->_data['parties'][$key];
    }

}
