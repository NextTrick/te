<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;
use Zend\Http\Client;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;

class UspsWs extends BaseWs
{  
    /**     
     * @var Client 
     */
    public $client;
    
    protected $userId;
    
    protected $password;
    
    public function __construct($config)
    {
        $this->userId = $config['userId'];
        $this->password = $config['password'];
        $url = $config['url'];                      
        $this->client = new Client($url, array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 120,
            ),
        ));
    }
        
    public function getByTrackId($trackId)
    {                         
        $responseData = array('success' => true);         
        $requestParamesters = $this->getRequetParameters($trackId);        
                       
        $this->client->setMethod('GET');
        $this->client->setParameterGet($requestParamesters);  
        
        try {
//            $response = $this->getResponse();            
            $response = $this->client->send();           
            if ($response->isSuccess()) {
//            if (true) {
                $responseXml = $this->parseXml($response->getBody());
//                $responseXml = $this->parseXml($response);                             
                if (!empty($responseXml['TrackResponse']['TrackInfo']['TrackSummary'])) {
                    $responseData['data'] = $responseXml;
                } else {
                    $responseData = $this->parseError($responseXml);                    
                }   
            } else {
                $responseXml = $this->parseXml($response->getBody());
                $responseData = $this->parseError($responseXml);                
            }           
        } catch (\Exception $e) {
            $responseData = $this->getGenericErrorData();                              
            $responseData['error']['message'] = $e->getMessage();
            $responseData['error']['exception'] = $e->getTraceAsString();
        }
        
        return $responseData;
    }
    
    public function getResponse()
    {
        $string = '<TrackResponse>
    <TrackInfo ID="5551212699300000962610">
       <Class>USPS Retail Ground&amp;#153;</Class>
       <ClassOfMailCode>BP</ClassOfMailCode>
       <DestinationCity>KBEA</DestinationCity>
       <DestinationState>TX</DestinationState>
       <DestinationZip>12345</DestinationZip>
       <EmailEnabled>true</EmailEnabled>
       <KahalaIndicator>false</KahalaIndicator>
       <MailTypeCode>DM</MailTypeCode>
       <MPDATE>2016-01-08 10:34:04.000000</MPDATE>
       <MPSUFFIX>412725500</MPSUFFIX>
       <OriginCity>LAKE CHARLES</OriginCity>
       <OriginState>IL</OriginState>
       <OriginZip>12345</OriginZip>
       <PodEnabled>false</PodEnabled>
       <RestoreEnabled>false</RestoreEnabled>
       <RramEnabled>false</RramEnabled>
       <RreEnabled>false</RreEnabled>
       <Service>USPS Tracking&lt;SUP&gt;&amp;#174;&lt;/SUP&gt;</Service>
       <ServiceTypeCode>346</ServiceTypeCode>
       <Status>Arrived at facility</Status>
       <StatusCategory>In Transit</StatusCategory>
       <StatusSummary>Your item arrived at our USPS facility in COLUMBUS, OH 43218 on January 6, 2016 at 10:45 pm. The item is currently in transit to the destination.</StatusSummary>
       <TABLECODE>T</TABLECODE>
       <TrackSummary>
          <EventTime>10:45 pm</EventTime>
          <EventDate>January 6, 2016</EventDate>
          <Event>Arrived at USPS Facility</Event>
          <EventCity>COLUMBUS</EventCity>
          <EventState>OH</EventState>
          <EventZIPCode>43218</EventZIPCode>
          <EventCountry />
          <FirmName />
          <Name />
          <AuthorizedAgent>false</AuthorizedAgent>
          <EventCode>10</EventCode>
       </TrackSummary>
       <TrackDetail>
          <EventTime>9:10 am</EventTime>
          <EventDate>January 6, 2016</EventDate>
          <Event>Acceptance</Event>
          <EventCity>LAKE CHARLES</EventCity>
          <EventState>IL</EventState>
          <EventZIPCode>12345</EventZIPCode>
          <EventCountry />
          <FirmName />
          <Name />
          <AuthorizedAgent>false</AuthorizedAgent>
          <EventCode>03</EventCode>
       </TrackDetail>
    </TrackInfo>
 </TrackResponse>';
        
        return $string;
    }
    
    public function getGenericErrorData()
    {
        return array('success' => false,
                     'error' => array('code' => self::ERROR_GENERIC_CODE,
                                      'message' => self::ERROR_GENERIC_MESSAGE));
    }
    
    protected function parseError($response)
    {
        $return = $this->getGenericErrorData();        
        if (!empty($response['TrackResponse']['TrackInfo']['Error'])) {
            $return['code'] = $response['TrackResponse']['TrackInfo']['Error']['Number'];
            $return['message'] = $response['TrackResponse']['TrackInfo']['Error']['Description'];                                 
        }
        
        return $return;
    }
    
    public function getRequetParameters($trackId)
    {
        $data = array(
            'userId' => $this->userId,
            'password' => $this->password,
            'clientIp' => \Util\Common\Util::getIpClient(),
            'trackId' => $trackId,
        );
        $xml = $this->getViewXml('track-confirm.xml', $data);
        
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . $xml;
        
        return array(
            'XML' => $xml,
        );
    }
    
    public function getViewXml($file, $data = array())
    {
        $resolver = new TemplatePathStack(array(
            'script_paths' => array(__DIR__ . '/Usps/Xml')
        ));
        
        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);
        $view = new ViewModel();
        $view->setTemplate($file);
        $view->setVariables($data);
        
        return $renderer->render($view);
    }
      
    protected function parseXml($xmlString)
    {
        $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/',
                '' , $xmlString) . '</root>');
//        $xml = simplexml_load_string($xmlString);
        //var_dump($xml); exit;
        return json_decode(json_encode($xml), TRUE);
    }
}