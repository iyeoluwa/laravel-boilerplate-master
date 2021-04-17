<?php
namespace App\Services\Bring\API\Data;


class ShippingGuideData {

    const EVARSLING = 'EVARSLING';
    const POSTOPPKRAV = 'POSTOPPKRAV';
    const LORDAGSUTKJORING = 'LORDAGSUTKJORING';
    const ENVELOPE = 'ENVELOPE';
    const ADVISERING = 'ADVISERING';
    const PICKUP_POINT = 'PICKUP_POINT';
    const EVE_DELIVERY = 'EVE_DELIVERY';



    static public function validAdditionalServices () {
        return [
            self::EVARSLING,
            self::POSTOPPKRAV,
            self::LORDAGSUTKJORING,
            self::ENVELOPE,
            self::ADVISERING,
            self::PICKUP_POINT,
            self::EVE_DELIVERY
        ];
    }
}
