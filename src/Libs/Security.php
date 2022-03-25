<?php

namespace App\Libs;

class Security
{
    public static function secureHtml($string)
    {
        return strip_tags($string);
    }
    public static function isConnected()
    {
        return (!empty($_SESSION['profil']));
    }
}
