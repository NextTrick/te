<?php

namespace Util\Model\Repository\Base;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\ResultSet\ResultSet;

abstract class AbstractRepository implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var string 
     */
    protected $_table;
    
    /**
     *
     * @var string 
     */
    protected $_id = 'id';
    
    /**
     *
     * @var string 
     */
    protected $_creationDate = 'creationDate';
    
    /**
     *
     * @var string 
     */
    protected $_editionDate = 'editionDate';
    
    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $_sl;
    
    /**
     *
     * @var \Zend\Db\Adapter\Adapter 
     */
    public $adapter;
    
    /**
     *
     * @var \Storage\StorageInterface
     */                    
    public $cache;
    
    /**
     *
     * @var \Zend\Db\Sql\Sql 
     */
    public $sql;
    

    public function __construct(Adapter $adapter) 
    {        
        $this->sql = new Sql($adapter);
        $this->adapter = $adapter;
    }
    
    protected function _execute($select) 
    {
        $selectString = $this->sql->prepareStatementForSqlObject($select);
        $result = $selectString->execute();
        
        return $result;
    }
    
    protected function _select()
    {
        $sql = new Sql($this->adapter);
        return $sql->select()->from(array('a' => $this->_table));
    }
    
    /**
     * 
     * @param object $select
     * @param string $entityClass
     * @return array|object
     */
    public function fetchAll($select, $entityClass = null)
    {                
        $result = $this->_execute($select);
        $resultSet = new ResultSet();
        if ($entityClass !== null) {
            $resultSet->setArrayObjectPrototype(new $entityClass());
            return $resultSet->initialize($result);
        } else {
            return $resultSet->initialize($result)->toArray();
        }
    }

    /**
     * 
     * @param object $select
     * @param string $entityClass
     * @return array|object
     */
    public function fetchRow($select, $entityClass = null)
    {
        $result = $this->_execute($select);
        $resultSet = new ResultSet();
        if ($entityClass !== null) {
            $resultSet->setArrayObjectPrototype(new $entityClass());
            return $resultSet->initialize($result)->current();
        } else {
            $data = $resultSet->initialize($result)->toArray();
            if (!empty($data)) {
                return $data[0];
            }        
            return array();
        }
    }
    
    /**
     * 
     * @param type $select
     * @return array
     */
    public function fetchAssoc($select)
    {
        $result = $this->_execute($select);
        $resultSet = new ResultSet();
        $stmt = $resultSet->initialize($result)->toArray();
        $data = array();
        foreach ($stmt as $row) {
            $tmp = array_values(array_slice($row, 0, 1));
            $data[$tmp[0]] = $row;
        }

        return $data;
    }

    /**
     * 
     * @param type $select
     * @return array
     */
    public function fetchCol($select)
    {
        $result = $this->_execute($select);
        $resultSet = new ResultSet();
        $stmt = $resultSet->initialize($result)->toArray();
        $data = array();
        foreach ($stmt as $row) {
            $tmp = array_values(array_slice($row, 0, 1));
            $data[] = $tmp[0];
        }

        return $data;
    }

    /**
     * 
     * @param type $select
     * @return array
     */
    public function fetchPairs($select)
    {
        $result = $this->_execute($select);
        $resultSet = new ResultSet();
        $stmt = $resultSet->initialize($result)->toArray();
        $data = array();
        foreach ($stmt as $row) {
            $tmp = array_values(array_slice($row, 0, 2));
            $data[$tmp[0]] = $tmp[1];
        }

        return $data;
    }

    /**
     * 
     * @param type $select
     * @return array|object
     */
    public function fetchOne($select, $entityClass = null)
    {
        return $this->fetchRow($select, $entityClass);
    }
    
    public function update($data, $where)
    {
        $update = $this->sql->update($this->_table);
        $update->set($data);
        $update->where($where);                
        $result = $this->_execute($update);
                                                        
        if ($result) {
            return true;
        }

        return false;
    }
    
    public function insert($data)
    {
        $insert = $this->sql->insert($this->_table);
        $insert->values($data);
        
        $result = $this->_execute($insert);
        
        return $result->getGeneratedValue();
    }

    public function delete($where)
    {
        $delete = $this->sql->delete($this->_table);
        $delete->where($where);
        $result = $this->_execute($delete);

        if ($result) {
            return true;
        }

        return false;
    }

    public function getServiceLocator()
    {
        return $this->_sl;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->_sl = $serviceLocator;
    }

    /**
     * Get tables' columns
     * 
     * @param type $table
     * @return array
     */
    public function getCols($table)
    {
        $metadata = new Metadata($this->getAdapter());

        return $metadata->getColumnNames($table);
    }
    
    public function query($select)
    {
        return $this->getAdapter()->query($select, Adapter::QUERY_MODE_EXECUTE)->toArray();
    }
        
    public function setCache(StorageInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * To easily get a paginator instance for a select object
     * @author Anderson
     * @param $select \Zend\Db\Sql\Select The select object to paginate
     * @param $page int The current page number
     * @param $limit int Number of items per page
     * @return \Zend\Paginator\Paginator
     */
    protected function getPaginatorForSelect($select, $page, $limit = 20,
            $pageRange = 10)
    {
        //-- Utilizar Buffer para Paginator
        $resulset = new \Zend\Db\ResultSet\ResultSet();
        $resulset->buffer();
        $paginatorAdapter = new DbSelect($select, $this->getAdapter(), $resulset);
        $paginator = new Paginator($paginatorAdapter);

        $paginator->setItemCountPerPage($limit);
        $paginator->setPageRange($pageRange);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
    
    /**
     * Devuelve un solo registro de la tabla
     * filtrado por id
     * 
     * @param int $id
     * @param array $columns
     * @return array
     */
    public function getById($id, $columns = array(), $entityClass = null)
    {        
        $where = array(
            $this->_id => $id
        );        
        $select = $this->sql->select($this->_table);
        $select->where($where);
        
        if (count($columns) > 0) {
            $select->columns($columns);
        }
                     
        return $this->fetchRow($select, $entityClass);
    }
    
    /**
     * Devuelve todos los registros de la tabla
     * 
     * @param array $columns
     * @return array
     */
    public function getAll($columns = array(), $entityClass = null)
    {                
        $select = $this->sql->select($this->_table);      
        
        if (count($columns) > 0) {
            $select->columns($columns);
        }
        
        return $this->fetchAll($select, $entityClass);
    }
    
    /**
     * Retorna arrays asociativos de los registros
     * 
     * @param type $columns
     * @return type
     */
    public function getAssoc($columns = array())
    {                        
        $select = $this->sql->select($this->_table);      
        
        if (count($columns) > 0) {
            $select->columns($columns);
        }
        
        return $this->fetchAssoc($select);
    }
    
    /**
     * Obtiene los registros de la tabla, mediante el filtro
     * especificado en el parámetro $where          
     * 
     * @param type $where
     * @param type $row si es true, retorna un solo registro
     * @param type $columns
     * @return array
     */
    public function getBy($where, $row = false, $columns = array(), $entityClass = null)
    {
        $select = $this->sql->select($this->_table);
        
        if (!empty($where)) {
            $select->where($where);
        }
                        
        if (count($columns) > 0) {
            $select->columns($columns);
        }
        
        if ($row == true) {
            return $this->fetchRow($select, $entityClass);
        }
        
        return $this->fetchAll($select, $entityClass);
    }
    
    /**
     * Guarda si no existe el índice id, 
     * actualiza en caso contrario
     * 
     * @param array | object $data
     * @return int | null
     */
    public function save($data)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }
        
        $data = $this->unsetRels($data);
        $cols = $this->getCols($this->_table);        
        if (empty($data[$this->_id])) {
            $fecha = new \DateTime('NOW');
            
            if (in_array($this->_creationDate, $cols)) {
                if (!array_key_exists($this->_creationDate, $data)) {
                    $data[$this->_creationDate] = $fecha->format('Y-m-d H:i:s');
                }
            } 
            
            $id = $this->insert($data);
            if($id) {
                return $id;
            } 
        } else {
            $id = $data[$this->_id];
            $fecha = new \DateTime('NOW');
            
            if (in_array($this->_editionDate, $cols)) {
                if (!array_key_exists($this->_editionDate, $data)) {
                    $data[$this->_editionDate] = $fecha->format('Y-m-d H:i:s');
                }
            }                        
            $where = array(
                $this->_id => $id,
            );
            unset($data[$this->_id]);
                            
            if($this->update($data, $where)) {
                return $id;
            }
        }
        
        return null;
    }
    
    /**
     * Unset Relations attributes and relations array
     * 
     * @param type $data
     * @return type
     */
    public function unsetRels($data)
    {
        if (isset($data['relations'])) {
            foreach ($data['relations'] as $item) {
                unset($data[$item['attribute']]);
            }
            unset ($data['relations']); 
        }
        
        unset($data['extraData']);
        
        return $data;
    }
    
    /**
     * Devuele un array listo para formar parte de un elemento
     * Select
     * 
     * @param type $colums
     * @return array
     */
    public function getForSelect($colums = array('id', 'nombre'))
    {
        $select = $this->sql->select($this->_table);        
        $select->columns($colums);
        return $this->fetchPairs($select);
    }
    
    /**
     * 
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
    
    /**
     * 
     * @return \Zend\Db\Adapter\Adapter
     */
    public function setAdapter($adapter)
    {
        $this->sql = new Sql($adapter);
        $this->adapter = $adapter;
    }
    
    /**
     * 
     * @return \Storage\StorageInterface
     */
    public function getCache()
    {
        return $this->cache;
    }
    
    /**
     * Rerturn prefixed columns: ejm. array('usuario_usuario_id' => id). 
     * It is very neccesary to prevent overwrite fields with same name
     * 
     * @param string $table
     * @param array $columns
     * @param string $alias     
     * @return array
     */
    public function getPrefixedCols($table, $columns = array('*'), $alias = null)
    {        
        $aliasCacheKey = ($alias == null) ? 'null' : $alias;
        $colsCacheKey = (!empty($columns) && $columns[0] == '*') ? 'todos' : implode('_', $columns);
        $cacheKey = $table . '_' . $colsCacheKey . '_' . $aliasCacheKey;
                
        if ($this->cache->getItem($cacheKey) != null) {            
            return $this->cache->getItem($cacheKey);
        }
       
        if (!empty($columns) && $columns[0] == '*') {
            $columns = $this->getCols($table);
        }
        
        if ($alias == null) {
            $alias = $table . '_';
        }
        
        $prefixedColumns = array();
        foreach ($columns as $column) {            
           $prefixedColumns[$alias . $column] = $column; 
        }
        
        $this->cache->addItem($cacheKey, $prefixedColumns);
        
        return $prefixedColumns;
    }
    
    /**
     * Convierte un objeto Select a string.
     * 
     * @param Select $select
     * @return String
     */
    public function getSelectToString($select)
    {
        return $this->sql->getSqlStringForSqlObject($select);
    }            
}
