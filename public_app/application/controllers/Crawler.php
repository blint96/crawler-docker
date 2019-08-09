<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crawler extends CI_Controller {
	/**
	 * A to jest upload do elastica
	 */
	public function elastic()
	{
		header('Content-Type: application/json');
		$query = $this->db->query("SELECT * FROM cars WHERE `elastic` IS NULL ORDER BY id ASC LIMIT 50");
		$result = $query->result_array();

		// Authorization: Basic dXNlcjpYTmdSV1hCWFdOSDE=

		foreach($result as $r) {
			$db_id = (int) $r['id'];
			$original_id = (int) $r['original_id'];
			unset($r['id']);
			unset($r['original_id']);
			unset($r['elastic']);

			$payload = json_encode($r);

			$ch = curl_init('http://34.90.90.63/elasticsearch/auta/auto/' . $original_id);                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(      
				'Authorization: Basic dXNlcjpYTmdSV1hCWFdOSDE=',                                                                  
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($payload))                                                                       
			);                                                                                                                   
																																
			$result = curl_exec($ch);
			//var_dump($result);

			$this->db->query("UPDATE cars SET elastic = ? WHERE id = ?", [time(), $db_id]);
		}
	}
	
	/**
	 * To już ta finalna metoda do ściągania wszystkich samochodów z OLXa
	 * Wszystkich, tj. wszystkie strony /motoryzacja/
	 */
	public function vehicle()
	{
		$test = file_get_contents("page.txt");
		$starturl = "https://www.olx.pl/motoryzacja/samochody/?page=" . $test;
		$test = (int) $test + 1;
		file_put_contents("page.txt", $test);
		$crawler = new Crawler\CoreLinks($starturl, $this->db, "Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []);
		$crawler->init();
	}

	/**
	 * To jest pobieranie szczegółowych danych nt.
	 * ogłoszenia
	 */
	public function show()
	{
		$query = $this->db->query("SELECT * FROM vehicles 
			WHERE `date` IS NULL 
			ORDER BY id ASC LIMIT 30");
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
	
	public function test()
	{
		$query = $this->db->query("SELECT * FROM fresh 
			WHERE `link` LIKE '%oferta%' 
			AND `visited` IS NULL 
			AND id = 88332
			ORDER BY id ASC LIMIT 1");
		$result = $query->result_array();

		$crawler = new Crawler\OLX_Cars_Core($result[0]['link'], $this->db, 
			"Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
		$crawler->setLimitStart(microtime(true));

		$test = $crawler->crawl($result[0]['link'], 1);
		var_dump($test);

		var_dump($result);
	}

	public function cars()
	{
		$query = $this->db->query("SELECT * FROM fresh 
			WHERE `link` LIKE '%oferta%' 
			AND `visited` IS NULL 
			ORDER BY id ASC LIMIT 30");
		$result = $query->result_array();

		// set visited
		//$this->db->query("UPDATE fresh SET visited = CURRENT_TIMESTAMP WHERE id = ?", [$result[0]['id']]);

		$crawler = new Crawler\OLX_Cars_Core($result[0]['link'], $this->db, 
			"Mozilla/5.0 (Windows NT x.y; Win64; x64; rv:10.0) Gecko/20100101 Firefox/10.0", 1, []); 
		$crawler->setLimitStart(microtime(true));

		foreach($result as $r) {
			//var_dump($r['link']);
			$crawler->crawl($r['link'], 1);
			$this->db->query("UPDATE fresh SET visited = CURRENT_TIMESTAMP WHERE id = ?", [$r['id']]);
			echo 'Crawl the: ' . $r['link'] . '<br>';
		}
	}

	public function index() {
		// "https://www.olx.pl/motoryzacja/samochody/"
		$query = $this->db->query("SELECT * FROM fresh ORDER BY id DESC LIMIT 1");
		$result = $query->row_array();

		// prevent from view again
		$this->db->query("DELETE FROM fresh WHERE id = ?", [$result['id']]);

		echo 'Crawling start at: ' . $result['link'];

		$filters = [
			"pomoc.olx.pl",
			"tu-dodasz-reklame.olx.pl"
		];

		// init crawler
		$crawler = new Crawler\Core($result['link'], $this->db, "Mozilla", 5, $filters);
		$crawler->init();

		// cleanup links after all
		$this->db->query("DELETE FROM `fresh` WHERE `link` NOT LIKE '%oferta%' ");

		// remove first /kontakt/
		$this->db->query("DELETE FROM `fresh` WHERE `link` LIKE '%kontakt%'");

		//var_dump($crawler->getVisited());

		//$response = $crawler->getContent();
		//var_dump($response);
	}
}