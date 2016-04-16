<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;

class RequestService extends AbstractService
{
    protected function save($params, $method)
    {
        $data = array(
            'serviceApikeyId' => $params['serviceApikeyId'],        
            'request' => json_encode($params),
            'method' => $method,
        );

        return $this->getRequestService()->getRepository()->save($data);
    }
}