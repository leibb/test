<?php

namespace App\Helpers;

class UtilsHelper
{
    /**
     * 数组值
     * @param $array
     * @param $key
     * @param null $default
     * @return null
     */
    public static function arrayValue($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function arraySort($array, $field, $sort = SORT_DESC)
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], $sort, $array);
        return $array;
    }

    /**
     * 随机字符串
     * @param int $length
     * @param string $char
     * @return bool|string
     */
    public static function randStr($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }

        return $string;
    }

    /**
     * 取指定key组成的数组
     * @param $array
     * @param $key
     * @return array
     */
    public static function subKeyArray($array, $key)
    {
        $data = [];
        foreach ($array as $arr) {
            $data[] = $arr[$key];
        }
        return $data;
    }

    /**
     * 过滤输入
     * @param $input
     * @return string
     */
    public static function trimInput($input)
    {
        $input = trim($input);
        $input = preg_replace('/\x{202c}|\x{202d}/u', '', $input);
        return $input;
    }


    /**
     * 获取客户端IP地址
     * @return string
     */
    public static function getClientIp()
    {
        foreach (['HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_REAL_IP',
                     'HTTP_CLIENT_IP',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    /**
     * 获取随机头像
     * @param $sex
     * @return mixed
     */
    public static function getRandPic($sex)
    {
        $sex = $sex == 1 ? 'boy' : 'girl';
        header("Content-type:text/html;charset=utf-8");

        $array = [];

        $dir = public_path() . '/images/anonymous_avatar/' . $sex;

        $url = env('APP_URL');
        if (is_dir($dir)) {
            $handle = opendir($dir); //当前目录
            //列出 images 目录中的文件
            while (($file = readdir($handle)) !== false) {
                list($filesname, $kzm) = explode(".", $file);//获取扩展名
                if ($kzm == "jpeg" || $kzm == "jpg" || $kzm == "png") { //文件过滤
                    if (!is_dir('./' . $file)) { //文件夹过滤
                        $array[] = $url . '/images/anonymous_avatar/' . $sex . '/' . $file;//把符合条件的文件名存入数组
                    }
                }
            }
            closedir($handle);
        }
        $pic = array_rand($array);
        return $array[$pic];
    }
}