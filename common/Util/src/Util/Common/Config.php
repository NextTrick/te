<?php

namespace Util\Common;

class Config
{
    /**
     * Obtiene la configuracion de acuerdo al enviroment.
     */
    public static function get()
    {
        $env = static::getEnv();
        $local =  include APP_PATH . "/config/autoload/$env.php";
        $global = include APP_PATH . "/config/autoload/global.php";
                
        return array_replace_recursive($global, $local);
    }

    /**
     * Retorna el enviroment.
     */
    public static function getEnv()
    {
        $path = APP_PATH . "/config/autoload/";
        if (file_exists($path . 'local.php')) {
            return 'local';
        } else if (file_exists($path . 'local-prod.php')) {
            return 'local-prod';
        } else {
            return 'global';
        }
    }
}