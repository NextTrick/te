<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {                     
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');        
        //$carrierRepository = $entityManager->getRepository('CarrierRepository');
        $carrierEntity = $entityManager->find('Application\Entity\FcbCarrierCarrier', 1);
        echo 'hi'; exit;    
        return new ViewModel();
    }
}
