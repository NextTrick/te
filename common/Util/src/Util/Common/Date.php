<?php
namespace Util\Common;

class Date 
{
    public static function getMinutes($dateAfter, $dateBefore)
    {
        $dateBefore = strtotime($dateBefore);
        $dateAfter = strtotime($dateAfter);
        $interval  = abs($dateAfter - $dateBefore);
        $minutes   = round($interval / 60);
        
        return $minutes;
    }
}