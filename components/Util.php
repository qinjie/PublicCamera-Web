<?php

namespace app\components;

/**
 * Created by PhpStorm.
 * User: qj
 * Date: 9/15/14
 * Time: 5:16 PM
 */
class Util
{
    /**
     * Generate a token of 32 byte
     */
    static function generateMd5Token()
    {
        $token = md5(uniqid(mt_rand(), true));
        return $token;
    }

    /**
     * Generate random string composed from lower case, upper case, and numbers
     */
    static function generateRandomToken($length = 24)
    {

        $token = md5(uniqid(mt_rand(), true));

        $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXZ0123456789";
        srand((double)microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= $length) {
            $num = mt_rand() % strlen($chars);
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }


    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}