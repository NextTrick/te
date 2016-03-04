<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;

use Api\Model\Gateway\Carrier\Ws\Base\BaseWs;

class DhlWs extends BaseWs
{
    public $config;
            
    public $params;
    
    const ERROR_GENERIC_CODE = 500;
    
    const ERROR_GENERIC_MESSAGE = 'Error de conexión';

    public function __construct($config, $params) {
        $this->config = $config;
        $this->params = $params;
    }

    public function getByTrackingNumber($trackingNumber) 
    {
        $params = array(
            'messageTime' => date('c'),
            'messageReference' => str_pad(hexdec(uniqid()), 28, '0', STR_PAD_RIGHT),
            'siteId' => $this->config['siteId'],
            'password' => $this->config['password'],
            'languageCode' => 'es',
            'AWBNumber' => $trackingNumber,
        );
       
        $url = $this->config['host'] . 'XMLShippingServlet';
        $xml = $this->getViewXml('single-dhl.xml', $params);
        return $this->send($url, $xml);
    }

    public function getMultitracking() {
        
    }
    
    public function getViewXml($path, $data = array())
    {
        $resolver = new TemplatePathStack(array(
            'script_paths' => array(__DIR__ . '/Dhl/xml')
        ));
        
        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);
        $view = new ViewModel();
        $view->setTemplate($path);
        $view->setVariables($data);
        
        return $renderer->render($view);
    }

    private function send($url, $xml) {
        $response = array(
            'success' => false,
            'error' => array(
                'code' => self::ERROR_GENERIC_CODE,
                'message' => self::ERROR_GENERIC_MESSAGE
                )
            );
        try {
            $this->client = new \Zend\Http\Client(null, array(
                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                'curloptions' => array(
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 120,
                ),
            )); 
            $this->client->setUri($url);
            $this->client->setMethod('POST');
            $this->client->setRawBody($xml);
            $responseHttp = $this->client->send();
            $responseBody = $this->parseXml($responseHttp->getBody());
            if(!empty($responseBody['AWBInfo']['Status'])){
                $response = array(
                    'success' => true,
                    'data' => $responseBody
                ); 
            }
        } catch (\Exception $e) {
           $response = array(
            'success' => false,
            'error' => array(
                'code' => $e->getCode(),
                'message' => $e->getMessage()
                )
            ); 
            echo $e->getTraceAsString();
        }
        return $response;
    }
    
    protected function parseXml($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        return json_decode(json_encode($xml), TRUE);
    }

}
