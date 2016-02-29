<?php

namespace Api\Model\Gateway\Carrier\Ws;

use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Renderer\PhpRenderer;

class DhlWs {

    public $_PIuserid	= "911comprep";
    public $_PIpwd		= "DiiC08pR3p";
    public $_PIurl = "";
    public $_PItesturl = "";

    public $_PImode = "";

    public $_errors = array();
    public $errorFail = false;

    public $_xml = null;
    public $_result = null;
    public $_xmlEnd	= "\n";

    public $checkAuth = false;
    public $checkReq = true;
        
    public function __construct($config, $mode = 'test') {
        $this->_PIurl = "https://xmlpi-ea.dhl.com/XMLShippingServlet";
        $this->_PItesturl = "https://xmlpitest-ea.dhl.com/XMLShippingServlet";
        switch (strtolower($mode)) {
            case 'live':
                // we use live mode
                $this->_PImode = "live";
                break;
            case 'test':
            default:
                // we default to test mode
                $this->_PImode = "test";
                break;
        }
    }

    public function getTracking() 
    {
        $this->setAuth();
        $response = $this->single(9422367220);
        var_dump($response);exit;
        return 'OKSdhl';
    }

    public function getMultitracking() {
        
    }

    function setAuth($userid = NULL, $pwd = NULL) {
        if (is_null($userid)) {
            $this->logError("auth > UserID", $msg = "user id was not set", true);
        } else {
            $this->_PIuserid = $userid;
        }
        if (is_null($userid)) {
            $this->logError("auth > Password", $msg = "Password was not set", true);
        } else {
            $this->_PIpwd = $pwd;
        }
        $this->checkAuth = true;
    }

    function logError($loc = "", $msg = "", $fail = false) {
        //
        $tmp = array(
            'location' => $loc,
            'message' => $msg,
            'stop' => ((bool) $fail ? "Yes" : "No"),
            'time' => microtime(true)
        );
        if ((bool) $fail) {
            $this->errorFail = true;
        }
        $this->_errors[] = $tmp;
        $tmp = NULL;
    }

    function getErrors() {
        //
        return ($this->_errors);
    }

    //========================================================================================
    // send pi request
    //========================================================================================
    function _sendCallPI() {

        if (!$ch = curl_init()) {
            $this->logError("Send >> Curl", $msg = "Curl is not initialized", true);
            return false;
        } else {
            
            if ($this->checkAuth && $this->checkReq) {
                $use_url = ($this->_PImode == "test" ? $this->_PItesturl : $this->_PIurl);
                curl_setopt($ch, CURLOPT_URL, $use_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_xml);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $this->_result = curl_exec($ch);
                var_dump($this->_result);exit;
                if (curl_error($ch) != "") {
                    $this->logError("Send >> Curl", $msg = "Error with Curl installation: " . curl_error($ch), true);
                    return false;
                } else {
                    curl_close($ch);
                    return $this->_result;
                }
            }
        }
    }

    function single($airbill) {
        //
        $this->_xml = "";
        $this->_xml .= "<?xml version = '1.0' encoding = 'UTF-8'?>" . $this->_xmlEnd;
        $this->_xml .= "<req:KnownTrackingRequest xmlns:req='http://www.dhl.com' ";
        $this->_xml .= "		xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ";
        $this->_xml .= "		xsi:schemaLocation='http://www.dhl.com TrackingRequestKnown.xsd'>" . $this->_xmlEnd;
        $this->_xml .= "<Request>" . $this->_xmlEnd;
        $this->_xml .= "<ServiceHeader>" . $this->_xmlEnd;
        $this->_xml .= "<MessageTime>" . date("c") . "</MessageTime>" . $this->_xmlEnd;
        $this->_xml .= "<MessageReference>" . time() . $airbill . "</MessageReference>" . $this->_xmlEnd;
        $this->_xml .= "<SiteID>" . $this->_PIuserid . "</SiteID>" . $this->_xmlEnd;
        $this->_xml .= "<Password>" . $this->_PIpwd . "</Password>" . $this->_xmlEnd;
        $this->_xml .= "</ServiceHeader>" . $this->_xmlEnd;
        $this->_xml .= "</Request>" . $this->_xmlEnd;
        $this->_xml .= "<LanguageCode>en</LanguageCode>" . $this->_xmlEnd;
        $this->_xml .= "<AWBNumber>" . $airbill . "</AWBNumber>" . $this->_xmlEnd;
        $this->_xml .= "<LevelOfDetails>ALL_CHECK_POINTS</LevelOfDetails>" . $this->_xmlEnd;
        $this->_xml .= "</req:KnownTrackingRequest>" . $this->_xmlEnd;
        
        $abi = simplexml_load_string($this->_sendCallPI());
        $tmp_awb = (string) $abi->AWBNumber;
        $td['awb'] = $tmp_awb;

        $td['res']['status'] = (string) $abi->Status->ActionStatus;

        $td['event']['time']['date'] = (string) $abi->ShipmentInfo->ShipmentEvent->Date;
        $td['event']['time']['time'] = (string) $abi->ShipmentInfo->ShipmentEvent->Time;
        $td['event']['time']['stamp'] = strtotime($td['event']['time']['date'] . " " . $td['event']['time']['time']);
        //$td['event']['time']['check'] = date("c", $td['event']['time']['stamp']);
        $td['event']['code'] = (string) $abi->ShipmentInfo->ShipmentEvent->ServiceEvent->EventCode;

        $tmp_event_desc = (string) $abi->ShipmentInfo->ShipmentEvent->ServiceEvent->Description;
        $tmp_event_desc = preg_replace('/\s\s+/', ' ', $tmp_event_desc);
        $td['event']['desc'] = $tmp_event_desc;

        $tmp_loc_desc = (string) $abi->ShipmentInfo->ShipmentEvent->ServiceArea->Description;
        $tmp_loc_desc = preg_replace('/\s\s+/', ' ', $tmp_loc_desc);
        $td['event']['location'] = $tmp_loc_desc;


        return $td;
    }

}
