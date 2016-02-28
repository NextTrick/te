<?php
namespace Util\Common;

class RelativeTime 
{    
    /**
     *  Relative times Facebook style
     * @param datetime $time
     * @return string
     */
    public static function getTime($time)
    {
        if ($time !== intval($time)) {
            $time = strtotime($time);
        }
        
        $timestamp      = (int) $time;
        $current_time   = time();
        $diff           = $current_time - $timestamp;

        // Intervals in seconds
        $intervals = array(
            'year'  => 31556926, 
            'month' => 2629744, 
            'week'  => 604800, 
            'day'   => 86400, 
            'hour'   => 3600, 
            'minute' => 60
        );

        // now we just find the difference
        if ($diff == 0 || $diff < 0 || $diff < 5) {
            return 'Just now';
        }

        if ($diff < 60) {
            return $diff == 1 ? $diff . ' sec ago' : $diff . ' secs ago';
            //return $diff == 1 ? 'Just now' : $diff . ' secs ago';
        }

        if ($diff >= 60 && $diff < $intervals['hour']) {
            $diff = floor($diff / $intervals['minute']);
            return $diff == 1 ? $diff . ' min ago' : $diff . ' mins ago';
        }

        if ($diff >= $intervals['hour'] && $diff < $intervals['day']) {
            $diff = floor($diff / $intervals['hour']);
            return $diff == 1 ? $diff . ' hr ago' : $diff . ' hrs ago';
        }

        if ($diff >= $intervals['day'] && $diff < $intervals['week']) {
            $diff = floor($diff / $intervals['day']);
            return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
        }

        if ($diff >= $intervals['week'] && $diff < $intervals['month']) {
            $diff = floor($diff / $intervals['week']);
            return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
        }

        if ($diff >= $intervals['month'] && $diff < $intervals['year']) {
            $diff = floor($diff / $intervals['month']);
            return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
        }

        if ($diff >= $intervals['year']) {
            $diff = floor($diff / $intervals['year']);
            return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
        }
    }
}