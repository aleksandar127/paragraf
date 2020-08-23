<?php

namespace App\Interfejsi;




interface DbInterfejs
{

    public function upit($sql, $parametri = []);

    public function redPostoji($sql, $parametri = []);

    public function konekcija();
}
