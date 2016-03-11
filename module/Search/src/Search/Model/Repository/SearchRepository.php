<?php
namespace Search\Model\Repository;
          
use Util\Model\Repository\Base\AbstractRepository;

class SearchRepository extends AbstractRepository
{
    protected $_table = 'fcb_search_search';
    
    protected $_id = 'search_id';
}