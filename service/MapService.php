<?php
/**
 * Created by PhpStorm.
 * User: Mch
 * Date: 2019-09-14
 * Time: 15:45
 */

namespace service;

use lib\Logger;
use lib\Str;

/**
 * @ref: http://www.gpsspg.com/maps.htm
 * Class MapService
 * @package service
 */
class MapService {

    /**
     * 腾讯地图API
     * @param $lat double
     * @param $lng double
     * @return string
     */
    public static function tencent($lat, $lng) {
        $api = "http://www.gpsspg.com/apis/maps/geo/";
        // $ts = time();
        // "jQuery1102005223567051282596_".$ts,
        $params = array(
            "output" => "jsonp",
            "lat"    => $lat,
            "lng"    => $lng,
            "type"   => 0,
            "callback" => "jQuery110207233558658285575_1568446775652",
            "_" => "1568446775654"
        );

        $ch = curl_init();
        $headers = array(
            "Pragma: no-cache",
            "Host: www.gpsspg.com",
            // "Accept-Encoding: gzip, deflate",
            "Accept-Language: en,en-US;q=0.9,zh-CN;q=0.8,zh;q=0.7,ja;q=0.6",
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36",
            "Accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01",
            "Referer: http://www.gpsspg.com/iframe/maps/qq_181109.htm?mapi=2",
            "X-Requested-with: XMLHttpRequest",
            "Cookie: ARRAffinity=16e216e85d59d29bbba3137a10fe5038d043ca00c339d6f70d624fff72833aba; Hm_lvt_15b1a40a8d25f43208adae1c1e12a514=1568444518; Hm_lpvt_15b1a40a8d25f43208adae1c1e12a514=1568444518; __tins__540082=%7B%22sid%22%3A%201568444517866%2C%20%22vd%22%3A%201%2C%20%22expires%22%3A%201568446317866%7D; __51cke__=; __51laig__=1",
            "Connection: keep-alive",
            "Cache-Control: no-cache"
        );
        $qs = Curl::buildQuery($params);
        $fullURL = sprintf("%s?%s", $api, $qs);

        curl_setopt_array($ch, [
            CURLOPT_URL => $fullURL,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);

        $data = curl_exec($ch);
        Logger::write($data);
        $errno = curl_errno($ch);
        if ($errno) {
            throw new \RuntimeException("%s\n", curl_error($ch), $errno);
        }
        curl_close($ch);

        // header("Content-Type: application/jsonp; charset=UTF-8");
        $data = Str::extractAfterDelim($data, "\r\n\r\n");
        // jsonp => json
        return Str::jsonp2json($data);
    }

    /**
 * @param $lat double
 * @param $lng double
 * @return string
 */
    public static function baidu($lat, $lng) {
        $api = "http://api.map.baidu.com/";
        $params = array(
            "qt" => "rgc",
            "x" => "12947586.98",
            "y" => "4847016.95",
            "dis_poi" => 100,
            "poi_num" => 10,
            "latest_admin" => 1,
            "ie" => "utf-8",
            "oue" => 1,
            "fromproduct" => "jsapi",
            "res"=>"api",
            "callback" => "BMap._rd._cbk90073",
            "ak=4qdl6gwAEmpyrBPBNj6VIGqR"
        );

        $ch = curl_init();
        $headers = array(
            "Pragma: no-cache",
            "Host: www.gpsspg.com",
            "Accept-Language: en,en-US;q=0.9,zh-CN;q=0.8,zh;q=0.7,ja;q=0.6",
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36",
            "Referer: http://www.gpsspg.com/iframe/maps/baidu_181109.htm?mapi=1",
            "X-Requested-with: XMLHttpRequest",
        );
        $qs = Curl::buildQuery($params);
        $fullURL = sprintf("%s?%s", $api, $qs);

        curl_setopt_array($ch, [
            CURLOPT_URL => $fullURL,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);

        $data = curl_exec($ch);
        Logger::write($data);
        $errno = curl_errno($ch);
        if ($errno) {
            throw new \RuntimeException("%s\n", curl_error($ch), $errno);
        }
        curl_close($ch);
        $statusCode = Curl::getStatusCode($data);
        if ($statusCode !== 200) {
            return $data;
        }
        // header("Content-Type: application/jsonp; charset=UTF-8");
        $data = Str::extractAfterDelim($data, "\r\n\r\n");
        // jsonp => json
        return Str::jsonp2json($data);
    }

    /**
     * @param $lat double
     * @param $lng double
     * @return string
     */
    public static function gaode($lat, $lng) {

        $ch = curl_init();
        $headers = array(
            "Referer: http://www.gpsspg.com/iframe/maps/amap_181109.htm?mapi=3",
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36",
        );
        $fullURL = "http://restapi.amap.com/v3/geocode/regeo?" .
            "key=169d2dd7829fe45690fabec812d05bc3&s=rsv3&" .
            "location=".$lng.",".$lat.
        "&callback=jsonp_947512_&platform=JS&logversion=2.0&sdkversion=1.3".
        "&appname=http%3A%2F%2Fwww.gpsspg.com%2Fiframe%2Fmaps%2Famap_181109.htm%3Fmapi%3D3".
        "&csid=19E5FF71-1310-469C-8751-78FDB2C959AC";

        curl_setopt_array($ch, [
            CURLOPT_URL => $fullURL,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);

        $data = curl_exec($ch);
        Logger::write($data);
        $errno = curl_errno($ch);
        if ($errno) {
            throw new \RuntimeException("%s\n", curl_error($ch), $errno);
        }
        curl_close($ch);
        $statusCode = Curl::getStatusCode($data);
        if ($statusCode !== 200) {
            return $data;
        }
        $data = Str::extractAfterDelim($data, "\r\n\r\n");
        // jsonp => json
        return Str::jsonp2json($data);
    }

    /**
     * 通过ip38网站查询ip归属地 但是从服务器上访问403!
     * http://www.ip138.com/ips138.asp?ip=122.70.40.218&action=2
     * @param $ip
     * @return string
     * "<ul class="ul1">
     *     <li>本站数据：北京市丰台区  联通</li>
     *     <li>参考数据1：北京北京  联通</li>
     *     <li>参考数据2：北京市 联通(丰台区)</li>
     *     <li>兼容IPv6地址：::7C40:1108</li>
     *     <li>映射IPv6地址：::FFFF:7C40:1108</li>
     *  </ul>"
     */
    public static function queryGeoByIp($ip) {
        $api = "http://www.ip138.com/ips138.asp";
        $params = array(
            "ip" => $ip,
            "action" => 2,
        );
        $ch = curl_init();
        $headers = array(
            "Accept-Language: en,en-US;q=0.9,zh-CN;q=0.8,zh;q=0.7,ja;q=0.6",
            "Connection: keep-alive",
            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36",
            "Referer: http://www.ip138.com",
            "Cookie: Hm_lvt_f4f76646cd877e538aa1fbbdf351c548=1568378016; Hm_lpvt_f4f76646cd877e538aa1fbbdf351c548=1568465475; ASPSESSIONIDQQBSSRCQ=LEOEJDPCMNODBBBLHOEPNDHL; Hm_lvt_b018ba5033f3b0d184416653ad858a48=1568465490; Hm_lpvt_b018ba5033f3b0d184416653ad858a48=1568465904",
            "Host: www.ip138.com",
            "Upgrade-Insecure-Requests: 1",
            "X-Forwarded-For: 122.70.40.218"
        );
        $qs = Curl::buildQuery($params);
        $fullURL = sprintf("%s?%s", $api, $qs);

        curl_setopt_array($ch, [
            CURLOPT_URL => $fullURL,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 5
        ]);

        $data = curl_exec($ch);
        Logger::write($data);
        $errno = curl_errno($ch);
        if ($errno) {
            throw new \RuntimeException("%s\n", curl_error($ch), $errno);
        }
        curl_close($ch);
        $statusCode = Curl::getStatusCode($data);
        if ($statusCode !== 200) {
            return $data;
        }
        $data = Str::extractAfterDelim($data, "\r\n\r\n");
        $html = Str::htmlUtf8FromGb2312($data);
        // <td align="center"><ul class="ul1">xxxx</ul></td>
        $matches = array();
        preg_match('/<td align="center"><ul class="ul1">.*<\/ul><\/td>/', $html, $matches);
        return $matches[0];
    }
}