<?php
namespace Service\Model\Service;

use Util\Model\Service\Base\AbstractService;

class RequestService extends AbstractService
{
    protected function saveRequest($params, $trackId, $method)
    {
        $requestData = array(
            'serviceApikeyId' => $params['serviceApikeyId'],
            'trackId' => $trackId,
            'request' => json_encode($params),
            'method' => $method,
        );

        $this->getRequestService()->getRepository()->save($requestData);
    }
}