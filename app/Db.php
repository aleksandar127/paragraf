<?php

namespace App;


use \PDO;
use Exception;
use PDOException;
use App\Interfejsi\DbInterfejs;


class Db implements DbInterfejs
{

    public static $konekcija;
    public $rezultat, $poslednjiId, $zbirRedova;


    public function __construct()
    {
        if (!self::$konekcija)
            $this->konekcija();
    }


    public function konekcija()
    {

        try {

            self::$konekcija = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME . ';charset=utf8mb4', DBUSERNAME, DBPASS);
            self::$konekcija->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

            die($e->getMessage());
        }
    }



    public function upit($sql, $parametri = [])
    {

        $rezultat = self::$konekcija->prepare($sql);

        if ($parametri) {
            $i = 1;
            foreach ($parametri as $vrednosti) {
                $rezultat->bindValue($i, $vrednosti);
                $i++;
            }
        }
        $rezultat->execute();
        $this->poslednjiId = self::$konekcija->lastInsertId();
        $this->zbirRedova = $rezultat->rowCount();
        if (stripos($sql, 'select') !== false) {

            $this->rezultat = $rezultat->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this;
    }


    public function redPostoji($sql, $parametri = [])
    {
        $rez = $this->upit($sql, $parametri);
        return $rez->zbirRedova;
    }
}
