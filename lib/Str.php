<?php

namespace lib;

class Str {

    public static function jsonp2json($s) {
        $s = Str::extractAfterDelim($s, "({");
        $s = rtrim($s);
        $last = strlen($s)-1;
        if ($s[$last] === "\n");
        return "{" .substr($s, 0, $last);
    }

    private static function findIndexFollowDelim($s, $delim) {
        $n = strlen($delim);
        $j = 0;
        for ($i = 0; isset($s[$i]) && $j < $n; $i++) {
            if ($delim[$j] === $s[$i]) {
                $j += 1;
            } else {
                $j = 0;
            }
        }
        return $i;
    }

    public static function extractAfterDelim($s, $delim = "\r\n\r\n") {
        return substr($s, self::findIndexFollowDelim($s, $delim));
    }

    public static function extractBeforeDelim($s, $delim = "\r\n\r\n") {
        return substr($s, 0, self::findIndexFollowDelim($s, $delim)-strlen($delim));
    }

    // \service\SmsProvider::send
    public static function isMobile($mobile) {
        $matches = array();
        preg_match('/^1[356789][0-9]{9}$/', $mobile, $matches);
        return !empty($matches);
    }

    public static function htmlUtf8FromGb2312($html) {
        $html = preg_replace('/charset=gb2312/', 'charset=UTF8', $html);
        $html = iconv('gbk', 'utf-8', $html);
        return $html;
    }

}