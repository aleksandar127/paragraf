<?php

namespace App\Kontroleri;


use App\Db;
use DateTime;
use Exception;
use App\PdfRos;
use App\Pogled;
use App\Zahtev;
use App\MejlSwift;
use App\Interfejsi\DbInterfejs;


class Osiguranja
{


    private $zahtev;
    public $db;


    public function __construct(Zahtev $zahtev, DbInterfejs $db)
    {
        $this->db = $db;
        $this->zahtev = $zahtev;
    }
    public function index()
    {   //Spisak svih polisa
        $d = $this->db->upit('SELECT polise.id,DATE_FORMAT(polise.datum_kreiranja, "%d-%m-%Y") AS datum_kreiranja,osiguranici.ime,DATE_FORMAT(osiguranici.datum_rodjenja, "%d-%m-%Y") AS datum_rodjenja, osiguranici.broj_pasosa,osiguranici.email,DATE_FORMAT(polise.datum_polaska, "%d-%m-%Y") AS datum_polaska,DATE_FORMAT(polise.datum_dolaska, "%d-%m-%Y") AS datum_dolaska,DATEDIFF( polise.datum_dolaska, polise.datum_polaska ) AS broj_dana,osiguranici_polise.nosilac,polise.tip_polise
        FROM polise
        JOIN osiguranici_polise ON osiguranici_polise.polise_id=polise.id 
        JOIN osiguranici ON osiguranici.id=osiguranici_polise.osiguranici_id
        WHERE osiguranici_polise.nosilac=1
        ORDER BY polise.id');
        //View sa tabelom
        $p = new Pogled();
        $p->podaci = $d->rezultat;
        $p->ucitaj_pogled('index');
    }

    public function novaPolisa()
    {
        //View sa formom
        $p = new Pogled();
        $p->ucitaj_pogled('forma');
    }

    public function kreirajPolisu()
    {

        //Parametri se prihvataju
        $data = file_get_contents("php://input");
        $parametri = json_decode($data, true);
        $parametri = $parametri["user"];

        // Serverska validacija forme
        if ($greske = $this->validator($parametri)) {
            $poruka = ['status' => 'greska', 'greske' => $greske];
            echo json_encode($poruka);
            exit();
        } else {
            $poruka = ['status' => 'uspesno'];
        }


        //Upisivanje u bazu
        try {

            DB::$konekcija->beginTransaction();

            if ($parametri['dodatniOsiguranici']) {
                $tipPolise = "grupna";
            } else {
                $tipPolise = "individualna";
            }

            //Provera da li osiguranik postoji u bazi ili se pravi novi
            $sql = "SELECT * FROM osiguranici WHERE broj_pasosa=?";
            $osiguranik = $this->db->upit($sql, [$parametri['passport']]);
            if ($osiguranik->rezultat) {
                $osiguranikId = $osiguranik->rezultat[0]['id'];
                $emailNosilac = $osiguranik->rezultat[0]['email'];
            } else {
                $emailNosilac = $parametri['email'];
                $sql = "INSERT INTO osiguranici (ime,broj_pasosa,email,datum_rodjenja,telefon) VALUES (?,?,?,?,?)";
                if ($parametri['phone'] === "")
                    $parametri['phone'] = null;
                $osiguranik = $this->db->upit($sql, [$parametri['name'], $parametri['passport'], $parametri['email'], $parametri['birthDate'], $parametri['phone']]);
                $osiguranikId = $osiguranik->poslednjiId;
            }
            //Kreira se nova polisa
            $sql = "INSERT INTO polise (datum_polaska,datum_dolaska,tip_polise) VALUES (?,?,?)";
            $polisa = $this->db->upit($sql, ['1987-04-02', '1988-04-02', $tipPolise]);
            $polisaId = $polisa->poslednjiId;

            //Dodaju se podaci u veznu tabelu
            $sql = "INSERT INTO osiguranici_polise (osiguranici_id,polise_id,nosilac) VALUES (?,?,?)";
            $osiguraniciPolise = $this->db->upit($sql, [$osiguranikId, $polisaId, 1]);
            $osiguraniciPoliseId = $osiguraniciPolise->poslednjiId;

            //Ako postoje grupni osiguranici ubacuju se na polisu
            if ($parametri['dodatniOsiguranici']) {
                foreach ($parametri['dodatniOsiguranici'] as  $osiguranikGrupa) {

                    $sql = "SELECT * FROM osiguranici WHERE broj_pasosa= ?";
                    $osiguranik = $this->db->upit($sql, [$osiguranikGrupa['passportGrupa']]);
                    if ($osiguranik->rezultat) {
                        $osiguranikId = $osiguranik->rezultat[0]['id'];
                    } else {

                        $sql = "INSERT INTO osiguranici (ime,broj_pasosa,datum_rodjenja) VALUES (?,?,?)";
                        $osiguranik = $this->db->upit($sql, [$osiguranikGrupa['imeGrupa'], $osiguranikGrupa['passportGrupa'], $osiguranikGrupa['datumRodjenjaGrupa']]);
                        $osiguranikId = $osiguranik->poslednjiId;

                        $sql = "INSERT INTO osiguranici_polise (osiguranici_id,polise_id,nosilac) VALUES (?,?,?)";
                        $osiguraniciPolise = $this->db->upit($sql, [$osiguranikId, $polisaId, 0]);
                    }
                }
            }
            // commit
            DB::$konekcija->commit();
        } catch (Exception $e) {
            DB::$konekcija->rollBack();
            echo "Greska: " . $e->getMessage();
        }
        //kreira se PDF fajl
        $pdf = new PdfRos();
        $polisa = $this->polisa($polisaId);
        $pdfMail = $pdf->stampaj($polisa);
        //Salje se Email
        $email = new MejlSwift();
        $email->posalji($emailNosilac, $pdfMail);
        echo json_encode($poruka);
    }

    public function grupnaPolisa()
    {
        //Podaci o jednoj grupnoj polisi
        $data = file_get_contents("php://input");
        $parametri = json_decode($data, true);

        echo json_encode($this->polisa($parametri['id']));
    }

    public function validator($parametri)
    {

        $greske = [];

        //email  
        filter_var($parametri["email"], FILTER_SANITIZE_STRING);
        if (!filter_var($parametri["email"], FILTER_VALIDATE_EMAIL)) {
            $greske["email"] = "Nije validan email";
        }

        //ime i prezime
        filter_var($parametri["name"], FILTER_SANITIZE_STRING);
        if (!preg_match("/^([a-zA-Z ]+)$/", $parametri["name"]) || strlen($parametri["name"]) < 5) {
            $greske["ime"] = "Unesite pravilno ime i prezime";
        }
        //broj pasosa
        filter_var($parametri["passport"], FILTER_SANITIZE_NUMBER_INT);
        if (!preg_match("/^([0-9]+)$/", $parametri["passport"]) || strlen($parametri["passport"]) != 9) {
            $greske["broj_pasosa"] = "Pasos mora imati tacno 9 cifara";
        }

        //telefon
        if (!empty($parametri["phone"])) {
            if (!preg_match("/^([0-9]+)$/", $parametri["phone"]) || strlen($parametri["phone"]) < 9) {
                $greske["telefon"] = "telefon nije validan,dozvoljeni karakteri 0-9,minimum 9 cifara";
            }
        }
        //datumi putovanja
        $datumPolaska = new DateTime($parametri["dateFrom"]);
        $datumPovratka = new DateTime($parametri["dateTo"]);
        $danas = new DateTime();
        $validanPovratak = $datumPolaska->diff($datumPovratka);
        $validanDatum = $danas->diff($datumPolaska);
        if ($validanDatum->invert || $validanPovratak->invert) {
            $greske["datum_putovanja"] = 'datumi putovanja nisu validni';
        }

        //datum rodjenja
        $danas = new DateTime();
        $datumRodjenja = new DateTime($parametri["birthDate"]);
        $validanDatum = $datumRodjenja->diff($danas);
        if ($validanDatum->invert) {
            $greske["datum_rodjenja"] = 'datum nije validan';
        }

        //tip polise
        if (!$parametri["policy"]) {
            $greske["tip_polise"] = 'morate odabrati vrstu polise';
        }

        if ($parametri['dodatniOsiguranici']) {
            foreach ($parametri['dodatniOsiguranici'] as $o) {
                //ime i prezime grupa
                filter_var($o["imeGrupa"], FILTER_SANITIZE_STRING);
                if (!preg_match("/^([a-zA-Z ]+)$/", $o["imeGrupa"]) || strlen($o["imeGrupa"]) < 5) {
                    $greske["ime"] = "Unesite pravilno ime i prezime";
                }
                //broj pasosa grupa
                filter_var($o["passportGrupa"], FILTER_SANITIZE_NUMBER_INT);
                if (!preg_match("/^([0-9]+)$/", $o["passportGrupa"]) || strlen($o["passportGrupa"]) != 9) {
                    $greske["broj_pasosa"] = "Pasos mora imati tacno 9 cifara";
                }
                //datum rodjenja grupa
                $danas = new DateTime();
                $datumRodjenja = new DateTime($o["datumRodjenjaGrupa"]);
                $validanDatum = $datumRodjenja->diff($danas);
                if ($validanDatum->invert) {
                    $greske["datum_rodjenja"] = 'datum rodjenja nije validan';
                }
            }
        }

        if (count($greske)) {
            return $greske;
        } else {
            return false;
        }
    }

    public function polisa($id)
    {
        //Vraca jednu polisu
        $d = $this->db->upit(
            'SELECT polise.id,DATE_FORMAT(polise.datum_kreiranja, "%Y-%m-%d") AS datum_kreiranja,osiguranici.ime,osiguranici.datum_rodjenja,osiguranici.broj_pasosa,osiguranici.email,polise.datum_polaska,polise.datum_dolaska,DATEDIFF( polise.datum_dolaska, polise.datum_polaska ) AS broj_dana,osiguranici_polise.nosilac,polise.tip_polise
        FROM polise
        JOIN osiguranici_polise ON osiguranici_polise.polise_id=polise.id 
        JOIN osiguranici ON osiguranici.id=osiguranici_polise.osiguranici_id
        WHERE polise.id=?',
            [$id]
        );
        return $d->rezultat;
    }
}
