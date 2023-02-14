<?php


namespace Bootstrap;

final class Env
{
    public function __construct()
    {
        $file = fopen('.env', 'r');
        while (!feof($file)) {
            $setting = explode('=', fgets($file));
            $_ENV[$setting[0]] = $setting[1];
        }
        fclose($file);
    }
}