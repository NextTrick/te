<?php
namespace Util\Common;

class String 
{
    static public function createSlug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
          return 'n-a';
        }

        return $text;      
    }
    
    static public function creaeRamdonCode($numeroLetras = 7)
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $cad = "";
        for ($i = 1; $i <= $numeroLetras; $i++) {
            $cad .= substr($str, rand(0, 36), 1);
        }
        return $cad;
    }
    
    static public function prepareForMethod($string, $search = '_', $replace = ' ')
    {        
        return  str_replace(' ', '', ucwords(str_replace($search, $replace, $string)));
    }
    
    /**
     * crea un key para cache
     * 
     * @param array $data // los elementos del array pueden ser: bool|string|null
     * @return null | data
     */
    static public function createCacheKey($data)
    {        
        $cacheKey = null;         
        if (!empty($data)) {            
            foreach ($data as $item) {
                if ($item == null) {
                    $item = 'null';
                }
                
                if (is_bool($item)) {
                    $item = (int) $item;
                }
                
                $cacheKey .= '_' . (string) self::createSlug($item);                
            }
        }
        
        if ($cacheKey != null) {
            return $cacheKey;
        }
        
        throw new Exception('No se puede crear cache key');
    }
    
    /**
     * Corta la cadena enla posición indicada
     * 
     * @param string $descripcion
     * @param int $posicion
     * @param string $extraStr
     * @return string
     */
    public static function cut($string, $posicion, $extraStr = '')
    {        
        if (!empty($string)) {
            if (strlen($string) <= $posicion) {
                $extraStr = '';
            }
            
            $string =  substr($string, 0, $posicion);
        }
                                        
        return $string . $extraStr;        
    }
    
    /**
     * Limpia caracteres extraños
     * 
     * @param string $string
     * @return string
     */
    public static function trim($string)
    {
        if (!empty($string)) {
            //reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
            $string = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]' .
                    '|[\x00-\x7F][\x80-\xBF]+' .
                    '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*' .
                    '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})' .
                    '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S', '?', $string);

            //reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
            $string = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]' .
                    '|\xED[\xA0-\xBF][\x80-\xBF]/S', '?', $string);

            // Remove all none utf-8 symbols
            $string = htmlspecialchars_decode(htmlspecialchars($string, ENT_IGNORE, 'UTF-8'));        
            // remove non-breaking spaces and other non-standart spaces
            $string = preg_replace('~\s+~u', ' ', $string);        
            // replace controls symbols with "?"
            $string = preg_replace('~\p{C}+~u', '?', $string);
        }        
        
        return $string;
    }
    
    /**
     * Reemplaza todos los acentos por sus equivalentes sin ellos
     *
     * @param $string
     *  string la cadena a sanear
     *
     * @return $string
     *  string saneada
     */
    public static function sanear_string($string) {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            ".", " "), '', $string
        );


        return $string;
    }
    
    public static function cortarCadena($cadena,$longitud,$puntosSusp = true){
        if (strlen($cadena) < $longitud) {
            return $cadena;
        } else {                
            $nuevaCadena = substr( strip_tags($cadena),0,strrpos(substr( strip_tags($cadena),0,$longitud)," "));
            if ($nuevaCadena == "") {
               $nuevaCadena = substr( strip_tags($cadena),0,$longitud);
            }
            if ($puntosSusp) {
                $nuevaCadena = $nuevaCadena . '...';
            }
            
            return $nuevaCadena;
        }
    }
    
    /**
     * Método de encriptación - MCRYPT
     * 
     * USO:
     * $key = self::COOKIE_KEY
     * $secret = self::encriptar3DES($key, $cookie)
     * 
     * @param string $key
     * @param string $data
     * 
     * @return string
     */
    public static function encriptar3DES($key, $data)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        $data = str_repeat(' ', $iv_size) . $data;
        $secret = mcrypt_encrypt(MCRYPT_3DES, $key, $data
            , MCRYPT_MODE_CBC, $iv);
        return $secret;
    }

    /**
     * Método de desencriptación - MCRYPT
     * 
     * @param type $key
     * @param type $data
     * 
     * @return string
     */
    public static function desencriptar3DES($key, $data)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $iv = substr($data, 0, $iv_size);
        $encData = substr($data, $iv_size);


        $decData = mcrypt_decrypt(MCRYPT_3DES, $key, $encData
            , MCRYPT_MODE_CBC, $iv);

        return $decData;
    }
    
    public static function encrypt($sValue, $sSecretKey)
    {
        return rtrim(
            base64_encode(
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(
                        mcrypt_get_iv_size(
                            MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                        ), MCRYPT_RAND)
                )
            ), "\0"
        );
    }

    public static function decrypt($sValue, $sSecretKey)
    {
        return rtrim(
            mcrypt_decrypt(
                MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_ECB, mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB
                    ), MCRYPT_RAND
                )
            ), "\0"
        );
    }

}