<?php

namespace App;

class Pogled
{

	public $podaci = [];


	public function ucitaj_pogled($fajl)
	{
		require('./pogledi/' . $fajl . '.php');
	}
}
