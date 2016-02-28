<?php
namespace Util\Common;

class Arrray 
{
    /**
     * Cambia el indice de una array de array, pro el valor de la indice indicado
     * 
     * @param array array $data
     * @param string $index
     * @return array
     */
    static public function changeArrayKey($data, $index)
    {
        $changeArray = array();
        foreach ($data as $item) {
            $changeArray[$item[$index]] = $item;
        }
        
        return $changeArray;
    }
    
    static public function orderMultiDimensionalArray($toOrderArray, $field, $inverse = false) 
    {
        $position = array();
        $newRow = array();
        foreach ($toOrderArray as $key => $row) {
            $position[$key] = $row[$field];
            $newRow[$key] = $row;
        }
        if ($inverse) {
            arsort($position);
        } else {
            asort($position);
        }
        $returnArray = array();
        foreach ($position as $key => $pos) {
            $returnArray[] = $newRow[$key];
        }
        return $returnArray;
    }
}