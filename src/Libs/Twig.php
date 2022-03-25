<?php

namespace App\Libs;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
    private static $instance;
    private static $twig;

    private function __construct()
    {
        $templates = VIEWS;

        $loader = new FilesystemLoader($templates);
        self::$twig = new Environment($loader);
    }

    public static function getTwig(): Environment
    {
        if (!isset(self::$instance)) {
            self::$instance = new Twig();
        }

        return self::$twig;
    }

    /**
     * rend impossible le clonage
     */
    private function __clone()
    {
    }
}
