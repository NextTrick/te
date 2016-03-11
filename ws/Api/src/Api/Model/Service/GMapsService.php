<?php
namespace Api\Model\Service;

class GMapsService
{
    const MAPS_HOST = 'maps.googleapis.com';
    /**
     * Latitude 
     * 
     * @var double
     */
    private $_latitude;
    /**
     * Longitude 
     *
     * @var double
     */
    private $_longitude;
    /**
     * Address 
     *
     * @var string
     */
    private $_address;
    /**
     * Country name 
     *
     * @var string
     */
    private $_countryName;
    /**
     * Country name code
     *
     * @var string
     */
    private $_countryNameCode;
    /**
     * Administrative area name
     *
     * @var string
     */
    private $_administrativeAreaName;
    /**
     * Postal Code
     *
     * @var string
     */
    private $_postalCode;
    /**
     * Google Maps Key
     *
     * @var string
     */
    private $_key;
    /**
     * Base Url
     *
     * @var string
     */
    private $_baseUrl;
    
    public $serviceLocator;


    public function __construct($serviceLocator, $key) 
    {
        $this->serviceLocator = $serviceLocator;
        $this->_key= $key;
        $this->_baseUrl= "http://" . self::MAPS_HOST . "/maps/api/geocode/json?"; //key=" . $this->_key;
    }
    
    /**
     * getInfoLocation
     *
     * @param string $address
     * @param string $city
     * @param string $state
     * @return boolean
     */
    public function getInfoLocation ($address) {
        if (!empty($address)) {
            return $this->_connect($address);
        }
        return false;    
    }
    /**
     * connect to Google Maps
     *
     * @param string $param
     * @return boolean
     */
    private function _connect($param) {
        $key = sha1($param['search']); 
        $carrierCoordenadas = $this->getCarrierCoordinatesService()
                        ->getRepository()->getByCode($key);
        
        if(empty($carrierCoordenadas)) {
            $request_url = $this->_baseUrl . "address=" . urlencode($param['search']);
            $client = new \Zend\Http\Client();
            $client->setUri($request_url)
                    ->setMethod('GET');
            $responseHttp = $client->send();
            if($responseHttp->isOk()) {
                $responseBody = json_decode($responseHttp->getBody(), TRUE);
                if(!empty($responseBody['results'])){
                    $result = reset($responseBody['results']);
                    $this->_latitude = $result['geometry']['location']['lat'];
                    $this->_longitude = $result['geometry']['location']['lng'];
                    $this->getCarrierCoordinatesService()
                        ->getRepository()->insert(
                                array(
                                    'carrierId' => $param['carrierId'],
                                    'latitud' => $this->_latitude,
                                    'longitud' => $this->_longitude,
                                    'code' => $key,
                                    'nameCoordinate' => $param['search'],
                                )
                            );
                }
                
                return true;  
            } else {
                return false;
            }
        }
        else {
            $this->_latitude = $carrierCoordenadas['latitud'];
            $this->_longitude = $carrierCoordenadas['longitud'];
            return true;
        }
    }
    /**
     * get the Postal Code
     *
     * @return string
     */
    public function getPostalCode () {
        return $this->_postalCode;
    }
	/**
     * get the Address
     *
     * @return string
     */
    public function getAddress () {
        return $this->_address;
    }
	/**
     * get the Country name
     *
     * @return string
     */
    public function getCountryName () {
        return $this->_countryName;
    }
	/**
     * get the Country name code
     *
     * @return string
     */
    public function getCountryNameCode () {
        return $this->_countryNameCode;
    }
	/**
     * get the Administrative area name
     *
     * @return string
     */
    public function getAdministrativeAreaName () {
        return $this->_administrativeAreaName;
    }
    /**
     * get the Latitude coordinate
     *
     * @return double
     */
    public function getLatitude () {
        return $this->_latitude;
    }
    /**
     * get the Longitude coordinate
     *
     * @return double
     */
    public function getLongitude () {
        return $this->_longitude;
    }
    /**
     * 
     * @return \Carrier\Model\Service\CarrierCoordinatesService
     */
    private function getCarrierCoordinatesService()
    {
        return $this->serviceLocator->get('Model\CarrierCoordinatesService');
    }
}