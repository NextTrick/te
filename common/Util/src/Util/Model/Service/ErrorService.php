<?php

namespace Util\Model\Service;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream as WriterStream;

class ErrorService 
{
    const FORMAT_DATE = 'Y-m-d';
    const ERROR_FILE = 'error';
    public $path = '';

    private $_logger;

    public function __construct() 
    {
        $this->path = APP_PATH . '/data/log/';
        $this->_logger = new Logger();
    }

    public function logException(\Exception $e, $type = null) 
    {   
        $ip = \Util\Common\Util::getIpClient();
        $log  = "\n =========================================================================== \n";
        $log .= "\n \n Ip: {$ip}";
        $log .= "\n Mensaje: {$e->getMessage()}";
        $log .= "\n \n Archivo:{$e->getFile()}";
        $log .= "\n \n Linea:{$e->getLine()}";
        $log .= "\n \n Trace: {$e->getTraceAsString()} \n";
        $log .= "\n =========================================================================== \n";
        
        if (!empty($type)) {
            $file = $this->_generateFileName($type);                
        } else {
            $file = $this->_generateFileName(self::ERROR_FILE);                
        }
        
        @chmod($file, 0777);                    
        $writer = $this->_getWriter($file);
        $writer->err($log);
    }
    
    private function _getWriter($file)
    {
       return $this->_logger->addWriter(new WriterStream($file));
    }

    private function _generateFileName($type)
    {
        $date = new \DateTime('NOW');
        $dateFormat = $date->format(self::FORMAT_DATE);
        $file = "{$type}-{$dateFormat}.txt";

        return $this->path . $file;
    }
}