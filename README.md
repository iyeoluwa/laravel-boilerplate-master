## This readme teaches you on how to use the bring api

### the api main folder is located in the app directory > services directory > bring api
### The Booking Api

`use App\Services\Bring\API\Client\BookingClient;
use App\Services\Bring\API\Contract\Booking;
use App\Services\Bring\API\Data\BringData;

These 3 variable credentials is provided via the My Bring login interface. (it is advisible that you put the credentials in your .env file)
$bringUid = getenv('BRING_UID');
$bringApiKey = getenv('BRING_API_KEY');
$bringCustomerNumber = getenv('BRING_CUSTOMER');



$bringTestMode = true; // Setting this to false will send orders to Bring! Careful. This is for testing purposes.

$weight = 1; // 1 kg.
$length = 31;
$width = 21;
$height = 6;

$bringProductId = BringData::PRODUCT_SERVICEPAKKE;



// See http://developer.bring.com/api/booking/ ( Authentication section ) . You will need Client id, api key and client url.
$credentials = new App\Services\bring\API\Client\Credentials("http://mydomain.no", $bringUid, $bringApiKey);

// Create a booking client.
$client = new BookingClient($credentials);



// Send package in 5 hours..
$shipDate = new \DateTime('now');
$shipDate->modify('+5 hours');



// What bring product we want to use for shipping the package(s).

$bringProduct = new Booking\BookingRequest\Consignment\Product();
$bringProduct->setId($bringProductId);
$bringProduct->setCustomerNumber($bringCustomerNumber);

// Create a new package.

$consignmentPackage = new Booking\BookingRequest\Consignment\Package();
$consignmentPackage->setWeightInKg($weight);
$consignmentPackage->setDimensionHeightInCm($height);
$consignmentPackage->setDimensionLengthInCm($length);
$consignmentPackage->setDimensionWidthInCm($width);


// Create a consignment

$consignment = new Booking\BookingRequest\Consignment();
$consignment->addPackage($consignmentPackage);
$consignment->setProduct($bringProduct);
$consignment->setShippingDateTime($shipDate);


// Recipient

$recipient = new Booking\BookingRequest\Consignment\Address();
$recipient->setAddressLine("Veien 32");
$recipient->setCity("Bergen");
$recipient->setCountryCode("NO");
$recipient->setName("Privat person");
$recipient->setPostalCode(5097);
$recipient->setReference("Customer-id-in-DB");
$consignment->setRecipient($recipient);


// Sender

$sender = new Booking\BookingRequest\Consignment\Address();
$sender->setAddressLine("Veien 32");
$sender->setCity("Bergen");
$sender->setCountryCode("NO");
$sender->setName("Min bedrift");
$sender->setPostalCode(5097);
$contact = new Booking\BookingRequest\Consignment\Contact();
$contact->setEmail('mycompany@test.com');
$contact->setPhoneNumber('40000000');
$sender->setContact($contact);
$consignment->setSender($sender);







$request = new Booking\BookingRequest();
$request->addConsignment($consignment);
$request->setTestIndicator($bringTestMode);

try {


    echo "Using Bring UID: $bringUid, Key: $bringApiKey, Customer number: $bringCustomerNumber\n";

    $result = $client->bookShipment($request);
    print_r($result);

// Catch response errors.
} catch (App\Services\Bring\API\Client\BookingClientException $e) {
print_r($e->getErrors());
throw $e;
// Catch errors that relates to the contract / request.
} catch (App\Services\Bring\API\Contract\ContractValidationException $e) {
throw $e;
}
`
## Booking Api Get Customers 

use App\Services\Bring\API\Client\BookingClient;

// See http://developer.bring.com/api/booking/ ( Authentication section ) . You will need Client id, api key and client url.
$credentials = new App\Services\Bring\API\Client\Credentials("http://mydomain.no", getenv('BRING_UID'), getenv('BRING_API_KEY'));
$client = new BookingClient($credentials);

print_r($client->getCustomers());









### Tracking APi



use App\Services\Bring\API\Contract\Tracking;

// Mybring credentials are not required can be null. See rate limiting in bring developer docs.
$bringUid = getenv('BRING_UID') ?: null;
$bringApiKey = getenv('BRING_API_KEY') ?: null;

$client = new App\Services\Bring\API\Client\TrackingClient(new App\Services\Bring\API\Client\Credentials("http://mydomain.no", $bringUid, $bringApiKey));




$request = new Tracking\TrackingRequest();
$request->setQuery('TESTPACKAGELOADEDFORDELIVERY');
$request->setLanguage(App\Services\Bring\API\Data\BringData::LANG_NORWEGIAN);


try {

    $trackingInfo = $client->getTracking($request);

    foreach ($trackingInfo['consignmentSet'] as $consignmentSet) {
        // There was an error in this consignment set.
        if (isset($consignmentSet['error'])) {

            print_r($error);

        } else {
            print_r($consignmentSet);
        }
    }

} catch (App\Services\Bring\API\Client\TrackingClientException $e) {

    throw $e->getRequestException();

} catch (App\Services\Bring\API\Contract\ContractValidationException $e) {
throw $e;
}





### Shipping Guide Api



use App\Services\Bring\API\Contract\ShippingGuide;
use App\Services\Bring\API\Data\ShippingGuideData;
use App\Services\Bring\API\Data\BringData;


// See http://developer.bring.com/api/booking/ ( Authentication section ) . You will need Client id, api key and client url.
$client = new App\Services\BringAPI\Client\ShippingGuideClient(new App\Services\Bring\API\Client\Credentials("http://mydomain.no"));

$request = new ShippingGuide\PriceRequest();

$request->setFromCountry('NO');
$request->setFrom('5097');

$request->setToCountry('NO');
$request->setTo('5155');

$request->setWeightInGrams(1500);

$request->addAdditional(ShippingGuideData::EVARSLING); // Makes it cheaper, and environment friendly! :)


// Set possible shipping products
$request->addProduct(BringData::PRODUCT_SERVICEPAKKE)
->addProduct(BringData::PRODUCT_MINIPAKKE)
->addProduct(BringData::PRODUCT_PA_DOREN);


// If we use EDI..
$request->setEdi(true);



try {

    $prices = $client->getPrices($request);

    print_r($prices);

} catch (App\Services\Bring\API\Client\ShippingGuideClientException $e) {
throw $e; // just re-throw for testing.

} catch (App\Services\Bring\API\Contract\ContractValidationException $e) {
throw $e;
}
