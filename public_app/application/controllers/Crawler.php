<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crawler extends CI_Controller {
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

		//var_dump($crawler->getVisited());

		//$response = $crawler->getContent();
		//var_dump($response);
	}
}