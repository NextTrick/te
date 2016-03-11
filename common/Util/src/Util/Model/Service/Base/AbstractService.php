<?php
namespace Util\Model\Service\Base;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Util\Model\Service\Entity\Base\AbstractEntity;

abstract class AbstractService implements ServiceLocatorAwareInterface
{
    protected $_sl;
    
    protected $_repository;

    public function __construct($repository = null)
    {
        $this->_repository = $repository;  
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface;
     */
    public function getServiceLocator()
    {
        return $this->_sl;
    }
    
    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->_sl = $serviceLocator;
    }
    
    /**
     * Devuelve el repsositorio correspondiente al servicio
     * 
     * @return object
     */
    public function getRepository()
    {
        return $this->_repository;
    }
    
    /**
     * Retorna un array de array de la entidad correspondiente
     * 
     * @param Object $entity
     * @param array assoc array $data
     * @param array $mainPrefix     
     * @return object
     */
    public function getEntities($entity, $data, $mainPrefix = '')
    {
        if (!$entity instanceof AbstractEntity) {
            throw new \Exception('The fist param must be an AbstractEntity class');
        }
                
        $entities =  array();
        if (!empty($data)) {            
            foreach ($data as $item) {                   
                $clone = clone $entity;
                $entities[] = $this->getEntity($clone, $item);
            }
        }        
        return $entities;
    }
    
    /**
     * Get the current entity
     * 
     * @param EntityObject $entity instance of Entity
     * @param array $data Entitys' and relations' data
     * @param array $mainPrefix
     * @return Object | null
     */    
    public function getEntity($entity, $data, $mainPrefix = '')
    {
        if (!$entity instanceof AbstractEntity) {
            throw new \Exception('The fist param must be an AbstractEntity class');
        }
                
        if (!empty($data)) {
            return $entity->exchangeArray($data, $mainPrefix);;
        }
        
        return null;
    }        
}