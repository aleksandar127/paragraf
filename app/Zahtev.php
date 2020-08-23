<?php

namespace App;

class Zahtev
{


    public $parametri;
    public $kontroler;
    public $metod;


    public function __construct()
    {


        $this->kontroler();
    }


    private function kontroler()
    {
        $url = $_SERVER['REQUEST_URI'];
        str_replace(ROOT, "", $url);
        if (strpos($_SERVER['REQUEST_URI'], '?')) {
            $url = explode('?', $_SERVER['REQUEST_URI'])[0];
        }
        $urlDelovi = explode('/', $url);
        $this->kontroler = $urlDelovi[2] ? $urlDelovi[2] : 'Osiguranja';
        $this->metod =  (isset($urlDelovi[3]) && $urlDelovi[3] !== "") ? $urlDelovi[3] : 'index';
    }
}
