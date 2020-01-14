<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Getting car links for Otomoto
 */
class Otomoto extends CI_Controller {

    private $directory = "cache/otomoto/";

    private $pageLimit = 15;

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
            if($counter < $this->pageLimit) {
                $_brand = $brand;
                $_counter = $counter;
                break;
            }
        }

        // update now caching brand
        $this->db->query("UPDATE cached SET value = ? WHERE name = ?", [$_brand, "OTOMOTO_NOW"]);

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
        
        $crawler = new Crawler\OTOMOTO_Cars_Core($result[0]['link'], $this->db, 
            "Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
        $crawler->setLimitStart(microtime(true));

		foreach($result as $r) {
			$crawler->crawl($r['link'], 1);
			$this->db->query("UPDATE vehicles_otomoto SET date = CURRENT_TIMESTAMP WHERE id = ?", [$r['id']]);
			echo 'Crawl the: ' . $r['link'] . '<br>';
		}
    }
    
    /**************************************************************
     * ELASTIC
     *****************************************************************/


    /**
	 * A to jest upload do elastica
	 */
	public function elastic()
	{
		header('Content-Type: application/json');
		$query = $this->db->query("SELECT * FROM cars_otomoto WHERE `elastic` IS NULL ORDER BY id ASC LIMIT 150");
		$result = $query->result_array();

		// Authorization: Basic dXNlcjpYTmdSV1hCWFdOSDE=

		foreach($result as $r) {
			$db_id = (int) $r['id'];
			$original_id = (int) $r['original_id'];
			unset($r['id']);
			unset($r['original_id']);
			unset($r['elastic']);

			$payload = json_encode($r);

			$ch = curl_init('http://34.89.133.205/elasticsearch/auta/auto/' . $original_id);                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(      
				'Authorization: Basic dXNlcjpBTFJFNjNvSG5TMzQ=',                                                                  
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($payload))                                                                       
			);                                                                                                                   
																																
			$result = curl_exec($ch);
			//var_dump($result);

			$this->db->query("UPDATE cars_otomoto SET elastic = ? WHERE id = ?", [time(), $db_id]);
		}
	}
}