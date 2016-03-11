<?php

namespace Util\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Link extends AbstractHelper
{   
    private $_link = '<a %id %class %href>%name</a>';

    public function __invoke($href, $name = '', $class = '', $id = '')
    {
        $this->_setId($id);
        $this->_setClass($class);
        $this->_setHref($href);
        $this->_setName($name);

        return $this->_link;
    }

    private function _setId($id)
    {
        $replace = '';

        if($id != '') {
            $replace = 'id="'. $id .'"';
        }

        $this->_link = str_replace('%id', $replace, $this->_link);
    }   

    private function _setName($name)
    {
        $replace = '';

        if($name != '') {
            $replace = $name;
        }

        $this->_link = str_replace('%name', $replace, $this->_link);
    }

    private function _setClass($class)
    {
        $replace = '';

        if($class != '') {
            $replace = 'class="'. $class .'"';
        }

        $this->_link = str_replace('%class', $replace, $this->_link);
    }

        private function _setHref($href)
    {
        $replace = '';

        if($href != '') {
            $replace = 'href="'. $href .'"';
        }

        $this->_link = str_replace('%href', $replace, $this->_link);
    }
}
