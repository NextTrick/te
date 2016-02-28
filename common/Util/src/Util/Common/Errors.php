<?php
namespace Util\Common;

class Errors
{
    const ERROR_DEFAULT = 0;
    
    protected static $_errors = array(
        0 => 'Internal server error, please try again.',
        500 => 'Error 500: Internal server error, please try again.',
        2033 => 'Error 2033: Invalid Token. Please contact Bongo.',
        2032 => 'Error 2032: Invalid Domain. Please contact Bongo.',
        2029 => 'Error 2029: Invalid access to Order data, please try again.',
    );
    
    public static function get($code)
    {
        if (isset(self::$_errors[$code])) {
            return self::$_errors[$code];
        }
        
        return self::$_errors[self::ERROR_DEFAULT];
    }
}
