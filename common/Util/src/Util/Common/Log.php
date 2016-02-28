<?php

namespace Util\Common;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream as WriterStream;

class Log {
    
    private static $_logs = array();

    /**
     * 
     * @param string $subject
     * @param string|Exception|array $message
     * @param string|array $emails
     * @param \Bongo\Util\Mail $serviceMail
     * @return boolean
     */
    public static function push($subject, $message, $emails = null, \Bongo\Util\Mail $serviceMail = null) {
        $salt = "<br><br>";
        if ($emails === null) {
            $salt = "\n";
        } elseif ($serviceMail == null) {
            throw new \Exception('Must be enter $serviceMail instance of \Bongo\Util\Mail');
        }
        
        if (is_array($message) || is_object($message)) {
            if ($message instanceof \Exception) {
                $message = $message->getMessage() . " in " . $message->getFile() . " line " . $message->getLine() . " [" . gethostname() . "]" 
                    . $salt . $message->getTraceAsString();
            } else {
                $message = print_r($message, true);
            }
        }
        
        $idLog = uniqid('log');
        
        if ($emails != null) {
            if (!is_array($emails)) {
                $emails = explode(',', $emails);
            }
            
            try {
                $serviceMail->notificarError("[$idLog] $subject :: " . date('d/m/Y H:i:s') . ' - ', "<pre>" . $message . "</pre>", $emails, true);
                return $idLog;
            } catch (Exception $e) {
                
            }
        }    
        
        try {
            $log = self::factory($subject);
            $log->log(Logger::INFO, "[$idLog] \n $message");
        } catch (Exception $e) {
            return false;
        }    
        
        return $idLog;
    }

    /**
     * 
     * @param string $name
     * @return Logger
     */
    public static function factory($name) {
        if (!defined('APP_PATH')) {
            throw new Exception('Undefined APP_PATH in public/index.php >> "define(\'APP_PATH\', dirname(__DIR__));"');
        }
        
        if (!isset(self::$_logs[$name])) {
            self::$_logs[$name] = new Logger();

            $logFile = APP_PATH . '/data/log/' . $name . '-' . date('Ymd') . '.log';
            
            chmod($logFile, 0777);
            $writer = new WriterStream($logFile);
            self::$_logs[$name]->addWriter($writer);
            chmod($logFile, 0777);
        }
        return self::$_logs[$name];
    }

}
