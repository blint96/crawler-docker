<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extend extends CI_Controller {

    private $brands = [
        "alfa-romeo", "audi", "bmw", "cadillac", "chevrolet", "chrysler", "citroen", "dacia", "daewoo", "renault",
        "fiat", "ford", "honda", "hyundai", "jeep", "kia", "lexus", "mazda", "mercedes-benz", "mitsubishi", "nissan", "opel", "peugeot", "volkswagen", "toyota"
    ];

	public function index()
	{
        foreach($this->brands as $brand) {
            file_put_contents($brand . ".txt", "1");
        }
    }

    public function links() {
        $_brand = NULL; $_counter = 1;
        foreach($this->brands as $brand) {
            $counter = (int) file_get_contents($brand . ".txt");
            if($counter < 500) {
                $_brand = $brand;
                $_counter = $counter;
                break;
            }
        }

        if($_brand != NULL) {
            $starturl = "https://www.olx.pl/motoryzacja/samochody/" . $_brand . "/?page=" . $_counter;
            $crawler = new Crawler\CoreLinks($starturl, $this->db, "Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []);
            $crawler->init();
            
            $_counter += 1;
            file_put_contents($_brand . ".txt", $_counter);
        }

        var_dump($_brand);
    }
}