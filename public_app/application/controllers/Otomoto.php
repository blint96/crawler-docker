<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Getting car links for Otomoto
 */
class Otomoto extends CI_Controller {

    private $directory = "cache/otomoto/";

    private $brands = [
        "alfa-romeo", "audi", "bmw", "cadillac", "chevrolet", "chrysler", "citroen", "dacia", "daewoo", "renault",
        "fiat", "ford", "honda", "hyundai", "jeep", "kia", "lexus", "mazda", "mercedes-benz", "mitsubishi", "nissan", "opel", "peugeot", "volkswagen", "toyota"
    ];

    /**
     * Clear counter for all brands
     */
    public function index()
	{
        foreach($this->brands as $brand) {
            file_put_contents($this->directory . $brand . ".txt", "1");
        }
    }

    /**
     * Crawling links for found brand
     */
    public function links() {
        $_brand = NULL; $_counter = 1;
        foreach($this->brands as $brand) {
            $counter = (int) file_get_contents($this->directory . $brand . ".txt");
            if($counter < 500) {
                $_brand = $brand;
                $_counter = $counter;
                break;
            }
        }

        if($_brand != NULL) {
            $starturl = "https://www.otomoto.pl/osobowe/" . $_brand . "/?page=" . $_counter;
            $crawler = new Crawler\CoreLinksOtomoto($starturl, $this->db, "Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []);
            $crawler->init();
            
            $_counter += 1;
            file_put_contents($this->directory . $_brand . ".txt", $_counter);
        }

        var_dump($_brand);
    }

    /**************************************************************
     * CRAWL VEHICLE DETAILS
     *****************************************************************/

    /**
	 * To jest pobieranie szczegółowych danych nt.
	 * ogłoszenia
	 */
	public function show()
	{
		$query = $this->db->query("SELECT * FROM vehicles_otomoto 
			WHERE `date` IS NULL 
			ORDER BY id ASC LIMIT 60");
		$result = $query->result_array();

		/*$crawler = new Crawler\OLX_Cars_Core($result[0]['link'], $this->db, 
			"Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
        $crawler->setLimitStart(microtime(true));*/
        
        $crawler = new Crawler\OTOMOTO_Cars_Core($result[0]['link'], $this->db, 
            "Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
        $crawler->setLimitStart(microtime(true));

		foreach($result as $r) {
			//var_dump($r['link']);
			$crawler->crawl($r['link'], 1);
			//$this->db->query("UPDATE vehicles SET date = CURRENT_TIMESTAMP WHERE id = ?", [$r['id']]);
			echo 'Crawl the: ' . $r['link'] . '<br>';
		}
	}
}