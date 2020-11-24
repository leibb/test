<?php

namespace App\Helpers;


class UserAgentHelper
{
    /**
     * 获取设备名称
     * @param $useragent
     */
    public static function getDeviceName($useragent)
    {
        if (strpos($useragent, "okhttp") !== false)
            return "安卓APP";
        else if (strpos($useragent, "PYTui") !== false)
            return "苹果APP";
        else if (strpos($useragent, "MicroMessenger") !== false)
            return "微信浏览器";
        else if (strpos($useragent, "MSIE") !== false)
            return "IE";
        else if (strpos($useragent, "Firefox") !== false)
            return "Firefox";
        else if (strpos($useragent, "Chrome") !== false)
            return "Google Chrome";
        else if (strpos($useragent, "Safari") !== false)
            return "Safari";
        else if (strpos($useragent, "Opera") !== false)
            return "Opera";

        return "未知";
    }
}