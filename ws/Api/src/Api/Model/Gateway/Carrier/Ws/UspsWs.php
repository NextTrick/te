<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;
use Zend\Http\Client;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;

class UspsWs extends BaseWs
{   
    private $request = array();
    
    /**     
     * @var Client 
     */
    public $client;
    
    protected $userId;
    
    protected $password; 
    
    const STATUS_ERROR = 'Error';    
    const STATUS_SUCCESS = 'Success';
    
    protected $errorStatus = array(
        self::STATUS_ERROR,
    );
         
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
            $response = $this->client->send();
            var_dump($this->client->getLastRawRequest()); exit;
            if ($response->isSuccess()) {
                $responseXml = $this->parseXml($response->getBody());                
                $responseData['data'] = $responseXml;
            } else {
                $responseXml = $this->parseXml($response->getBody());
                $responseData = $this->parseError($responseXml);
                $responseData['success'] = false;
            }           
        } catch (\Exception $e) {
            $responseData['success'] = false;   
            $responseData['exception'] = $this->errorCodeGeneric;
            $responseData['message'] = $e->getMessage();
        }
        var_dump($responseData); exit;
        return $responseData;
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
        
        if (!empty($response->Errors->ErrorDetail->PrimaryErrorCode)) { 
            $tempError = array('');
//            foreach ($response->ErrorDetail as $notification) {                
                $tempError['code'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Code;
                $tempError['message'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Description;
                $tempError['aditionalMessage'][] = $response->Errors->ErrorDetail->PrimaryErrorCode->Digest;              
//            }
            
            $returnError = array();
            $returnError['code'] = implode('|', $tempError['code']);
            $returnError['message'] = implode('|', $tempError['message']);
            $returnError['aditionalMessage'] = implode('|', $tempError['aditionalMessage']);
            
            $return['error'] = $returnError;
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
        $xml = simplexml_load_string($xmlString);
        return json_decode(json_encode($xml), TRUE);
    }
}