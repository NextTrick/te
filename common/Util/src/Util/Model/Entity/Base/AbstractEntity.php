<?php

namespace Util\Model\Entity\Base;

use Util\Model\Service\Entity\Base\EntityInterface;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * Solo se deben agregar relaciones de primer nivel y de tipo "m - 1" (relaciones padres)
     * Ejm: relaciones para producto, puede ser categoría o tipo, más no transacciones
     * 
     * Relations array. It must be like this:
     * $relations = array(
     *       'UsuarioEntity' => array(
     *           'prefix' => 'usuario_usuario_',   // prefix of relation table column name, it is like "<table>_"
     *           'class' => '\Usuario\Model\Entity\UsuarioEntity', //relation entity's class path
     *           'attribute' => 'usuario'),   //relation attribute in current entity
     *  )
     *
     * @var array 
     */
    public $relations = array();
    
    /**
     * variable en la cual se puede guardar datos extras
     * @var mix
     */
    public $extraData;
    
    /**
     * Convert array data to Object
     * 
     * @param type $data
     * @param string $mainPrefix Prefijo de los elementos del array de datos,
     * Sirve para poder enviar data de la entidad con un prefijo. 
     * Ejm: para el atributo id, si existe prefijo 'usuario_', entonces el elemento que le 
     * corresponde en el array de datos sería usuario_id 
     * 
     * @return \Util\Model\Service\Entity\Base\AbstractEntity
     */
            
    public function exchangeArray($data, $mainPrefix = '')
    {        
        if (!empty($this->relations) || !empty($data)) {
            foreach ($this->relations as $key => $relation) {
                foreach ($data as $column => $value) {
                    if (strpos($column, $relation['prefix']) !== false) {
                        $col = str_replace($relation['prefix'], '', $column);
                        $this->relations[$key]['data'][$col] = $value; 
                        unset($data[$column]);
                    }
                }
                
                if (!empty($this->relations[$key]['data'])) {
                    $relObject = new $relation['class']();
                    $relObject->exchangeArray($this->relations[$key]['data']);
                    $this->relations[$key]['object'] = $relObject;
                    $attribute = \Util\Common\String::prepareForMethod($this->relations[$key]['attribute']);
                    $method = 'set' . $attribute;
                    $this->$method($this->relations[$key]['object']);                    
                }                                                                
            }            
        }
                        
        foreach (get_object_vars($this) as $field => $value) {
            $dataField = $mainPrefix . $field;            
            $this->$field = isset($data[$dataField]) ? $data[$dataField] : $value;
        }

        return $this;
    }

    public function toArray() 
    {
        return get_object_vars($this);
    }
    
    public function setExtraData($extraData)
    {
        $this->extraData = $extraData;
    }
    
    public function getExtraData()
    {
        return $this->extraData;
    }
}