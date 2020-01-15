<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crawler extends CI_Controller {

	/**
	 * Generowanie headerów dla OLX
	 */
	public function check() {
		$crawler = new Crawler\CoreCookies("https://www.olx.pl/motoryzacja/samochody/", $this->db, 
			"Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
		$crawler->setLimitStart(microtime(true));

		$crawler->check("https://www.olx.pl/motoryzacja/samochody/");
	}
	
	/**
	 * A to jest upload do elastica
	 */
	public function elastic()
	{
		//header('Content-Type: application/json');
		$query = $this->db->query("SELECT * FROM cars WHERE `elastic` IS NULL ORDER BY id ASC LIMIT 150");
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

			$this->db->query("UPDATE cars SET elastic = ? WHERE id = ?", [time(), $db_id]);
		}

		echo '<html><head><meta http-equiv="refresh" content="1"></head></html>';
	}

	/**
	 * To jest pobieranie szczegółowych danych nt.
	 * ogłoszenia
	 */
	public function show()
	{
		$query = $this->db->query("SELECT * FROM vehicles 
			WHERE `date` IS NULL 
			ORDER BY id ASC LIMIT 60");
		$result = $query->result_array();

		// set visited
		//$this->db->query("UPDATE fresh SET visited = CURRENT_TIMESTAMP WHERE id = ?", [$result[0]['id']]);

		$crawler = new Crawler\OLX_Cars_Core($result[0]['link'], $this->db, 
			"Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
		$crawler->setLimitStart(microtime(true));

		foreach($result as $r) {
			//var_dump($r['link']);
			$crawler->crawl($r['link'], 1);
			$this->db->query("UPDATE vehicles SET date = CURRENT_TIMESTAMP WHERE id = ?", [$r['id']]);
			echo 'Crawl the: ' . $r['link'] . '<br>';
		}
	}
}