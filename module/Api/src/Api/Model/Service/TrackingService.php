<<<<<<< HEAD
<?php
namespace Api\Model\Service;

use Api\Model\Gateway\CarrierGateway;

class TrackingService
{
    public function getTracking()
    {
        $this->getCarrierTracking()->getTracking();
    }
    
    public function getCarrierTracking()
    {
        $carrier = 'dhl';
        return CarrierGateway::getCarrierService($carrier);
    }
=======
<?php
namespace Api\Model\Service;

use Api\Model\Gateway\CarrierGateway;


class TrackingService
{
    public $serviceLocator;
    
    public function __construct($serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }
    public function getTracking()
    {
        return $this->getCarrierTracking()->getTracking();
    }
    
    public function getCarrierTracking()
    {
        $carrier = 'dhl';
        $config = $this->serviceLocator->get('config');
        return CarrierGateway::getCarrierService(
                    $carrier, 
                    $config[$carrier], 
                    $this->serviceLocator
                );
    }
>>>>>>> 356904467f1e0feca49f578fc53aa97a5e23cd28
}