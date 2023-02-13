<?php


namespace App\Services;


class Crypt
{
    const KEY = 1;

    public static function encode($unencoded)
    {
        $string = base64_encode($unencoded);

        $arr = [];
        $x = 0;
        $newstr = '';
        while ($x < strlen($string)) {
            $arr[$x-1] = md5(md5(Crypt::KEY.$string[$x-1]).Crypt::KEY);
            $newstr = $newstr.$arr[$x-1][3].$arr[$x-1][6].$arr[$x-1][1].$arr[$x-1][2];
            $x++;
        }
        return $newstr;
    }

    public static function decode($encoded)
    {
        $strofsym = "qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";
        $x = 0;
        while ($x <= strlen($strofsym)) {
            $tmp = md5(md5(Crypt::KEY.$strofsym[$x-1]).Crypt::KEY);
            $encoded = str_replace($tmp[3].$tmp[6].$tmp[1].$tmp[2], $strofsym[$x-1], $encoded);
            $x++;
        }
        return base64_decode($encoded);
    }
}