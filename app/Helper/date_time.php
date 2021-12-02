<?php
if (!function_exists('strToTimestamp')) {
    function strToTimestamp(string $str)
    {
        return date('Y-m-d H:m:s', strtotime($str));
    }
}

if (!function_exists('dateTimeToStr')) {
    function dateTimeToStr(string $str)
    {
        return date('d-M-Y', strtotime($str));
    }
}