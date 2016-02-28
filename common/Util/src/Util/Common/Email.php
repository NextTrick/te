<?php

namespace Util\Common;

use Zend\Mail\Message;
use Zend\Mime\Part;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Util\Common\EmailTemplate;

class Email
{
    const LOGIGAL = 1;
    const APPLICATION = 2;
        
    private $emails;
    private $config;
           
    private static $instance;
    
    /**     
     * Variable temporal
     * @var bool 
     */
    public $checkout = false;
    
    /**
     *
     * @var \Zend\Mail\Transport\Smtp 
     */
    public $smtp;    
    public $emailTemplate;
    public $domain;
    
    public function __construct($config) 
    {                
        if (!empty($config['bongo_server'])) {
            $this->checkout = true;
            $this->config = $config['email'];
            $this->emails = $config['bongo_server']['emails'];
            $this->domain = $config['bongo_server']['server']['content'];
        } else {
            $this->config = $config['mail'];
            $this->emails = $config['emails'];
            $this->domain = $config['view_manager']['base_path'];
        }
        
        $this->emailTemplate = new EmailTemplate();
        
        $this->setSmtp();    
    }

    private static function _getInstance()
    {
        $config = \Util\Common\Config::get();
        
        if (is_null(static::$instance)) {
            static::$instance = new Email($config);
        }
        
        return static::$instance;
    }
    
    /**
     * Envia un mensaje para reportar un error lógico en el sistema al administrador.
     * y a los desarroladores.
     * 
     * @param Exception $e
     * @param String $subject
     * 
     * @return void
     * 
     */       
    public static function reportException(\Exception $e, $subject = '', $extraData = array(), $type = 2)
    {
        $emailService = self::_getInstance();

        $body = '';
        $key = '';

        switch ($type) {
            case self::LOGIGAL:
                $body = $emailService->emailTemplate->getLogicalExceptionTemplate($e, $extraData);
                $key = 'Logical';
                break;
            case self::APPLICATION:
                $body = $emailService->emailTemplate->getApplicationExceptionTemplate($e, $extraData);
                $key = 'Exception';
                break;
        }

        if ($subject == '') {
            $subject = $emailService->domain . " [$key] ";
            $subject .= isset($emailService->config['subject']) ? $emailService->config['subject'] : " Error en la aplicación.";
        } else {
            $subject = $emailService->domain . " [$key] " . $subject;            
        }
        
        try {
            $log = new \Util\Model\Service\ErrorService();                        
            $log->logException($e);
            $emailService->send($subject, $body, $emailService->emails['admin'], true, $emailService->emails['developers']);
            
        } catch (\Exception $e) {            
            $log = new \Util\Model\Service\ErrorService();            
            $log->logException($e);            
        }         
    }
    /**
     * Envia un mensaje para reportar un debug en casos especificos de debug.
     * 
     * @param Array $data
     * @param Exception $e | null
     * @param String $subject
     * 
     * @return void
     * 
     */
    public static function reportDebug($data, \Exception $e = null, $subject = '')
    {
        $config = \Util\Common\Config::get();

        if (!empty($config['error']['internal_debug'])) {
            return;
        }

        $emailService = self::_getInstance();

        if ($subject == '') {
            $subject = $emailService->domain . ' [Debug]';
            $subject .= isset($emailService->config['subject']) ? $emailService->config['subject'] : " Error en la aplicación.";
        } else {
            $newSubject = $emailService->domain . ' [Debug] ' . $subject;
            $subject = $newSubject;
        }
 
        $body = $emailService->emailTemplate->getDebugTemplate($data, $e);
        
        try {
             
            if (!empty($e)) {
                $log = new \Util\Model\Service\ErrorService();                    
                $log->logException($e, 'debug');
            }
            $emailService->send($subject, $body, $emailService->emails['admin'], true, $emailService->emails['developers']);
        } catch (\Exception $e) {   
            $log = new \Util\Model\Service\ErrorService();            
            $log->logException($e, 'debug');
        }
    }
    /**
     *  Envia un correo de caracter particular a una direccion especifica.
     *  
     * @param String $subject Nombre del correo
     * @param String $body Cuerpo del correo
     * @param String|Array $to Para quien va el correo
     * @param bool $html (Opcional) Soporte de mensaje HTML (false)
     * @param String|null $bcc (Opcional) Copias ocultas (correo1, correo2, correo3)
     * @param String|null $fromName (Opcional) Nombre del remitente
     *  @param String|null $fromEmail (Opcional) Email del remitente
     * 
     * @return void
     */
    public static function send($subject, $body, $to, $html = false, $bcc = null, $fromName = null, $fromEmail = null)
    {        
        $emailService = self::_getInstance();
                
        $message = new Message();
        $message->addTo($to);
        $message->setSubject($subject);

        $emailService->setBcc($message, $bcc);

        $emailService->setFrom($message, $fromEmail, $fromName);

        $emailService->setBody($message, $body, $html);

        $emailService->smtp->send($message);                
    }

    public function setSmtp()
    {        
        $this->smtp = new Smtp();
        if ($this->checkout == true) {
            $smptOptions = array(
                'host' => $this->config['smtp_host'],
                'port' => $this->config['smtp_port'],
            );

            if (isset($this->config['use_smtp']) && $this->config['use_smtp'])  {
                $smptOptions['connection_class'] = $this->config['smtp_connection_class'];
                $smptOptions['connection_config'] = array(
                    'username' => $this->config['smtp_username'],
                    'password' => $this->config['smtp_password'],
                    'ssl' => isset($this->config['smtp_ssl']) ? $this->config['smtp_ssl'] : 'tls',
                );
            }

            $this->smtp->setOptions(new SmtpOptions($smptOptions));
        } else {
            $options = $this->config['transport']['options'];
            $smptOptions['host'] = $options['host'];
            $smptOptions['port'] = $options['port'];
            
            if (isset($options['use_smtp']) && $options['use_smtp']) {
                $smptOptions['connection_class'] = $options['connection_class'];
                $smptOptions['connection_config'] = $options['connection_config'];
            }

            $this->smtp->setOptions(new SmtpOptions($smptOptions));
        }
    }

    public function setBcc(Message $message, $bcc)
    {
        if (!empty($bcc)) {
            $bccs = explode(',', $bcc);

            foreach ($bccs as $email) {
                $message->addBcc(trim($email));
            }
        }
    }

    public function setFrom(Message $message, $fromEmail = null, $fromName = null)
    {
        if ($fromEmail == null) {
            if ($this->checkout == true) {
                $fromName = $this->config['from_nick_name'];
                $fromEmail = $this->config['from'];
            } else {
                $fromEmail = $this->config['fromEmail'];
                $fromName = $this->config['fromName'];
            }
                                               
            $message->setFrom($fromEmail, $fromName);            
        } else {
            $message->setFrom($fromEmail,
                $fromName);
        }        
    }

    public function setBody(Message $message, $textBody, $html)
    {
        if ($html) {
            $body = new Part($textBody);
            $body->type = 'text/html';
        } else {
            $body = new Part($textBody);
            $body->type = 'text/plain';
        }

        $mimeMessage = new MimeMessage();
        $mimeMessage->setParts(array($body));

        $message->setBody($mimeMessage);
    }   
}
