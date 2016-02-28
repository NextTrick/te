<?php
namespace Util\Common;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class Filter 
{
    /**
     * 
     * @param mixed $data
     * @return type
     */
    public static function trimStripTag($data = null)
    {
        if(is_string($data)) {
            $filterStringTrim = new StringTrim();
            $data = $filterStringTrim->filter($data);
            
            $filterStripTags = new StripTags();
            $data = $filterStripTags->filter($data);

        } else if( is_array($data) && count($data) ){
            foreach($data as $key => $value){
                $data[$key] = Filter::trimStripTag($value);
            }
        }

        return $data;
    }     
}