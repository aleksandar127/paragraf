<?php

namespace App;

use App\Interfejsi\DbInterfejs;



class Ruter
{
    private $zahtev;
    private $db;


    public function __construct(Zahtev $zahtev, DbInterfejs $db)
    {
        $this->zahtev = $zahtev;
        $this->db = $db;
        $this->InstancirajKontroler();
    }

    private function InstancirajKontroler()
    {
        $kontroler = $this->zahtev->kontroler;
        $kontroler = '\\App\\kontroleri\\' . $kontroler;

        if (class_exists($kontroler)) {
            $kon = new $kontroler($this->zahtev, $this->db);
            if (method_exists($kon, $this->zahtev->metod)) {
                $kon->{$this->zahtev->metod}();
            } else {
                header('HTTP/1.1 404 Not Found');
                exit();
            }
        } else {
            header('HTTP/1.1 404 Not Found');
            exit();
        }
    }
}
