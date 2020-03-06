<?php

namespace App\Service;

class Validator
{
    static public function validateUrl(string $url)
    {
        $headers = self::getHeaders($url);

        if(!$headers) {
            return false;
        }

        return strtok($headers, "\n");
    }

    static private function getHeaders(string $url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => "spider",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_NOBODY => true
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}