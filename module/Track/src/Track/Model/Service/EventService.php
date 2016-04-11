<?php
namespace Track\Model\Service;

use Util\Model\Service\Base\AbstractService;
use Track\Model\Repository\EventRepository;
class EventService extends AbstractService
{
    public function saveEvent($params)
    {
        $dataTrack = $this->getTrackService()->getRepository()
                        ->getBy(array('trackId=?' => $params['trackId']));
        if(empty($dataTrack)) {
            return array(
                'success' => FALSE,
                'message' => 'TrackId no valido'
            );
        }
        $response = array('success' => TRUE);
        $eventId = $this->getRepository()->save(array(
            'eventId' => empty($params['eventid'])? NULL : $params['eventid'],
            'trackId' => $params['trackId'], 
            'eventStatusId'  => $params['eventStatusId'], 
            'creationDate' => date('Y-m-d H:i:s'), 
            'postalCode' => $params['trackId'],  
            'stateProvinceCode' => $params['trackId'],  
            'countryName' => $params['countryName'],  
            'countryCode' => $params['countryCode'],  
            'latitud' => $params['latitud'],  
            'longitud' => $params['longitud'],  
            'ubigeoId' => $params['ubigeoId'], 
        ));
        $response['data']['eventId'] = $eventId;
        return $response;
    }
    
    public function deleteEvent($params)
    {
        $response = array('success' => TRUE);
        $this->getRepository()->update(
                array('status' => EventRepository::STATUS_DISABLED),
                array('eventId=?' => $params['eventId'])
            );
        return $response;
    }
    
    /**
     * @return \Track\Model\Service\TrackService
     */
    public function getTrackService()
    {
        return $this->getServiceLocator()->get('Track\Model\Service\TrackService');
    }
    
}