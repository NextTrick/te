<?php

namespace Api\Model;

class Payment
{
    const ERROR_CODE = '400';
    const MESSAGE_OK = 'OK';

    public static $_params = array(
            'authorization' => array(
                'amount',
                'currency',
                'customerName',
                'customerLastName',
                'creditCardNumber',
                'ccv2Number',
                'creditCardType',
                'recurringTransaction',
                'expirationMonth',
                'expirationYear',
            ),
            'authorizationshopper' => array(
                'amount',
                'currency',
                'shopperId',
                'recurringTransaction',
            ),
            'authorizationandcapture' => array(
                'amount',
                'currency',
                'customerName',
                'customerLastName',
                'creditCardNumber',
                'ccv2Number',
                'creditCardType',
                'expirationMonth',
                'expirationYear',
            ),
            'authorizationcapture' => array(
                'shopperId',
                'amount',
                'currency',
            ),
            'capture' => array(
                'origin',
            ),
            'refund' => array(
                'origin',
            ),
            'partialrefund' => array(
                'origin',
                'amount',
            ),
            'authorizationreversal' => array(
                'origin'
            ),
            'authorizationreversal' => array(
                'origin'
            ),
        );
    
    /**
     * 
     * @param type $data
     * @param type $type
     * @return string
     */
    public static function validExistsInput($data, $type = 'authorization')
    {
        $response = array('success' => TRUE);
        $columns = self::$_params[$type];
        if ((count($columns) != count($data))) {
            return array(
                    'success' => FALSE, 
                    'code' => self::ERROR_CODE,
                    'message' => 'El número de parámetros no coincide.'
                );
        }
        $keys = array_keys($data);
        $columnInvalid = array();
        foreach ($columns as $column) {
            if (!in_array($column, $keys)) {
                $columnInvalid[] = $column;
            }
        }
        if(!empty($columnInvalid)){
            $response = array(
                    'success' => FALSE,
                    'code' => self::ERROR_CODE,
                    'message' => 'Faltan parámetros: '. implode(', ', $columnInvalid),
                );
        }
        return $response;
    }
}
