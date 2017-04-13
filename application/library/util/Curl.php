<?php
/**
 * Curl工具类
 */
class Util_Curl{

    public static function request($url, $type, $data = false, $header=[], $timeout = 0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if(!empty($header)){
            // 设置请求头
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        // 设置需要返回Body
        curl_setopt($cl, CURLOPT_NOBODY, 0);

        // 设置超时时间
        if ($timeout > 0) {
            curl_setopt($cl, CURLOPT_TIMEOUT, $timeout);
        }

        $type = strtoupper($type);
        if ($type == 'POST'){
            curl_setopt($cl, CURLOPT_POST, true);
            curl_setopt($cl, CURLOPT_POSTFIELDS, $data);
        }
        if ($type == 'GET'){
            if(stripos($url, "?") === false){
                $url .= '?';
            }
            $url .= http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($errno == 0 && isset($status['http_code'])) {
            $header = substr($response, 0, $status['header_size']);
            $body = substr($response, $status['header_size']);
            return array($body, $header, $status, 0, '');
        } else {
            return array('', '', $status, $errno, $error);
        }
    }
}