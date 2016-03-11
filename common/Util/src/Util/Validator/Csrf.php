<?php
namespace Util\Validator;
use Zend\Math\Rand;

class Csrf extends \Zend\Validator\Csrf
{
    /**
     * Generate CSRF token with sha256
     *
     * Generates CSRF token and stores both in {@link $hash} and element
     * value.
     *
     * @return void
     */
    protected function generateHash()
    {
        $tokenValue = $this->getSalt() . Rand::getBytes(32) .  $this->getName();
        $token = hash("sha256", $tokenValue);

        $this->hash = $this->formatHash($token, $this->generateTokenId());

        $this->setValue($this->hash);
        $this->initCsrfToken();
    }
    
    public function clearStoragedValues() 
    {
        $session = $this->getSession();
        if(!empty($session->tokenList)) {
            foreach ($session->tokenList as $key => $value) {
                unset($session->tokenList[$key]);
            }
        }
    }
}
